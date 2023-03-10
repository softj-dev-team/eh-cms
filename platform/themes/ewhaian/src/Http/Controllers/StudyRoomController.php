<?php

namespace Theme\Ewhaian\Http\Controllers;

use App\Traits\NotificationTrait;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Campus\Models\Description\DescriptionCampus;
use Botble\Campus\Models\Notices\NoticesCampus;
use Botble\Campus\Models\StudyRoom\StudyRoom;
use Botble\Campus\Models\StudyRoom\StudyRoomCategories;
use Botble\Campus\Models\StudyRoom\StudyRoomComments;
use Botble\Introduction\Models\CategoriesIntroduction;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Botble\Support\Http\Requests\Request as RequestsRequest;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Theme;

class StudyRoomController extends Controller
{
    use NotificationTrait;
    private $numberTopComment = 3;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (hasPermission('studyRoomFE.list')) {
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
        $studyRoom = StudyRoom::where('status', '!=', 'draft')->ordered()->paginate(9);

        $categories = StudyRoomCategories::where('status', 'publish')->get();

        Theme::setTitle(__('campus') . ' | ' . __('campus.study_room'));

        $notices = NoticesIntroduction::code('STUDY_ROOM_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = DescriptionCampus::where('code', 'STUDY_ROOM_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('studyRoomFE.create');
        return Theme::scope('campus.study_room.index', [
            'studyRoom' => $studyRoom,
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


        $style = $request->style ?? 0;
        $studyRoom = StudyRoom::where('status', '!=', 'draft')->ordered()->paginate(9);

        $categories = StudyRoomCategories::where('status', 'publish')->get();

        Theme::breadcrumb()->add(__('campus.study_room'), route('campus.study_room_list'));
        Theme::setTitle(__('campus') . ' | ' . __('campus.study_room'));

        $description = DescriptionCampus::where('code', 'STUDY_ROOM_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('studyRoomFE.create');
        return Theme::scope('campus.study_room.notice', [
            'studyRoom' => $studyRoom,
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'style' => $style,
            'canCreate' => $canCreate,
            'subList' => [
                'studyRoom' => $studyRoom,
                'categories' => $categories,
                'style' => $style,
                'canCreate' => $canCreate,
            ]
        ])->render();


    }

    public function show($id)
    {

        $studyRoom = StudyRoom::where('status', '!=', 'draft')->findOrFail($id);
        $studyRoom->lookup = $studyRoom->lookup + 1;
        $studyRoom->save();

        $comments = StudyRoomComments::where('study_room_id', $id)->where('parents_id', null)
            ->withCount([
                'dislikes',
            ])->withCount([
                'likes',
            ])
            ->paginate(10);

        $top_comments = StudyRoomComments::where('study_room_id', $id)
            ->withCount([
                'dislikes',
            ])->withCount([
                'likes',
            ])
            ->having('likes_count', '>', 0)
            ->orderBy('likes_count', 'DESC')
            ->take($this->numberTopComment)->get();

        Theme::breadcrumb()->add(__('campus.study_room'), route('campus.study_room_list'))
//            ->add($studyRoom->title, 'http:...')
        ;

        Theme::setTitle(__('campus') . ' | ' . __('campus.study_room') . ' | ' . $studyRoom->title);

        if (hasPermission('memberFE.isAdmin') || $studyRoom->member_id == auth()->guard('member')->user()->id) {
            $canEdit = hasPermission('studyRoomFE.edit');
            $canDelete = hasPermission('studyRoomFE.delete');
        } else {
            $canEdit = false;
            $canDelete = false;
        }

        $canCreateComment = hasPermission('studyRoomFE.comment.create');
        $canDeleteComment = hasPermission('studyRoomFE.comment.delete');
        $canViewComment = hasPermission('studyRoomFE.comment');

        $style = 0;
        $studyRooms = StudyRoom::where('status', '!=', 'draft')->ordered()->paginate(9);
        $categories = StudyRoomCategories::where('status', 'publish')->get();
        $canCreate = hasPermission('studyRoomFE.create');

        return Theme::scope('campus.study_room.details', [
            'studyRoom' => $studyRoom,
            'comments' => $comments,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => $top_comments,
            'subList' => [
                'studyRoom' => $studyRooms,
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
            $anonymous = $request->input('is_secret_comments') ?? 0;
            $study_room_id = $request->input('study_room_id');
            $content = $request->input('content');
            $parents_id = $request->input('parents_id');

            //            if (isset($parents_id)) {
            //                $title = __('life.open_space.comment_on_comment');
            //            } else {
            //                $title = __('life.open_space.comment_on_post');
            //            }
            //
            //            if ($anonymous == 1) {
            //                $ip_address = $request->ip();
            //            }
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

            $comments = new StudyRoomComments;
            $comments->study_room_id = $study_room_id;
            $comments->anonymous = $anonymous;
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->ip_address = $ip_address ?? null;
            $comments->save();

            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comments->id,
                'slug' => "comment-study-room-".$comments->id,
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
            $studyRoom = StudyRoom::find($study_room_id);

            $this->notify($title, $content, [
                $studyRoom->member_id
            ], $type_noti);

            return redirect()->back();
        } else {
            // return to loginß
            return redirect()->back();
        }
    }

    public function deleteComment($id)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $comments = StudyRoomComments::findOrFail($id);
        } else {
            $comments = StudyRoomComments::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }
        // foreach ($comments->getAllCommentByParentsID($id) as $item) {
        //     $item->delete();
        // }
        // $comments->delete();

        if($comments->parents_id > 0){
            $parentComment = StudyRoomComments::findOrFail($comments->parents_id);
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

            $folder = MediaFolder::where('slug', "comment-study-room-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }



        return redirect()->back()->with('success', '댓글을 삭제하셨습니다');
    }

    public static function getList()
    {

        $studyRoom = StudyRoom::orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);

        Theme::breadcrumb()->add(__('campus.study_room'), route('campus.study_room_list'))->add(__('campus.study_room.study_room_list'), 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.study_room.study_room_list'));

        return Theme::scope('campus.study_room.study-room-fe-list', ['studyRoom' => $studyRoom])->render();
    }

    public static function getCreate()
    {
        $studyRoom = StudyRoom::where('status', 'draft')->where('member_id', auth()->guard('member')->user()->id)->first();
        if (is_null($studyRoom)) {

            Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
                ->add(__('campus.study_room'), route('campus.study_room_list'))
                ->add(__('campus.study_room.create_study_room'), 'http:...');
            Theme::setTitle(__('campus') . ' | ' . __('campus.study_room') . ' | ' . __('campus.study_room.create_study_room'));

            $categories = StudyRoomCategories::where('status', 'publish')->get();

            $description = DescriptionCampus::where('code', 'STUDY_ROOM_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

            return Theme::scope('campus.study_room.study-room-fe-create', ['studyRoom' => null, 'categories' => $categories, 'description' => $description])->render();
        } else {
            return redirect()->route('studyRoomFE.edit', ['id' => $studyRoom->id]);
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
            'contact' => 'required',
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb

        ]);
        if ($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['categories' => $request->input('categories.2')]);
        $images = $request->input('imagesValue');

        $studyRoom = new StudyRoom;
        $studyRoom = $studyRoom->create($request->input());
        $parent = MediaFolder::where('slug', 'study-room-fe')->first();
        $folder = MediaFolder::create([
            'name' => $studyRoom->id,
            'slug' => $studyRoom->id,
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
        $studyRoom->images = json_encode($images);

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
            $studyRoom->file_upload = $file;
        }
        $studyRoom->save();

        addPointForMembers();
        $this->deleteFilePreview();

        event(new CreatedContentEvent(STUDY_ROOM_MODULE_SCREEN_NAME, $request, $studyRoom));

        if($request->status != 'draft') {
            $keyMsg = 'controller.create_successful';
        }else{
            $keyMsg = 'controller.create_draft';
        }

        return redirect()->route('campus.study_room_list')->with('success', __($keyMsg, ['module' => __('campus.study_room')]));
    }

    public static function getEdit($id)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $studyRoom = StudyRoom::findOrFail($id);
        } else {
            $studyRoom = StudyRoom::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }
        $categories = StudyRoomCategories::all();
        $selectedCategories = StudyRoomCategories::where('id', $studyRoom->categories)->where('status', 'publish')->first();
        $description = DescriptionCampus::where('code', 'STUDY_ROOM_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
            ->add(__('campus.study_room'), route('campus.study_room_list'))
            ->add(__('campus.study_room.edit_study_room'), 'http:...');
        Theme::setTitle(__('campus') . ' | ' . __('campus.study_room') . ' | ' . __('campus.study_room.edit_study_room') . ' #' . $studyRoom->id);

        return Theme::scope('campus.study_room.study-room-fe-create', ['studyRoom' => $studyRoom, 'categories' => $categories, 'selectedCategories' => $selectedCategories, 'description' => $description])->render();
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
            'contact' => 'required',
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
        ]);

        $images = $request->input('imagesValue');
        if (hasPermission('memberFE.isAdmin')) {
            $studyRoom = StudyRoom::findOrFail($id);
        } else {
            $studyRoom = StudyRoom::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        if ($studyRoom->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $parent = MediaFolder::where('slug', 'study-room-fe')->first();
        $folder = MediaFolder::where('slug', $studyRoom->id)->where('parent_id', $parent->id ?? 0)->first();
        if (is_null($folder)) {
            $folder = MediaFolder::create([
                'name' => $studyRoom->id,
                'slug' => $studyRoom->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }
        if ($studyRoom->images != "") {
            foreach ($studyRoom->images as $key => $item) {
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
        $file_delete = $studyRoom->file_upload;
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
        $studyRoom->file_upload = $file_upload;

        $studyRoom = $studyRoom->update($request->input());
        $this->deleteFilePreview();
        event(new CreatedContentEvent(STUDY_ROOM_MODULE_SCREEN_NAME, $request, $studyRoom));

        if($request->status != 'draft') {
            $keyMsg = 'controller.update_successful';
        }else{
            $keyMsg = 'controller.create_draft';
        }

        return redirect()->route('campus.study_room_list')->with('success', __($keyMsg, ['module' => __('campus.study_room')]));
    }

    public function delete(Request $request)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $studyRoom = StudyRoom::where('id', $request->input('id'))->firstOrFail();
        } else {
            $studyRoom = StudyRoom::where('id', $request->input('id'))->where('member_id', auth()->guard('member')->user()->id)->firstOrFail();
        }
        $parent = MediaFolder::where('slug', 'study-room-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id', $parent->id)->first();
        if ($folder) {
            $directory = str_replace(basename(geFirsttImageInArray($studyRoom->images, null, 1)), '', geFirsttImageInArray($studyRoom->images, null, 1));
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
            $studyRoom->delete();

            event(new DeletedContentEvent(STUDY_ROOM_MODULE_SCREEN_NAME, $request, $studyRoom));

            return redirect()->route('campus.study_room_list')->with('success', __('controller.delete_successful', ['module' => __('campus.study_room')]));
        } catch (Exception $exception) {
            return redirect()->route('campus.study_room_list')->with('error', __('controller.delete_failed'));
        }
    }

    public function preview(Request $request)
    {
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
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        Theme::breadcrumb()->add(__('campus.study_room.study_room_list'), route('studyRoomFE.list'))->add(__('campus.study_room.preview'), 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.study_room.preview'));

        $studyRoom = new StudyRoom;
        $studyRoom->title = $request->title;
        $studyRoom->contact = $request->contact;
        $studyRoom->detail = $request->detail;
        $studyRoom->status = $request->status;
        $studyRoom->images = $request->imagesBase64;
        $studyRoom->categories = $request->categories;
        $studyRoom->link = $request->link;

        $parent = MediaFolder::where('slug', 'study-room-fe')->first();
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
                $ads_item = StudyRoom::findOrFail($request->idPreview);
            } else {
                $ads_item = StudyRoom::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idPreview);
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

        $studyRoom->file_upload = $file_upload;

        return Theme::scope('campus.study_room.preview', ['studyRoom' => $studyRoom])->render();
    }

    function deleteFilePreview()
    {
        $parent = MediaFolder::where('slug', 'study-room-fe')->first();
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

    public static function dislikeComments(Request $request)
    {
        $post_id = $request->post_id;
        $reason = $request->reason;
        $comment_id = $request->comment_id;
        $sympathy = StudyRoomComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_study_room_comments.member_id', $user->id);
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
            } else {
                $check->detach($user->id);
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
        }
        $sympathy = StudyRoomComments::withCount([
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

        $post_id = $request->post_id;
        $comment_id = $request->comment_id;
        $sympathy = StudyRoomComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_study_room_comments.member_id', $user->id);
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
            } else {

                $check->detach($user->id);
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
        }
        $sympathy = StudyRoomComments::withCount([
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

    public static function checkSympathyPermissionOnComment(Request $request) {
        $commentId = $request->comment_id;
        $commentOwnerID = StudyRoomComments::find($commentId)->member_id;
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
