<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Campus\Models\Description\DescriptionCampus;
use Botble\Campus\Models\Genealogy\Genealogy;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Life\Models\Ads\Ads;
use Botble\Life\Models\Ads\AdsCategories;
use Botble\Life\Models\Ads\AdsComments;
use Botble\Life\Models\Description;
use Botble\Life\Models\Notices;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Theme;

class AdsController extends Controller
{

    /**
     * @return \Response
     */
    public static function index(Request $request)
    {
        $style = $request->style ?? 0 ;
        Theme::setTitle(__('life').' | '.__('life.advertisements'));
        $ads = Ads::where('status', 'publish')->ordered();

        $today = date("Y-m-d 00:00:00");
        $ads->where(function ($query) use ($today) {
            $query->where('deadline', '>=', $today)->orWhere('is_deadline', 0);
        });
        Theme::breadcrumb()->add('Advertisements', route('life.advertisements_list'))->add("List", 'http:...');

        $categories = AdsCategories::where('status', 'publish')->get();

        $notices = NoticesIntroduction::code('ADS_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = Description::where('code', 'ADS_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('adsFE.create');
        return Theme::scope('life.ads.index', [
            'ads' => $ads->paginate(10),
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'style' => $style,
            'canCreate' => $canCreate,
        ])->render();
    }

    public static function detailNotice($id)
    {
        $notices = NoticesIntroduction::where('id', $id)->where('status', 'publish')->first();
        $notices->lookup = $notices->lookup + 1;
        $notices->save();

        $ads = Ads::where('status', 'publish')->ordered()->paginate(10);

        Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
            ->add(__('life.advertisements'), route('life.advertisements_list'))
        ;
        $categories = AdsCategories::where('status', 'publish')->get();
        $style = 0;

        $description = DescriptionCampus::where('code', 'ADS_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('adsFE.create');
        return Theme::scope('life.ads.notice', [
            'ads' => $ads,
            'notices' => $notices,
            'description' => $description,
            'canCreate' => $canCreate,
            'subList' => [
                'ads' => $ads,
                'categories' => $categories,
                'style' => $style,
                'canCreate' => $canCreate,
            ]
        ])->render();


    }

    public function show($id)
    {
        $ads = Ads::where('status', 'publish')->withCount(['dislikes'])
            ->withCount(['likes'])->findOrFail($id);
        $ads->lookup = $ads->lookup + 1;
        $ads->save();
        $comments = AdsComments::where('advertisements_id', $id)->where('parents_id', null)->where('status','publish')
        ->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->paginate(10);

        $top_comments = AdsComments::where('advertisements_id', $id)->where('status','publish')
        ->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])
        ->having('likes_count', '>', 0)
        ->orderBy('likes_count', 'DESC')
        ->take(3)->get();

        Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
        ->add(__('life.advertisements'), route('life.advertisements_list'))
//        ->add($ads->title, 'http:...')
        ;

        Theme::setTitle($ads->title);


        if(hasPermission('memberFE.isAdmin') || @$ads->member_id ==  @auth()->guard('member')->user()['id'] ) {
            $canEdit = hasPermission('adsFE.edit');
            $canDelete = hasPermission('adsFE.delete');
        } else {
            $canEdit = false;
            $canDelete = false;
        }

        $canCreateComment = hasPermission('adsFE.comment.create');
        $canDeleteComment = hasPermission('adsFE.comment.delete');
        $canViewComment = hasPermission('adsFE.comment');

        $style = 0;
        $categories = AdsCategories::where('status', 'publish')->get();
        $subAds = Ads::where('status', 'publish')->ordered()->paginate(10);
        $canCreate = hasPermission('adsFE.create');

        return Theme::scope('life.ads.details', [
            'ads' => $ads,
            'comments' => $comments,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => $top_comments,
            'subList' => [
                'ads' => $subAds,
                'categories' => $categories,
                'style' => $style,
                'canCreate' => $canCreate,
            ]
        ])->render();
    }

    public function createComment(Request $request)
    {
        $file = "";
        if (auth()->guard('member')->check()) {
            $anonymous = $request->is_secret_comments ?? 0;
            $advertisements_id = $request->advertisements_id;
            $content = $request->content;
            $parents_id = $request->parents_id;
            if( $anonymous == 1) {
                $ip_address = $request->ip();
            }

            $comments = new AdsComments;
            $comments->advertisements_id = $advertisements_id;
            $comments->anonymous = $anonymous;
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->ip_address = $ip_address ?? null;
            $comments->save();


            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comments->id,
                'slug' => "comment-life-advertisements-".$comments->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
            //file
            if ($request->hasFile('commentFile')) {
                $file_link = \RvMedia::handleUpload($request->commentFile, $folder->id ?? 0);
                if ($file_link['error'] == false) {
                    $file = $file_link['data']->url;
                }
                $comments->file_upload = $file;
            }
            $comments->save();

            addPointForMembers(1);

            return redirect()->back();
        } else {
            // return to login
            return redirect()->back();
        }
    }

    public function deleteComment($id){
        if(hasPermission('memberFE.isAdmin') ){
            $comments = AdsComments::findOrFail($id);
        } else {
            $comments = AdsComments::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }
        // foreach ($comments->getAllCommentByParentsID($id) as $item){
        //     $item->delete();
        // }
        // $comments->delete();

        if($comments->parents_id > 0){
            $parentComment = AdsComments::findOrFail($comments->parents_id);
            $sameChildComment = $parentComment->getAllCommentByParentsID($comments->parents_id);

            if($sameChildComment->count() > 1){
                $countDelete = 0;
                foreach($sameChildComment as $key=>$item){
                    if($item->id == $id){
                        $item->is_deleted = 1;
                        $item->save();
                        $countDelete++;
                    }else{
                        if($item->is_deleted == 1){
                            $countDelete++;
                        }
                    }
                }

                $flagDeleteParent = false;
                if($countDelete==$sameChildComment->count()){
                    $flagDeleteParent = true;
                    foreach($sameChildComment as $key=>$item){
                        $item->delete();
                    }

                }

                if($parentComment->is_deleted == 1){
                    if($flagDeleteParent){
                        $parentComment->delete();
                    }
                }
            }else{
                $comments->delete();
                if($parentComment->is_deleted == 1){
                    $parentComment->delete();
                }
            }

        }
        else{
            $allChildComment = $comments->getAllCommentByParentsID($id);
            if($allChildComment->count() > 0){
                $comments->is_deleted = 1;
                $comments->save();
            }else{
                $comments->delete();
            }
        }

        $file_delete = $comments->file_upload;
        if ($file_delete) {
            # code...
            $file = MediaFile::where('url', $file_delete)->first();
            if ($file) {
                $file->forceDelete();
            }
            $uploadManager = new UploadsManager;
            $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $file_delete);
            $uploadManager->deleteFile($path , 1);

            $folder = MediaFolder::where('slug', "comment-life-advertisements-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }




        return redirect()->back()->with('success', __('controller.deletecomment'));
    }

    public static function getList()
    {
        //getDataByCurrentLanguageCode

        $ads = Ads::where('status','draft')->orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);

        Theme::breadcrumb()->add(__('life.advertisements'), route('life.advertisements_list'))->add(__('life.advertisements.advertisements_list'), 'http:...');

        Theme::setTitle(__('life.advertisements.advertisements_list'));

        return Theme::scope('life.ads.ads-fe-list', ['ads' => $ads])->render();
    }

    public static function getCreate()
    {
        $ads = Ads::where('status','draft')->orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->first();

        if( is_null($ads)){
            Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
            ->add(__('life.advertisements'), route('life.advertisements_list'))
            ->add(__('life.advertisements.create_advertisements'), 'http:...');
            Theme::setTitle(__('life') . ' | '.__('life.advertisements').' | ' .__('life.advertisements.create_advertisements'));

            $categories = AdsCategories::where('status', 'publish')->get();
            $description = Description::where('code', 'ADS_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
            return Theme::scope('life.ads.ads-fe-create', ['ads' => null, 'categories' => $categories,'description'=>$description])->render();
        } else {
            return redirect()->route('adsFE.edit',['id'=>$ads->id]);
        }

    }

    public function postStore(Request $request)
    {
        //-------------------
        $file = [];
        $link = [];
        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }

        $start = Carbon::createFromFormat('Y.m.d', $request->input('start') ?? getToDate1())->format('Y-m-d 00:00:00');
        $deadline = Carbon::createFromFormat('Y.m.d', $request->input('deadline') ?? getToDate(1))->format('Y-m-d 23:59:00');

        $request->merge(['link' => json_encode($link)]);
        $request->merge(['start' => $start]);
        $request->merge(['deadline' => $deadline]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        $request->validate([
            'title' => 'required|max:120',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb,
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        if($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }

        if (is_null($request->input('is_deadline'))) {
            $request->merge(['is_deadline' => 0]);
        }

        $request->merge(['categories' => $request->input('categories.2')]);

        $ads = new Ads;
        $ads = $ads->create($request->input());

        $parent = MediaFolder::where('slug', 'ads-fe')->first();
        $folder = MediaFolder::create([
            'name' => $ads->id,
            'slug' => $ads->id,
            'user_id' => '0',
            'parent_id' => $parent->id ?? 0,
        ]);

        if ($request->images) {
            //images
            $image_link = \RvMedia::handleUpload($request->images, $folder->id ?? 0);
            if ($image_link['error'] == false) {
                $ads->images = $image_link['data']->url;
            } else {
                return redirect()->back()->with('err', __('controller.save_image_failed'));
            }
        }

        //file
        if ($request->hasFile('file')) {
            foreach ($request->file as $key => $item) {

                $file_link = \RvMedia::handleUpload($item, $folder->id ?? 0);
                if ($file_link['error'] == false) {
                    array_push($file, $file_link['data']->url);
                } else {
                    return redirect()->back()->with('err', __('controller.save_file_failed',['file'=>($key + 1)]) );
                }
            }
            $ads->file_upload = json_encode($file);
        }

        $ads->save();

        addPointForMembers();

        $this->deleteFilePreview();

        event(new CreatedContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));

        return redirect()->route('life.advertisements_list')->with('success', __('controller.create_successful',['module'=>__('life.advertisements')]));
    }

    public static function getEdit($id)
    {
        if(hasPermission('memberFE.isAdmin') ){
            $ads = Ads::findOrFail($id);
        } else {
            $ads = Ads::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        $categories = AdsCategories::all();
        $selectedCategories = AdsCategories::where('id', $ads->categories)->where('status', 'publish')->first();
        $description = Description::where('code', 'ADS_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
        ->add(__('life.advertisements'), route('life.advertisements_list'))
        ->add(__('life.advertisements.edit_advertisements'), 'http:...');
        Theme::setTitle(__('life') . ' | '.__('life.advertisements').' | ' .__('life.advertisements.edit_advertisements').' #' . $ads->id );

        return Theme::scope('life.ads.ads-fe-create', ['ads' => $ads, 'categories' => $categories, 'selectedCategories' => $selectedCategories,'description'=>$description])->render();
    }

    public  function postUpdate($id, Request $request)
    {
        $file_upload = [];
        $link = [];
        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }

        $start = Carbon::createFromFormat('Y.m.d', $request->input('start') ?? getToDate(1))->format('Y-m-d 00:00:00');
        $deadline = Carbon::createFromFormat('Y.m.d', $request->input('deadline') ?? getToDate(1))->format('Y-m-d 23:59:00');

        $request->merge(['link' => json_encode($link)]);
        $request->merge(['start' => $start]);
        $request->merge(['deadline' => $deadline]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        if (is_null($request->input('is_deadline'))) {
            $request->merge(['is_deadline' => 0]);
        }

        $request->merge(['categories' => $request->input('categories.2')]);
        $request->validate([
            'title' => 'required|max:120',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb,
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        if(hasPermission('memberFE.isAdmin') ){
            $ads = Ads::findOrFail($id);
         } else {
            $ads = Ads::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
         }

        if($ads->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $parent = MediaFolder::where('slug', 'ads-fe')->first();
        $folder = MediaFolder::where('slug', $ads->id)->where('parent_id', $parent->id )->first();
        if(is_null($folder)){
            $folder = MediaFolder::create([
                'name' => $ads->id,
                'slug' => $ads->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }

        if ($request->hasFile('images')) {
            //lưu hình mới
            $image_link = \RvMedia::handleUpload($request->images, $folder->id ?? 0);

            if ($image_link['error'] != false) {
                return redirect()->route('adsFE.edit', ['id' => $id])->with('err',__('controller.save_image_failed'));
            }

            $request->merge(['images' => $image_link['data']->url]);

            //---------- xóa hình cũ ------------
            $file = MediaFile::where('url', $ads->images)->first();
            if ($file) {
                $file->forceDelete();
            }

            $uploadManager = new UploadsManager;
            $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $ads->images);
            $uploadManager->deleteFile($path, 1);
            //---------- ------------------

        }
        //delete old file
        $file_delete = $ads->file_upload;
        if ($request->input('delete') != null) {
            foreach ($request->delete as $key => $item) {
                if ($item != null) {
                    # code...
                    $file = MediaFile::where('url', $item)->first();
                    if ($file) {
                        $file->forceDelete();
                    }
                    $uploadManager = new UploadsManager;
                    $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $item);
                    $uploadManager->deleteFile($path, 1);

                    unset($file_delete[$key]);
                }
            }
        }
        //file
        if ($request->hasFile('file')) {
            foreach ($request->file as $key => $item) {

                $file_link = \RvMedia::handleUpload($item, $folder->id ?? 0);
                if ($file_link['error'] == false) {
                    if ($file_upload == null) {
                        $file_upload = [];
                    }
                    array_push($file_upload, $file_link['data']->url);
                } else {
                    return redirect()->back()->with('err', __('controller.save_file_failed',['file'=>($key + 1)])  );
                }
            }
        }
        if ($file_delete == null) {
            $file_delete = [];
        }
        $file_upload = array_merge($file_upload, $file_delete);
        $ads->file_upload = json_encode($file_upload);
        $ads = $ads->update($request->input());

        $this->deleteFilePreview();

        event(new CreatedContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));

        return redirect()->route('life.advertisements_list')->with('success', __('controller.update_successful',['module'=>__('life.advertisements')]));
    }

    public function getDownload(Request $request)
    {
        $url = $request->input('url');
        $filename = basename($url);

        if (substr($url, 0, 1) == "/") {
            $url = substr($url, 1);
        }
        return response()->download($url, $filename);
    }

    public function delete(Request $request)
    {
        if(hasPermission('memberFE.isAdmin') ){
            $ads = Ads::where('id', $request->input('id'))->firstOrFail();
        } else {
            $ads = Ads::where('id', $request->input('id'))->where('member_id', auth()->guard('member')->user()->id)->firstOrFail();
        }
        $parent = MediaFolder::where('slug', 'ads-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id', $parent->id)->first();
        if ($folder) {
            $directory = str_replace(basename($ads->images), '', $ads->images);
            $file = new Filesystem;

            // Xóa file trong server
            if ($file->exists($directory)) {

                $file->cleanDirectory($directory);

                // Get all files in this directory.
                $files = $file->files($directory);

                // Check if directory is empty.
                if (empty($files)) {
                    // Yes, delete the directory.
                    $file->deleteDirectory($directory);
                } else {
                    return redirect()->back()->with('err', __('controller.delete_failed'));
                }
            }
            $files = MediaFile::where('folder_id', $folder->id)->get();
            // xóa trong database media
            foreach ($files as $key => $item) {
                # code...
                $item->forceDelete();
            }
            $folder->forceDelete();

        }

        try {
            $ads->delete();

            event(new DeletedContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));

            return redirect()->route('life.advertisements_list')->with('success', __('controller.delete_successful',['module'=>__('life.advertisements')]));

        } catch (Exception $exception) {
            return redirect()->route('adsFE.list')->with('error',  __('controller.delete_failed'));
            return redirect()->route('life.advertisements_list')->with('success',  __('controller.delete_failed'));

        }
    }

    public function preview(Request $request)
    {
        $file = [];
        $link = [];
        $file_upload = [];
        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }

        $start = Carbon::createFromFormat('Y.m.d', $request->input('start') ?? getToDate(1))->format('Y-m-d 00:00:00');
        $deadline = Carbon::createFromFormat('Y.m.d', $request->input('deadline') ?? getToDate(1))->format('Y-m-d 23:59:00');

        $request->merge(['link' => json_encode($link)]);
        $request->merge(['start' => $start]);
        $request->merge(['deadline' => $deadline]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        $request->validate([
            'title' => 'required|max:120',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb,
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);

        if (is_null($request->input('is_deadline'))) {
            $request->merge(['is_deadline' => 0]);
        }

        $request->merge(['categories' => $request->input('categories.2')]);

        //file

        $ads = new Ads;
        $ads->title = $request->title;
        $ads->categories = $request->categories;
        $ads->details = $request->details;
        $ads->link = $request->link;
        $ads->start = $request->start;
        $ads->deadline = $request->deadline;
        $ads->member_id = $request->member_id;
        $ads->is_deadline = $request->is_deadline;
        $ads->recruitment = $request->recruitment;
        $ads->contact = $request->contact;
        $ads->duration = $request->duration;
        $ads->images = $request->base64Image;
        $ads->club = $request->club;


        if(!is_null($request->idAds) ){
            if(hasPermission('memberFE.isAdmin') ){
                $ads_item = Ads::findOrFail($request->idAds);
            } else {
                $ads_item = Ads::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idAds);
            }

            $file_delete = $ads_item->file_upload;

            if ($request->input('delete') != null) {
                foreach ($request->delete as $key => $item) {
                    if ($item != null) {
                        unset($file_delete[$key]);
                    }
                }
            }
            if ($file_delete == null) {
                $file_delete = [];
            }

            $file_upload = $file_delete;

        }

        if ($request->hasFile('file')) {

            $parent = MediaFolder::where('slug', 'ads-fe')->first();
            $folder = MediaFolder::where('slug',  auth()->guard('member')->user()->id_login)->where('parent_id', $parent->id)->first();
            if (is_null($folder)) {
                $folder = MediaFolder::create([
                    'name' => auth()->guard('member')->user()->id_login,
                    'slug' => auth()->guard('member')->user()->id_login,
                    'user_id' => '0',
                    'parent_id' => $parent->id ?? 0,
                ]);
            }
            //xóa file cũ

            if(!is_null($folder) && count($folder->files) >0 ){

                $directory = str_replace(basename($folder->files->first()->url), '', $folder->files->first()->url);

                if ($folder && $directory) {

                    $filesDelete = new Filesystem;

                    // Xóa file trong server
                    if ($filesDelete->exists($directory)) {

                        $filesDelete->cleanDirectory($directory);
                    }
                    $filesDelete = $folder->files;
                    // xóa trong database media
                    foreach ($filesDelete as $key => $item) {
                        # code...
                        $item->forceDelete();
                    }
                }
            }

            foreach ($request->file as $key => $item) {

                $file_link = \RvMedia::handleUpload($item, $folder->id ?? 0);
                if ($file_link['error'] == false) {
                    array_push($file, $file_link['data']->url);
                } else {
                    return redirect()->back()->with('err', __('controller.save_file_failed',['file'=>($key + 1)]) );
                }
            }
            $file_upload=  array_merge( $file,$file_upload);
        }


        $ads->file_upload = json_encode($file_upload);

        Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
        ->add(__('life.advertisements'), route('life.advertisements_list'))
        ->add(__('life.advertisements.preview'), 'http:...');

        Theme::setTitle(__('life').' | '.__('life.advertisements').' | '.__('life.advertisements.preview') );

        return Theme::scope('life.ads.preview', ['ads' => $ads])->render();

    }

    function deleteFilePreview(){
        $parent = MediaFolder::where('slug', 'ads-fe')->first();
        $folder = MediaFolder::where('slug',  auth()->guard('member')->user()->id_login )->where('parent_id', $parent->id)->first();

        if(!is_null($folder) && count($folder->files) >0 ){

            $directory = str_replace(basename($folder->files->first()->url), '', $folder->files->first()->url);

            if ($folder && $directory) {

                $file = new Filesystem;

                // Xóa file trong server
                if ($file->exists($directory)) {

                    $file->cleanDirectory($directory);

                    // Get all files in this directory.
                    $files = $file->files($directory);

                    // Check if directory is empty.
                    if (empty($files)) {
                        // Yes, delete the directory.
                        $file->deleteDirectory($directory);
                    } else {
                        return redirect()->back()->with('err', __('controller.delete_failed'));
                    }
                }
                $files = $folder->files;
                // xóa trong database media
                foreach ($files as $key => $item) {
                    # code...
                    $item->forceDelete();
                }
                $folder->forceDelete();

            }
        }

    }

    public static function dislike(Request $request) {
        $id = $request->post_id;
        $reason = $request->reason;

        $sympathy = Ads::findOrFail($id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_ads.member_id', $user->id);
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 0);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 1,
                    'reason' => $reason,
                    'updated_at' => Carbon::now()
                ]);
                $dislike = 2;
                if($reason!=""){
                    sympathyCommentDetail("advertisement",$id,$reason,"dislike");
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnPost("advertisement",$id,"dislike");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 1,
                    'reason' => $reason,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            ]);
            $dislike = 2;
            if($reason!=""){
                sympathyCommentDetail("advertisement",$id,$reason,"dislike");
            }
        }
        $sympathy = Ads::withCount([
            'dislikes',
        ])
            ->withCount([
                'likes',
            ])->findOrFail($id);

        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'disliked' => $dislike,
            ]
        );
    }

    public static function like(Request $request) {
        $id = $request->post_id;
        $reason = $request->reason;

        $sympathy = Ads::findOrFail($id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_ads.member_id', $user->id);
        $liked = 0;
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 1);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 0,
                    'reason' => $reason,
                    'updated_at' => Carbon::now()
                ]);
                $liked = 2;
                if($reason!=""){
                    sympathyCommentDetail("advertisement",$id,$reason,"like");
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnPost("advertisement",$id,"like");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            ]);
            $liked = 1;
            if($reason!=""){
                sympathyCommentDetail("advertisement",$id,$reason,"like");
            }
        }
        $sympathy = Ads::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->findOrFail($id);
        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'liked' => $liked
            ]
        );
    }

    public static function dislikeComments(Request $request)
    {
        $post_id = $request->post_id;
        $reason = $request->reason;
        $comment_id = $request->comment_id;
        $sympathy = AdsComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_ads_comments.member_id', $user->id);
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 0);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 1,
                    'post_id' => $post_id,
                    'reason' => $reason,
                    'updated_at' => Carbon::now()
                ]);
                $dislike = 2;
                if($reason!=""){
                    sympathyCommentDetail("advertisement", $post_id,$reason,"dislike",$comment_id);
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnComment("advertisement",$comment_id,"dislike");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 1,
                    'post_id' => $post_id,
                    'reason' => $reason,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $dislike = 2;
            if($reason!=""){
                sympathyCommentDetail("advertisement", $post_id,$reason,"dislike",$comment_id);
            }
        }
        $sympathy = AdsComments::withCount([
            'dislikes',
        ])
            ->withCount([
                'likes',
            ])->findOrFail($comment_id);

        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'disliked' => $dislike,
            ]
        );
    }

    public static function likeComments(Request $request)
    {
        $reason = $request->reason;
        $post_id = $request->post_id;
        $comment_id = $request->comment_id;
        $sympathy = AdsComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_ads_comments.member_id', $user->id);
        $liked = 0;
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 1);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 0,
                    'post_id' => $post_id,
                    'reason' => $reason,
                    'updated_at' => Carbon::now()
                ]);
                $liked = 2;
                if($reason!=""){
                    sympathyCommentDetail("advertisement", $post_id,$reason,"like",$comment_id);
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnComment("advertisement",$comment_id,"like");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 0,
                    'post_id' => $post_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $liked = 1;
            if($reason!=""){
                sympathyCommentDetail("advertisement", $post_id,$reason,"like",$comment_id);
            }
        }
        $sympathy = AdsComments::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->findOrFail($comment_id);
        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'liked' => $liked,
            ]
        );
    }
    public static function checkSympathyPermissionOnPost(Request $request) {
        $id = $request->id;

        $adsOwnerID = Ads::find($id)->member_id;
        $currentUserID = auth()->guard('member')->user()->id;
        $allow = 1;

        if($adsOwnerID == $currentUserID){
            $allow = 0;
        }

        return response()->json(
            [
                'valid' =>$allow,
            ]
        );
    }

    public static function checkSympathyPermissionOnComment(Request $request) {
        $commentId = $request->comment_id;
        $commentOwnerID = AdsComments::find($commentId)->member_id;
        $currentUserID = auth()->guard('member')->user()->id;
        $allow = 1;

        if($commentOwnerID == $currentUserID){
            $allow = 0;
        }

        return response()->json(
            [
                'valid' =>$allow,
            ]
        );
    }

}
