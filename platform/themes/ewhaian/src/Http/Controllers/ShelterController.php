<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Campus\Models\Description\DescriptionCampus;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Life\Models\Ads\Ads;
use Botble\Life\Models\Ads\AdsCategories;
use Botble\Life\Models\Description;
use Botble\Life\Models\Notices;
use Botble\Life\Models\Shelter\Shelter;
use Botble\Life\Models\Shelter\ShelterCategories;
use Botble\Life\Models\Shelter\ShelterComments;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Theme;

class ShelterController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            if(hasPermission('shelterFE.list')){
                return $next($request);
            };
            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        });
    }

    /**
     * @return \Response
     */
    public static function index(Request $request)
    {
        $style = $request->style ?? 0;
        // $shelter = Shelter::withCount(['dislikes'])
        // ->has('dislikes','<',10)
        // ->where('status', 'publish')->ordered()->paginate(9);

        $shelter = Shelter::where('status', 'publish')->ordered()->paginate(9);

        $categories = ShelterCategories::where('status', 'publish')->get();

        Theme::setTitle(__('life').' | '.__('life.shelter_info'));

        $notices = NoticesIntroduction::code('SHELTER_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = Description::where('code', 'SHELTER_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('shelterFE.create');
        return Theme::scope('life.shelter.index', [
            'shelter' => $shelter,
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'style' => $style,
            'canCreate' => $canCreate
        ])->render();

    }

    public static function detailNotice($id)
    {

        $style = $request->style ?? 0;
        $notices = NoticesIntroduction::where('id', $id)->where('status', 'publish')->first();
        $notices->lookup = $notices->lookup + 1;
        $notices->save();

        $shelters = Shelter::where('status', 'publish')->ordered()->paginate(9);

        Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
            ->add(__('life.shelter_info'), route('life.shelter_list'))
//        ->add($shelter->title, 'http:...')
        ;

        Theme::setTitle(__('life').' | '.__('life.shelter_info'));

        $categories = ShelterCategories::where('status', 'publish')->get();

        $description = DescriptionCampus::where('code', 'SHELTER_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('shelterFE.create');
        return Theme::scope('life.shelter.notice', [
            'shelter' => $shelters,
            'notices' => $notices,
            'description' => $description,
            'canCreate' => $canCreate,
            'subList' => [
                'shelter' => $shelters,
                'categories' => $categories,
                'style' => $style,
                'canCreate' => $canCreate
            ]
        ])->render();


    }

    public function show($id)
    {

        $shelter = Shelter::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])
        ->has('dislikes','<',10)
        ->where('id',$id)->where('status', 'publish')->firstOrFail();
        $shelter->lookup = $shelter->lookup + 1;
        $shelter->save();

        $comments = ShelterComments::where('shelter_id', $id)
        ->where('parents_id', null)->where('status','publish')
        ->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->paginate(10);

        $top_comments = ShelterComments::where('shelter_id', $id)
        ->where('status','publish')
        ->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])
        ->having('likes_count', '>', 0)
        ->orderBy('likes_count', 'DESC')
        ->take(3)->get();


        Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
        ->add(__('life.shelter_info'), route('life.shelter_list'))
//        ->add($shelter->title, 'http:...')
        ;

        Theme::setTitle(__('life').' | '.__('life.shelter_info').' | ' . $shelter->title);

        if(hasPermission('memberFE.isAdmin') || $shelter->member_id ==  auth()->guard('member')->user()->id ) {
            $canEdit = hasPermission('shelterFE.edit');
            $canDelete = hasPermission('shelterFE.delete');
        } else {
            $canEdit = false;
            $canDelete = false;
        }

        $canCreateComment = hasPermission('shelterFE.comment.create');
        $canDeleteComment = hasPermission('shelterFE.comment.delete');
        $canViewComment = hasPermission('shelterFE.comment');

        $style = $request->style ?? 0;
        $shelters = Shelter::where('status', 'publish')->ordered()->paginate(9);
        $categories = ShelterCategories::where('status', 'publish')->get();
        $canCreate = hasPermission('shelterFE.create');

        return Theme::scope('life.shelter.details', [
            'shelter' => $shelter,
            'comments' => $comments,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => $top_comments,
            'subList' => [
                'shelter' => $shelters,
                'categories' => $categories,
                'style' => $style,
                'canCreate' => $canCreate
            ]
        ])->render();
    }

    public function createComment(Request $request)
    {
        $file = "";
        if (auth()->guard('member')->check()) {

            $anonymous = $request->is_secret_comments ?? 0;

            $shelter_id = $request->shelter_id;
            $content = $request->content;
            $parents_id = $request->parents_id;
            if( $anonymous == 1) {
                $ip_address = $request->ip();
            }

            $comments = new ShelterComments;
            $comments->shelter_id = $shelter_id;
            $comments->member_id = auth()->guard('member')->user()->id ;
            $comments->anonymous = $anonymous;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->ip_address = $ip_address ?? null;
            $comments->save();


            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comments->id,
                'slug' => "comment-life-shelter-".$comments->id,
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
        if(hasPermission('memberFE.isAdmin') ) {
            $comments = ShelterComments::findOrFail($id);
        } else {
            $comments = ShelterComments::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }
        // foreach ($comments->getAllCommentByParentsID($id) as $item){
        //     $item->delete();
        // }
        // $comments->delete();

        if($comments->parents_id > 0){
            $parentComment = ShelterComments::findOrFail($comments->parents_id);
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

            $folder = MediaFolder::where('slug', "comment-life-shelter-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }

        return redirect()->back()->with('success', __('controller.deletecomment'));
    }

    public static function getList()
    {

        $shelter = Shelter::where('status', 'draft')->orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);

        Theme::breadcrumb()->add(__('life.shelter_info'), route('life.shelter_list'))->add(__('life.shelter_info.shelter_info_list'), 'http:...');

        Theme::setTitle(__('life').' | '.__('life.shelter_info'));

        return Theme::scope('life.shelter.shelter-fe-list', ['shelter' => $shelter])->render();

    }

    public static function getCreate()
    {
        $shelter = Shelter::where('status', 'draft')->orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->first();
        if(is_null($shelter )){
            $categories = ShelterCategories::where('status', 'publish')->get();
            $description = Description::where('code', 'SHELTER_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

            Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
            ->add(__('life.shelter_info'), route('life.shelter_list'))
            ->add(__('life.shelter_info.create_shelter'), 'http:...');
            Theme::setTitle(__('life') . ' | '.__('life.shelter_info').' | ' .__('life.shelter_info.create_shelter'));

            return Theme::scope('life.shelter.shelter-fe-create', ['shelter' => null, 'categories' => $categories,'description'=> $description])->render();
        } else {
            return redirect()->route('shelterFE.edit',['id'=>$shelter->id]);
        }

    }

    public function postStore(Request $request)
    {
        $file = [];
        $link = [];
        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }
        $request->merge(['link' => $link]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        $request->validate([
            'title' => 'required|max:120',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        if($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['categories' => $request->input('categories.2')]);

        $shelter = new Shelter;
        $shelter = $shelter->create($request->input());

        $images = $request->input('imagesValue');

        $parent = MediaFolder::where('slug', 'shelter-fe')->first();
        $folder = MediaFolder::create([
            'name' => $shelter->id,
            'slug' => $shelter->id,
            'user_id' => '0',
            'parent_id' => $parent->id ?? 0,
        ]);
        if(!is_null($request->images)){

            foreach ($request->images as $key => $value) {
                if ($images[$key] == 1) {
                    if ($request->hasFile('images.' . $key)) {
                        $image_link = \RvMedia::handleUpload($request->images[$key], $folder->id ?? 0);
                        if ($image_link['error'] == false) {
                            $images[$key] = $image_link['data']->url;
                            //Re-slug
                        } else {
                            return redirect()->back()->with('err', __('controller.save_failed'));
                        }
                    }
                }

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
            $shelter->file_upload = $file;
        }
        $shelter->images = json_encode($images);
        $shelter->save();

        addPointForMembers();
        $this->deleteFilePreview();
        event(new CreatedContentEvent(SHELTER_MODULE_SCREEN_NAME, $request, $shelter));

        return redirect()->route('life.shelter_list')->with('success', __('controller.create_successful',['module'=>__('life.shelter_info')]));
    }

    public static function getEdit($id)
    {
        if(hasPermission('memberFE.isAdmin') ) {
            $shelter = Shelter::findOrFail($id);
        } else {
            $shelter = Shelter::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        $categories = ShelterCategories::all();
        $selectedCategories = ShelterCategories::where('id', $shelter->categories)->where('status', 'publish')->first();
        $description = Description::where('code', 'SHELTER_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
        ->add(__('life.shelter_info'), route('life.shelter_list'))
        ->add(__('life.shelter_info.edit_shelter'), 'http:...');
        Theme::setTitle(__('life') . ' | '.__('life.shelter_info').' | ' .__('life.shelter_info.edit_shelter').' #' . $id);
//        dd($shelter->toArray());

        return Theme::scope('life.shelter.shelter-fe-create', ['shelter' => $shelter, 'categories' => $categories, 'selectedCategories' => $selectedCategories,'description'=>$description])->render();
    }

    public function postUpdate($id, Request $request)
    {
        $file_upload = [];
        $link = [];
        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }
        $request->merge(['link' => $link]);
        $request->merge(['categories' => $request->input('categories.2')]);
        $request->validate([
            'title' => 'required|max:120',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        $images = $request->input('imagesValue');
        if(hasPermission('memberFE.isAdmin') ) {
            $shelter = Shelter::findOrFail($id);
        } else {
            $shelter = Shelter::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        if($shelter->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $parent = MediaFolder::where('slug', 'shelter-fe')->first();
        $folder = MediaFolder::where('slug', $shelter->id)->where('parent_id', $parent->id)->first();
        if(is_null($folder)){
            $folder = MediaFolder::create([
                'name' => $shelter->id,
                'slug' => $shelter->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }
        if($shelter->images != ""){
            foreach ($shelter->images as $key => $item) {
                if ($request->hasFile('images.' . $key)) {

                    //lưu hình mới

                    $image_link = \RvMedia::handleUpload($request->images[$key], $folder->id ?? 0);

                    if ($image_link['error'] != false) {
                        return redirect()->back()->with('err', __('controller.save_failed'));
                    }

                    //---------- xóa hình cũ ------------

                    if (!is_null($item)) {
                        $file = MediaFile::where('url', $item)->first();
                        if ($file) {
                            $file->forceDelete();
                        }
                        /// xóa file hình trong server

                        $uploadManager = new UploadsManager;
                        $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $item);

                        $uploadManager->deleteFile($path, 1);

                    }

                    $images[$key] = $image_link['data']->url;

                }
                if ($images[$key] == null) {
                    $file = MediaFile::where('url', $item)->first();
                    if ($file) {
                        $file->forceDelete();
                    }
                    /// xóa file hình trong server

                    $uploadManager = new UploadsManager;
                    $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $item);

                    $uploadManager->deleteFile($path, 1);
                }
            }
        }
        $request->merge(['images' => json_encode($images)]);

        //delete old file
        $file_delete = $shelter->file_upload;
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
        $shelter->file_upload = $file_upload;

        $shelter = $shelter->update($request->input());
        $this->deleteFilePreview();
        event(new CreatedContentEvent(SHELTER_MODULE_SCREEN_NAME, $request, $shelter));

        return redirect()->route('life.shelter_list')->with('success',  __('controller.update_successful',['module'=>__('life.shelter_info')]));

    }

    public function delete(Request $request)
    {
        if(hasPermission('memberFE.isAdmin') ) {
            $shelter = Shelter::where('id',$request->input('id'))->firstOrFail();
        } else {
            $shelter = Shelter::where('id',$request->input('id'))->where('member_id',auth()->guard('member')->user()->id)->firstOrFail();
        }
        $parent = MediaFolder::where('slug', 'shelter-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id',$parent->id)->first();
        if ($folder) {
            $directory =  str_replace( basename(geFirsttImageInArray($shelter->images,null,1) ),'',geFirsttImageInArray($shelter->images,null,1) );
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
            $files = MediaFile::where('folder_id',$folder->id)->get();
            // xóa trong database media
            foreach ($files as $key => $item) {
                # code...
                $item->forceDelete();
            }
            $folder->forceDelete();
        }

        try {
            $shelter->delete();

            event(new DeletedContentEvent(SHELTER_MODULE_SCREEN_NAME, $request, $shelter));

            return redirect()->route('life.shelter_list')->with('success', __('controller.delete_successful',['module'=>__('life.shelter_info')]));

        } catch (Exception $exception) {
            return redirect()->route('life.shelter_list')->with('success', __('controller.delete_failed'));
        }
    }

    public function preview(Request $request){
        $file_upload = [];
        $link = [];
        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }

        $request->merge(['link' => $link]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        $request->merge(['categories' => $request->input('categories.2')]);
        $request->merge(['imagesBase64' => json_encode($request->imagesBase64)]);
        $request->validate([
            'title' => 'required|max:120',
            'contact' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);

        Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
        ->add(__('life.shelter_info'), route('shelterFE.list'))
        ->add(__('life.shelter_info.preview'), 'http:...');

        Theme::setTitle(__('life').' | '.__('life.shelter_info').' | '.__('life.shelter_info.preview') );

        $shelter = new Shelter;
        $shelter->title = $request->title;
        $shelter->contact = $request->contact;
        $shelter->status = $request->status;
        $shelter->images = $request->imagesBase64;
        $shelter->categories = $request->categories;
        $shelter->link = $request->link;

        $shelter->location = $request->location;
        $shelter->size = $request->size;
        $shelter->utility = $request->utility;
        $shelter->lease_period = $request->lease_period;
        $shelter->building_type = $request->building_type;
        $shelter->possible_moving_date = $request->possible_moving_date;
        $shelter->heating_type = $request->heating_type;
        $shelter->option = $request->option;
        $shelter->real_estate = $request->real_estate;

        $parent = MediaFolder::where('slug', 'shelter-fe')->first();
        $folder = MediaFolder::where('slug', auth()->guard('member')->user()->id_login )->where('parent_id', $parent->id ?? 0)->first();
        if (is_null($folder)){
            $folder = MediaFolder::create([
                'name' =>  auth()->guard('member')->user()->id_login,
                'slug' =>  auth()->guard('member')->user()->id_login,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }

        if(!is_null($request->idPreview) ){
            if(hasPermission('memberFE.isAdmin') ) {
                $preview_item = Shelter::findOrFail($request->idPreview);
            } else {
                $preview_item = Shelter::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idPreview);
            }

            $file_delete = $preview_item->file_upload;

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

        $shelter->file_upload = $file_upload;
        return Theme::scope('life.shelter.preview', ['shelter' => $shelter])->render();
    }

    function deleteFilePreview()
    {
        $parent = MediaFolder::where('slug', 'shelter-fe')->first();
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

    public static function dislike(Request $request)
    {
        $id = $request->post_id;
        $reason = $request->reason;

        $sympathy = Shelter::findOrFail($id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_shelter.member_id',$user->id);
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
                    sympathyCommentDetail("shelter",$id,$reason,"dislike");
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnPost("shelter",$id,"dislike");
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
                sympathyCommentDetail("shelter",$id,$reason,"dislike");
            }
        }
        $sympathy = Shelter::withCount([
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

    public static function like(Request $request)
    {
        $id = $request->post_id;

        $reason = $request->reason;
        $sympathy = Shelter::findOrFail($id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_shelter.member_id',$user->id);
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
                    sympathyCommentDetail("shelter",$id,$reason,"like");
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnPost("shelter",$id,"like");
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
                sympathyCommentDetail("shelter",$id,$reason,"like");
            }
        }
        $sympathy = Shelter::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->findOrFail($id);
        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'liked' =>  $liked
            ]
        );
    }

    public static function dislikeComments(Request $request)
    {
        $post_id = $request->post_id;
        $reason = $request->reason;
        $comment_id = $request->comment_id;
        $sympathy = ShelterComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_shelter_comments.member_id', $user->id);
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
                    sympathyCommentDetail("shelter", $post_id,$reason,"dislike",$comment_id);
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnComment("shelter",$comment_id,"dislike");
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
                sympathyCommentDetail("shelter", $post_id,$reason,"dislike",$comment_id);
            }
        }
        $sympathy = ShelterComments::withCount([
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
        $sympathy = ShelterComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_shelter_comments.member_id', $user->id);
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
                    sympathyCommentDetail("shelter", $post_id,$reason,"like",$comment_id);
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnComment("shelter",$comment_id,"like");
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
                sympathyCommentDetail("shelter", $post_id,$reason,"like",$comment_id);
            }
        }
        $sympathy = ShelterComments::withCount([
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

        $shelterOwnerID = Shelter::find($id)->member_id;
        $currentUserID = auth()->guard('member')->user()->id;
        $allow = 1;

        if($shelterOwnerID == $currentUserID){
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
        $commentOwnerID = ShelterComments::find($commentId)->member_id;
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
