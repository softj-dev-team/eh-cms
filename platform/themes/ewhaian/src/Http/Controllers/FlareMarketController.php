<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Life\Models\Description;
use Botble\Life\Models\Flare;
use Botble\Life\Models\FlareCategories;
use Botble\Life\Models\FlareComments;
use Botble\Life\Models\Jobs\JobsCategories;
use Botble\Life\Models\Jobs\JobsPartTime;
use Botble\Life\Models\Notices;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Theme;

class FlareMarketController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            if(hasPermission('flareMarketFE.list')){
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
        $categories = FlareCategories::where('status', 'publish')->get();
        // $flare = Flare::withCount(['dislikes'])
        // ->has('dislikes','<',10)
        // ->where('status','!=', 'draft')->orderBy('published', 'DESC')->paginate(10);

        $flare = Flare::where('status','!=', 'draft')->orderBy('published', 'DESC')->paginate(10);

        $notices = NoticesIntroduction::code('FLARE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = Description::where('code', 'FLARE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        Theme::setTitle(__('life').' | '.__('life.flea_market'));

        $canCreate = hasPermission('flareMarketFE.create');

        return Theme::scope('life.flare_market.index', [
            'flare' => $flare,
            'notices' => $notices,
            'categories' => $categories,
            'description' => $description,
            'style' => $style,
            'canCreate' => $canCreate
        ])->render();
    }

    public static function detailNotice($id)
    {
        $notices = NoticesIntroduction::where('id', $id)->where('status', 'publish')->first();
        $notices->lookup = $notices->lookup + 1;
        $notices->save();

        Theme::breadcrumb()->add(__('life.flea_market'), route('life.flare_market_list'));

        Theme::setTitle(__('life.flea_market'));

        $style = 0;
        $categories = FlareCategories::where('status', 'publish')->get();
        $flares = Flare::where('status','!=', 'draft')->orderBy('published', 'DESC')->paginate(10);
        $canCreate = hasPermission('flareMarketFE.create');
        return Theme::scope('life.flare_market.notice', [
            'flare' => $flares,
            'notices' => $notices,
            'canCreate' => $canCreate,
            'subList' => [
                'flare' => $flares,
                'categories' => $categories,
                'style' => $style,
                'canCreate' => $canCreate
            ]
        ])->render();


    }

    public function show($id)
    {

        $flare = Flare::withCount([
            'dislikes',
        ])
        ->withCount([
            'likes',
        ])
        ->has('dislikes','<',10)
        ->where('id',$id)->where('status','!=', 'draft')->firstOrFail();
        $flare->lookup = $flare->lookup + 1;
        $flare->save();

        $comments = FlareComments::where('flare_id', $id)->where('parents_id', null)->where('status','publish')
        ->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])
        ->paginate(10);

        $top_comments = FlareComments::where('flare_id', $id)->where('status','publish')
        ->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])
        ->having('likes_count', '>', 0)
        ->orderBy('likes_count', 'DESC')
        ->take(3)->get();

        Theme::breadcrumb()->add(__('life'), route('life.flare_market_list'))->add(__('life.flea_market'), route('life.flare_market_list'));

        Theme::setTitle(__('life.flea_market').' | ' . $flare->title);


        if(hasPermission('memberFE.isAdmin') || $flare->member_id ==  auth()->guard('member')->user()->id ) {
            $canEdit = hasPermission('flareMarketFE.edit');
            $canDelete = hasPermission('flareMarketFE.delete');
        } else {
            $canEdit = false;
            $canDelete = false;
        }

        $canCreateComment = hasPermission('flareMarketFE.comment.create');
        $canDeleteComment = hasPermission('flareMarketFE.comment.delete');
        $canViewComment = hasPermission('flareMarketFE.comment');

        $style = 0;
        $categories = FlareCategories::where('status', 'publish')->get();
        $flares = Flare::where('status','!=', 'draft')->orderBy('published', 'DESC')->paginate(10);
        $canCreate = hasPermission('flareMarketFE.create');

        return Theme::scope('life.flare_market.details', [
            'flare' => $flare,
            'comments' => $comments,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' =>$top_comments,
            'subList' => [
                'flare' => $flares,
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
            $flare_id = $request->flare_id;
            $content = $request->content;
            $parents_id = $request->parents_id;
            if( $anonymous == 1) {
                $ip_address = $request->ip();
            }

            $comments = new FlareComments;
            $comments->flare_id = $flare_id;
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->anonymous = $anonymous;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->ip_address = $ip_address ?? null;
            $comments->save();

            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comments->id,
                'slug' => "comment-life-flare-".$comments->id,
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
            $comments = FlareComments::findOrFail($id);
        } else {
            $comments = FlareComments::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }


        // foreach ($comments->getAllCommentByParentsID($id) as $item){
        //     $item->delete();
        // }
        // $comments->delete();

        if($comments->parents_id > 0){
            $parentComment = FlareComments::findOrFail($comments->parents_id);
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

            $folder = MediaFolder::where('slug', "comment-life-flare-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }

        return redirect()->back()->with('success', __('controller.deletecomment'));
    }

    public static function getList()
    {
        //getDataByCurrentLanguageCode

        $flare = Flare::orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);
        $categories = FlareCategories::where('parent_id', 1)->get();

        Theme::breadcrumb()->add(__('life.flea_market'), route('life.flare_market_list'))->add(__('life.flea_market.flea_market_list'), 'http:...');

        Theme::setTitle(__('life.flea_market.flea_market_list'));

        return Theme::scope('life.flare_market.flare-fe-list', ['flare' => $flare, 'categories' => $categories])->render();
    }

    public static function getCreate($categoryId)
    {
        $flare = Flare::where('status', 'draft')->where('member_id', auth()->guard('member')->user()->id)->first();

        if (is_null($flare)){

            $categories = FlareCategories::all();
            $parent = FlareCategories::where('parent_id', 1)->get();
            Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
            ->add(__('life.flea_market'), route('life.flare_market_list'))
            ->add(__('life.flea_market.create_flea_market'), 'http:...');
            Theme::setTitle(__('life') . ' | '.__('life.flea_market').' | ' .__('life.flea_market.create_flea_market'));

            $description = Description::where('code', 'FLARE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

            return Theme::scope('life.flare_market.flare-fe-create', ['flare' => null, 'categories' => $categories, 'parent' => $parent,'description'=>$description, 'categoryId' => $categoryId])->render();

        } else {
            return redirect()->route('flareMarketFE.edit',['id'=>$flare->id]);
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
        $request->validate([
            'title' => 'required|max:120',
            'contact' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:10000', // max 10000kb = 10 Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:10000', // max 10000kb = 10Mb
        ]);
        if ($request->input('exchange.4')) {
            $request->validate(['exchange.5' => 'required']);
        }
        try {
            $purchase_date = Carbon::createFromFormat('Y.m.d',$request->purchase_date)->startOfDay();

        } catch (\Exception $ex) {
            $purchase_date = null;
        }
        $request->merge(['purchase_date' =>  $purchase_date]);
        if($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['categories' => json_encode($request->categories)]);
        $request->merge(['exchange' => json_encode($request->exchange)]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        $images = $request->input('imagesValue');

        $flare = new Flare;
        $flare = $flare->create($request->input());

        $parent = MediaFolder::where('slug', 'flare-market-fe')->first();
        $folder = MediaFolder::create([
            'name' => $flare->id,
            'slug' => $flare->id,
            'user_id' => '0',
            'parent_id' => $parent->id ?? 0,
        ]);

        if (!is_null($request->images)) {


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
        $flare->images = json_encode($images);

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
            $flare->file_upload = $file;
        }

        $flare->save();

        addPointForMembers();
        $this->deleteFilePreview();

        event(new CreatedContentEvent(FLARE_MODULE_SCREEN_NAME, $request, $flare));

        if($request->status != 'draft') {
            $keyMsg = 'controller.create_successful';
        }else{
            $keyMsg = 'controller.create_draft';
        }
        return redirect()->route('life.flare_market_list')->with('success', __($keyMsg,['module'=>__('life.flea_market')]));
    }

    public static function getEdit($id)
    {
        if(hasPermission('memberFE.isAdmin') ) {
            $flare = Flare::findOrFail($id);
        } else {
            $flare = Flare::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        $categories = FlareCategories::all();

        Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
        ->add(__('life.flea_market'), route('life.flare_market_list'))
        ->add(__('life.flea_market.edit_flea_market'), 'http:...');
        Theme::setTitle(__('life') . ' | '.__('life.flea_market').' | ' .__('life.flea_market.edit_flea_market').' #' . $flare->id);

        $description = Description::where('code', 'FLARE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        return Theme::scope('life.flare_market.flare-fe-edit', ['flare' => $flare, 'categories' => $categories,'description'=>$description])->render();
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
        $request->validate([
            'title' => 'required|max:120',
            'reason_selling' => 'required',
            'sale_price' => 'required',
            'contact' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:10000', // max 10000kb = 10 Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:10000', // max 10000kb = 10Mb
        ]);
        if ($request->input('exchange.4')) {
            $request->validate(['exchange.5' => 'required']);
        }

        $request->merge(['categories' => json_encode($request->categories)]);
        $request->merge(['exchange' => json_encode($request->exchange)]);
        try {
            $purchase_date = Carbon::createFromFormat('Y.m.d',$request->purchase_date)->startOfDay();

        } catch (\Exception $ex) {
            $purchase_date = now()->startOfDay();
        }
        $request->merge(['purchase_date' =>  $purchase_date]);
        if(hasPermission('memberFE.isAdmin') ) {
            $flare = Flare::findOrFail($id);
        } else {
            $flare = Flare::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        if($flare->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        //--------------------------------------------------
        $images = $request->input('imagesValue');
        $parent = MediaFolder::where('slug', 'flare-market-fe')->first();
        $folder = MediaFolder::where('slug', $flare->id)->where('parent_id', $parent->id ?? 0)->first();
        if ($folder == null) {
            $folder = MediaFolder::create([
                'name' => $flare->id,
                'slug' => $flare->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }

        foreach ($flare->images as $key => $item) {
            if ($request->hasFile('images.' . $key)) {

                //lưu hình mới

                $image_link = \RvMedia::handleUpload($request->images[$key], $folder->id ?? 0);

                if ($image_link['error'] != false) {
                    return redirect()->back()->with('err',__('controller.save_failed'));
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
        $request->merge(['images' => json_encode($images)]);
        //delete old file
        $file_delete = $flare->file_upload;
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
        $flare->file_upload = $file_upload;

        $flare = $flare->update($request->input());
        $this->deleteFilePreview();
        event(new CreatedContentEvent(FLARE_MODULE_SCREEN_NAME, $request, $flare));

        if($request->status != 'draft') {
            $keyMsg = 'controller.update_successful';
        }else{
            $keyMsg = 'controller.create_draft';
        }

        return redirect()->route('life.flare_market_list')->with('success', __($keyMsg,['module'=>__('life.flea_market')]));
    }

    public function delete(Request $request)
    {
        if(hasPermission('memberFE.isAdmin') ) {
            $flare = Flare::firstOrFail();
        } else {
            $flare = Flare::where('id',$request->input('id'))->where('member_id',auth()->guard('member')->user()->id)->firstOrFail();
        }

        $parent = MediaFolder::where('slug', 'flare-market-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id',$parent->id)->first();

        if ($folder) {

            $directory =  str_replace( basename(geFirsttImageInArray($flare->images,null,1) ),'',geFirsttImageInArray($flare->images,null,1) );

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
            $flare->delete();

            event(new DeletedContentEvent(FLARE_MODULE_SCREEN_NAME, $request, $flare));

            return redirect()->route('life.flare_market_list')->with('success',__('controller.delete_successful',['module'=>__('life.flea_market')]));
        } catch (Exception $exception) {
            return redirect()->route('life.flare_market_list')->with('error',__('controller.delete_failed'));
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
        $request->validate([
            'title' => 'required|max:120',
            'reason_selling' => 'required',
            'sale_price' => 'required',
            'contact' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2 Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb
        ]);
        if ($request->input('exchange.4')) {
            $request->validate(['exchange.5' => 'required']);
        }

        $request->merge(['categories' => json_encode($request->categories)]);
        $request->merge(['exchange' => json_encode($request->exchange)]);
        $request->merge(['imagesBase64' => json_encode($request->imagesBase64)]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);

        $flare = new Flare;
        $flare->title = $request->title;
        $flare->purchasing_price = $request->purchasing_price;
        $flare->reason_selling = $request->reason_selling;
        $flare->sale_price = $request->sale_price;
        $flare->contact = $request->contact;
        $flare->categories = $request->categories;
        $flare->exchange = $request->exchange;
        $flare->member_id = $request->member_id;
        $flare->detail = $request->detail;
        $flare->images = $request->imagesBase64;
        $flare->link = $request->link;

        $parent = MediaFolder::where('slug', 'flare-market-fe')->first();
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
                $preview_item = Flare::findOrFail($request->idPreview);
            } else {
                $preview_item = Flare::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idPreview);
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

        $flare->file_upload = $file_upload;



        Theme::breadcrumb()->add(__('life.flea_market.flea_market_list'), route('flareMarketFE.list'))->add(__('life.flea_market.preview'), 'http:...');


        Theme::setTitle(__('life.flea_market.flea_market_list').' | '.__('life.flea_market.preview'));

        return Theme::scope('life.flare_market.preview', ['flare' => $flare,])->render();
    }

    function deleteFilePreview()
    {
        $parent = MediaFolder::where('slug', 'flare-market-fe')->first();
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

        $sympathy = Flare::findOrFail($id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_flare_market.member_id',$user->id);
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
                    sympathyCommentDetail("flea-market",$id,$reason,"dislike");
                }
            } else {
                $check->detach($user->id);
                sympathyCommentDetail("flea-market",$id,$reason,"dislike");
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
                sympathyCommentDetail("flea-market",$id,$reason,"dislike");
            }
        }
        $sympathy = Flare::withCount([
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
        $sympathy = Flare::findOrFail($id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_flare_market.member_id',$user->id);
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
                    sympathyCommentDetail("flea-market",$id,$reason,"like");
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnPost("flea-market",$id,"like");
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
                sympathyCommentDetail("flea-market",$id,$reason,"like");
            }
        }
        $sympathy = Flare::withCount([
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
        $sympathy = FlareComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_flare_market_comments.member_id', $user->id);
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
                    sympathyCommentDetail("flea-market", $post_id,$reason,"dislike",$comment_id);
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnComment("flea-market",$comment_id,"dislike");
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
                sympathyCommentDetail("flea-market", $post_id,$reason,"dislike",$comment_id);
            }
        }
        $sympathy = FlareComments::withCount([
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
        $sympathy = FlareComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_flare_market_comments.member_id', $user->id);
        $liked = 0;
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 1);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 0,
                    'post_id' => $post_id,
                    'updated_at' => Carbon::now()
                ]);
                $liked = 2;
                if($reason!=""){
                    sympathyCommentDetail("flea-market", $post_id,$reason,"like",$comment_id);
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnComment("flea-market",$comment_id,"like");
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
                sympathyCommentDetail("flea-market", $post_id,$reason,"like",$comment_id);
            }
        }
        $sympathy = FlareComments::withCount([
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

        $flareOwnerID = Flare::find($id)->member_id;
        $currentUserID = auth()->guard('member')->user()->id;
        $allow = 1;

        if($flareOwnerID == $currentUserID){
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
        $commentOwnerID = FlareComments::find($commentId)->member_id;
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
