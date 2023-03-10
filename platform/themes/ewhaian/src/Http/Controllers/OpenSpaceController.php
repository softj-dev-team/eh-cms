<?php

namespace Theme\Ewhaian\Http\Controllers;

use App\Traits\NotificationTrait;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Life\Models\Description;
use Botble\Life\Models\Flare;
use Botble\Life\Models\FlareCategories;
use Botble\Life\Models\Notices;
use Botble\Life\Models\OpenSpace\OpenSpace;
use Botble\Life\Models\OpenSpace\OpenSpaceComments;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Botble\Member\Models\Member;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Theme;

class OpenSpaceController extends Controller
{
    use NotificationTrait;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            if (hasPermission('openSpaceFE.list')) {
                return $next($request);
            };
            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        });
    }

    public function index(Request $request) {
        $style = $request->style ?? 0;
        // $openSpace = OpenSpace::withCount(['dislikes'])
        //     ->has('dislikes', '<', 10)
        //     ->where('status', 'publish')->ordered()->paginate(10);

        $openSpace = OpenSpace::where('status', 'publish')->ordered()->paginate(10);

        $notices = NoticesIntroduction::code('OPEN_SPACE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = Description::where('code', 'OPEN_SPACE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        Theme::setTitle(__('life').' | '.__('life.open_space'));
        $canCreate = hasPermission('openSpaceFE.create');
        return Theme::scope('life.open_space.index', [
            'openSpace' => $openSpace,
            'style' => $style,
            'notices' => $notices,
            'description' => $description,
            'canCreate' => $canCreate
        ])->render();
    }
    public static function detailNotice($id)
    {
        $notices = NoticesIntroduction::where('id', $id)->where('status', 'publish')->first();
        $notices->lookup = $notices->lookup + 1;
        $notices->save();

        Theme::breadcrumb()->add(__('life'), route('life.flare_market_list'))->add(__('life.open_space'), route('life.open_space_list'))
        ;

        Theme::setTitle(__('life') . ' | ' . __('life.open_space'));

        $openSpaces = OpenSpace::where('status', 'publish')->ordered()->paginate(10);
        $style = $request->style ?? 0;
        $description = Description::where('code', 'OPEN_SPACE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('openSpaceFE.create');
        return Theme::scope('life.open_space.notice', [
            'openSpace' => $openSpaces,
            'notices' => $notices,
            'canCreate' => $canCreate,
            'subList' => [
                'openSpace' => $openSpaces,
                'style' => $style,
                'notices' => $notices,
                'description' => $description,
                'canCreate' => $canCreate
            ]
        ])->render();


    }


    public function show($id) {
        $openSpace = OpenSpace::withCount(['dislikes'])
            ->has('dislikes', '<', 10)
            ->withCount(['likes'])->where('status', 'publish')->findOrFail($id);

        $openSpace->views = $openSpace->views + 1;
        $openSpace->save();
        $comments = OpenSpaceComments::where('open_space_id', $id)->where('parents_id', null)->where('status', 'publish')
            ->withCount([
                'dislikes',
            ])->withCount([
                'likes',
            ])->paginate(10);

        $top_comments = OpenSpaceComments::where('open_space_id', $id)->where('status', 'publish')
            ->withCount([
                'dislikes',
            ])->withCount([
                'likes',
            ])
            ->having('likes_count', '>', 0)
            ->orderBy('likes_count', 'DESC')
            ->take(3)->get();

        Theme::breadcrumb()->add(__('life'), route('life.flare_market_list'))->add(__('life.open_space'), route('life.open_space_list'))
//            ->add($openSpace->title, 'http:...')
        ;

        Theme::setTitle(__('life') . ' | ' . __('life.open_space') . ' | ' . $openSpace->title);

        if (hasPermission('memberFE.isAdmin') || $openSpace->member_id == auth()->guard('member')->user()->id) {
            $canEdit = hasPermission('openSpaceFE.edit');
            $canDelete = hasPermission('openSpaceFE.delete');
        } else {
            $canEdit = false;
            $canDelete = false;
        }

        $canCreateComment = hasPermission('openSpaceFE.comment.create');
        $canDeleteComment = hasPermission('openSpaceFE.comment.delete');
        $canViewComment = hasPermission('openSpaceFE.comment');

        $openSpaces = OpenSpace::where('status', 'publish')->ordered()->paginate(10);
        $style = $request->style ?? 0;
        $notices = Notices::where('code', 'OPEN_SPACE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $description = Description::where('code', 'OPEN_SPACE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('openSpaceFE.create');

        return Theme::scope('life.open_space.details', [
            'openSpace' => $openSpace,
            'comments' => $comments,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => $top_comments,
            'subList' => [
                'openSpace' => $openSpaces,
                'style' => $style,
                'notices' => $notices,
                'description' => $description,
                'canCreate' => $canCreate
            ]
        ])->render();
    }

    public function createComment(Request $request) {
        $file = "";
        if (auth()->guard('member')->check()) {
            $anonymous = $request->input('is_secret_comments') ?? 0;
            $open_space_id = $request->input('open_space_id');
            $content = $request->input('content');
            $parents_id = $request->input('parents_id');
            if (isset($parents_id)) {
                $title = __('life.open_space.comment_on_comment');
                if ($anonymous == 1) {
                    $ip_address = $request->ip();
                    $type_noti = "secret_garden_comment_on_comment";
                } else {
                    $type_noti = "garden_comment_on_comment";
                }
            } else {
                $title = __('life.open_space.comment_on_post');
                if ($anonymous == 1) {
                    $ip_address = $request->ip();
                    $type_noti = "secret_garden_comment_on_post";
                } else {
                    $type_noti = "garden_comment_on_post";
                }
            }

            $comments = new OpenSpaceComments;
            $comments->open_space_id = $open_space_id;
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->anonymous = $anonymous;
            $comments->ip_address = $ip_address ?? null;
            $comments->save();

            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comments->id,
                'slug' => "comment-life-openspace-".$comments->id,
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

            // notify to owner
            $openSpace = OpenSpace::find($open_space_id);

            $this->notify($title, $content, [
                $openSpace->member_id
            ], $type_noti);

            return redirect()->back();

        } else {
            // return to login
            return redirect()->back();
        }
    }

    public function deleteComment($id) {
        if (hasPermission('memberFE.isAdmin')) {
            $comments = OpenSpaceComments::findOrFail($id);
        } else {
            $comments = OpenSpaceComments::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        // foreach ($comments->getAllCommentByParentsID($id) as $item) {
        //     $item->delete();
        // }
        // $comments->delete();

        if($comments->parents_id > 0){
            $parentComment = OpenSpaceComments::findOrFail($comments->parents_id);
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

            $folder = MediaFolder::where('slug', "comment-life-openspace-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }


        return redirect()->back()->with('success', '댓글을 삭제하셨습니다');
    }

    public function getCreate() {
        $openSpace = OpenSpace::where('status', 'draft')->where('member_id', auth()->guard('member')->user()->id)->where('status', 'draft')->orderby('created_at', 'DESC')->first();
        if (is_null($openSpace)) {

            Theme::breadcrumb()->add(__('life'), route('life.flare_market_list'))
                ->add(__('life.open_space'), route('life.open_space_list'))
                ->add(__('life.open_space.create_open_space'), 'http:...');
            Theme::setTitle(__('life') . ' | ' . __('life.open_space') . ' | ' . __('life.open_space.create_open_space'));

            return Theme::scope('life.open_space.create', ['openSpace' => null])->render();
        } else {
            return redirect()->route('openSpaceFE.edit', ['id' => $openSpace->id]);
        }

    }

    public function postStore(Request $request) {
        $file = [];
        $link = [];
        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }
        $request->merge(['link' => $link]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);

        if($request->status != 'publish'){
            $arrValidate = ['images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
                'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb
            ];
        }else{
            $arrValidate = [
                'title' => 'required|max:120',
                'status' => 'required',
                'detail' => 'required',
                'policy_confirm' => 'required',
                'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
                'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

            ];
        }
        $request->validate($arrValidate);
        if ($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $openSpace = new OpenSpace;
        $openSpace = $openSpace->create($request->input());
        $images = $request->input('imagesValue');

        if (!is_null($request->images)) {
            $parent = MediaFolder::where('slug', 'open-space-fe')->first();
            $folder = MediaFolder::create([
                'name' => $openSpace->id,
                'slug' => $openSpace->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
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
        $openSpace->images = $images;
        //file
        if ($request->hasFile('file')) {
            foreach ($request->file as $key => $item) {

                $file_link = \RvMedia::handleUpload($item, $folder->id ?? 0);
                if ($file_link['error'] == false) {
                    array_push($file, $file_link['data']->url);
                } else {
                    return redirect()->back()->with('err', __('controller.save_file_failed', ['file' => ($key + 1)]));
                }
            }
            $openSpace->file_upload = $file;
        }
        $openSpace->save();
        addPointForMembers();
        $this->deleteFilePreview();

        event(new CreatedContentEvent(OPEN_SPACE_MODULE_SCREEN_NAME, $request, $openSpace));

        return redirect()->route('life.open_space_list')->with('success', __('controller.create_successful', ['module' => __('life.open_space')]));
    }

    public function getList() {
        $openSpace = OpenSpace::where('member_id', auth()->guard('member')->user()->id)->where('status', 'draft')->orderby('created_at', 'DESC')->paginate(10);
        Theme::breadcrumb()->add(__('life.open_space'), route('life.open_space_list'))->add(__('life.open_space.open_space_list'), 'http:...');

        Theme::setTitle(__('life.open_space') . ' | ' . __('life.open_space.open_space_list'));

        return Theme::scope('life.open_space.list', ['openSpace' => $openSpace])->render();

    }

    public function getEdit($id) {
        if (hasPermission('memberFE.isAdmin')) {
            $openSpace = OpenSpace::where('id', $id)->firstOrFail();
        } else {
            $openSpace = OpenSpace::where('member_id', auth()->guard('member')->user()->id)->where('id', $id)->firstOrFail();
        }

        Theme::breadcrumb()->add(__('life'), route('life.flare_market_list'))
            ->add(__('life.open_space'), route('life.open_space_list'))
            ->add(__('life.open_space.edit_open_space'), 'http:...');
//            ->add(__('life.open_space.edit_open_space') . ' #' . $openSpace->id, 'http:...');
        Theme::setTitle(__('life') . ' | ' . __('life.open_space') . ' | ' . __('life.open_space.edit_open_space') . ' #' . $openSpace->id);

        return Theme::scope('life.open_space.create', ['openSpace' => $openSpace])->render();

    }

    public function postUpdate($id, Request $request) {
        $file_upload = [];
        $link = [];
        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }
        $request->merge(['link' => $link]);
        if($request->status != 'publish'){
            $arrValidate = ['images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
                'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb
            ];
        }else{
            $arrValidate = [
                'title' => 'required|max:120',
                'status' => 'required',
                'detail' => 'required',
                'policy_confirm' => 'required',
                'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
                'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

            ];
        }
        $request->validate($arrValidate);

        $images = $request->input('imagesValue');
        if (hasPermission('memberFE.isAdmin')) {
            $openSpace = OpenSpace::findOrFail($id);
        } else {
            $openSpace = OpenSpace::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        if ($openSpace->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $parent = MediaFolder::where('slug', 'open-space-fe')->first();
        $folder = MediaFolder::where('slug', $openSpace->id)->where('parent_id', $parent->id ?? 0)->first();
        if (is_null($folder)) {
            $folder = MediaFolder::create([
                'name' => $openSpace->id,
                'slug' => $openSpace->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }

        if ($openSpace->images != "") {
            foreach ($openSpace->images as $key => $item) {
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
        $request->merge(['images' => $images]);
        //delete old file
        $file_delete = $openSpace->file_upload;
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
                    return redirect()->back()->with('err', __('controller.save_file_failed', ['file' => ($key + 1)]));
                }
            }
        }
        if ($file_delete == null) {
            $file_delete = [];
        }
        $file_upload = array_merge($file_upload, $file_delete);
        $openSpace->file_upload = $file_upload;
        $openSpace = $openSpace->update($request->input());
        $this->deleteFilePreview();

        event(new CreatedContentEvent(OPEN_SPACE_MODULE_SCREEN_NAME, $request, $openSpace));

        return redirect()->route('life.open_space_list')->with('success', __('controller.update_successful', ['module' => __('life.open_space')]));
    }

    public function delete(Request $request) {
        if (hasPermission('memberFE.isAdmin')) {
            $openSpace = OpenSpace::where('status', 'publish')->where('id', $request->input('id'))->firstOrFail();
        } else {
            $openSpace = OpenSpace::where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->where('id', $request->input('id'))->firstOrFail();
        }

        $parent = MediaFolder::where('slug', 'study-room-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id', $parent->id)->first();
        if ($openSpace->comments->count()) {
            return redirect()->back()->with('err', '게시물에 댓글이 작성되면 해당 게시물을 삭제할 수 없습니다');
        }
        if ($folder) {
            $directory = str_replace(basename(geFirsttImageInArray($openSpace->images, null, 1)), '', geFirsttImageInArray($openSpace->images, null, 1));
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
            $openSpace->delete();

            event(new DeletedContentEvent(OPEN_SPACE_MODULE_SCREEN_NAME, $request, $openSpace));

            return redirect()->route('life.open_space_list')->with('success', __('controller.delete_successful', ['module' => __('life.open_space')]));
        } catch (Exception $exception) {
            return redirect()->route('life.open_space_list')->with('error', __('controller.save_failed'));
        }

    }


    public function preview(Request $request) {
        $file_upload = [];
        $link = [];
        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }

        $request->merge(['link' => $link]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        $arrValidate = [
            'title' => 'required|max:120',
            'status' => 'required',
            'detail' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ];

        $request->validate($arrValidate);
        $openSpace = new OpenSpace;
        $openSpace->title = $request->title ?? null;
        $openSpace->images = $request->imagesBase64;
        $openSpace->detail = $request->detail ?? null;
        $openSpace->member_id = $request->member_id;
        $openSpace->link = $request->link;

        $parent = MediaFolder::where('slug', 'open-space-fe')->first();
        $folder = MediaFolder::where('slug', auth()->guard('member')->user()->id_login)->where('parent_id', $parent->id ?? 0)->first();
        if (is_null($folder)) {
            $folder = MediaFolder::create([
                'name' => auth()->guard('member')->user()->id_login,
                'slug' => auth()->guard('member')->user()->id_login,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }
        if (!is_null($request->idPreview)) {
            if (hasPermission('memberFE.isAdmin')) {
                $preview_item = OpenSpace::findOrFail($request->idPreview);
            } else {
                $preview_item = OpenSpace::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idPreview);
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
                    return redirect()->back()->with('err', __('controller.save_file_failed', ['file' => ($key + 1)]));
                }
            }
        }

        $openSpace->file_upload = $file_upload;

        Theme::breadcrumb()->add(__('life'), route('life.flare_market_list'))->add(__('life.open_space'), route('life.open_space_list'))
//            ->add($openSpace->title, 'http:...')
        ;

        Theme::setTitle(__('life') . ' | ' . __('life.open_space') . ' | ' . $openSpace->title);

        return Theme::scope('life.open_space.preview', ['openSpace' => $openSpace])->render();

    }

    function deleteFilePreview() {
        $parent = MediaFolder::where('slug', 'open-space-fe')->first();
        $folder = MediaFolder::where('slug', auth()->guard('member')->user()->id_login)->where('parent_id', $parent->id)->first();

        if (!is_null($folder) && count($folder->files) > 0) {

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

        $sympathy = OpenSpace::findOrFail($id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_open_space.member_id', $user->id);
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
                    sympathyCommentDetail("open-space",$id,$reason,"dislike");
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnPost("garden",$id,"dislike");
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
                sympathyCommentDetail("open-space",$id,$reason,"dislike");
            }
        }
        $sympathy = OpenSpace::withCount([
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

        $sympathy = OpenSpace::findOrFail($id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_open_space.member_id', $user->id);
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
                    sympathyCommentDetail("open-space",$id,$reason,"like");
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnPost("open-space",$id,"like");
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
                sympathyCommentDetail("open-space",$id,$reason,"like");
            }
        }
        $sympathy = OpenSpace::withCount([
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

    public static function dislikeComments(Request $request) {
        $post_id = $request->post_id;
        $reason = $request->reason;
        $comment_id = $request->comment_id;
        $sympathy = OpenSpaceComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_open_space_comments.member_id', $user->id);
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
                    sympathyCommentDetail("open-space", $post_id,$reason,"dislike",$comment_id);
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnComment("open-space",$comment_id,"dislike");
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
                sympathyCommentDetail("open-space", $post_id,$reason,"dislike",$comment_id);
            }
        }
        $sympathy = OpenSpaceComments::withCount([
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

    public static function likeComments(Request $request) {

        $reason = $request->reason;
        $post_id = $request->post_id;
        $comment_id = $request->comment_id;
        $sympathy = OpenSpaceComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_open_space_comments.member_id', $user->id);
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
                    sympathyCommentDetail("open-space", $post_id,$reason,"like",$comment_id);
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnComment("open-space",$comment_id,"like");
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
                sympathyCommentDetail("open-space", $post_id,$reason,"like",$comment_id);
            }
        }
        $sympathy = OpenSpaceComments::withCount([
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

        $flareOwnerID = OpenSpace::find($id)->member_id;
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
        $commentOwnerID = OpenSpaceComments::find($commentId)->member_id;
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
