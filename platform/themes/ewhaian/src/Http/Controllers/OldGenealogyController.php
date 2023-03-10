<?php

namespace Theme\Ewhaian\Http\Controllers;

use App\Traits\NotificationTrait;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Campus\Models\Description\DescriptionCampus;
use Botble\Campus\Models\Genealogy\Genealogy;
use Botble\Campus\Models\Notices\NoticesCampus;
use Botble\Campus\Models\OldGenealogy\OldGenealogy;
use Botble\Campus\Models\OldGenealogy\OldGenealogyComments;
use Botble\Campus\Models\StudyRoom\StudyRoom;
use Botble\Campus\Models\StudyRoom\StudyRoomCategories;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Theme;

class OldGenealogyController extends Controller
{
    use NotificationTrait;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (hasPermission('oldGenealogyFE.list')) {
                return $next($request);
            };
            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        });
    }

    /**
     * @return \Response
     */
    public static function index()
    {
        $oldGenealogy = OldGenealogy::where('status', 'publish')->ordered()->paginate(10);
        $notices = NoticesIntroduction::code(OLD_GENEALOGY_MODULE_SCREEN_NAME)->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = DescriptionCampus::where('code', 'OLD_GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        Theme::setTitle(__('campus') . ' | ' . __('campus.old_genealogy.genealogy_list'));
        $canCreate = hasPermission('oldGenealogyFE.create');
        return Theme::scope('campus.old-genealogy.index', [
            'oldGenealogy' => $oldGenealogy,
            'notices' => $notices,
            'description' => $description,
            'canCreate' => $canCreate,
        ])->render();
    }

    public static function detailNotice($id)
    {
        $notices = NoticesIntroduction::where('id', $id)->where('status', 'publish')->first();
        $notices->lookup = $notices->lookup + 1;
        $notices->save();


        $style = $request->style ?? 0;
        $oldGenealogies = OldGenealogy::where('status', 'publish')->ordered()->paginate(10);

        Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
            ->add(__('campus.old_genealogy'), route('campus.old.genealogy'));

        Theme::setTitle(__('campus') . ' | ' . __('campus.old_genealogy'));

        $description = DescriptionCampus::where('code', 'OLD_GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('oldGenealogyFE.create');
        return Theme::scope('campus.old-genealogy.notice', [
            'oldGenealogy' => $oldGenealogies,
            'notices' => $notices,
            'description' => $description,
            'canCreate' => $canCreate,
            'subList' => [
                'oldGenealogy' => $oldGenealogies,
                'canCreate' => $canCreate,
            ]
        ])->render();


    }

    public function show($id)
    {

        $oldGenealogy = OldGenealogy::where('id', $id)->where('status', 'publish')->firstOrFail();
        $oldGenealogy->lookup = $oldGenealogy->lookup + 1;
        $oldGenealogy->save();

        $comments = OldGenealogyComments::where('old_genealogy_id', $id)->where('parents_id', null)
            ->withCount([
                'dislikes',
            ])->withCount([
                'likes',
            ])
            ->paginate(10);

        $top_comments = OldGenealogyComments::where('old_genealogy_id', $id)
            ->withCount([
                'dislikes',
            ])->withCount([
                'likes',
            ])
            ->having('likes_count', '>', 0)
            ->orderBy('likes_count', 'DESC')
            ->take(3)->get();

        Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
            ->add(__('campus.old_genealogy'), route('campus.old.genealogy'))
//            ->add($oldGenealogy->title, 'http:...')
        ;

        Theme::setTitle(__('campus') . ' | ' . __('campus.old_genealogy') . ' | ' . $oldGenealogy->title);

        if (hasPermission('memberFE.isAdmin') || $oldGenealogy->member_id == auth()->guard('member')->user()->id) {
            $canEdit = hasPermission('oldGenealogyFE.edit');
            $canDelete = hasPermission('oldGenealogyFE.delete');
        } else {
            $canEdit = false;
            $canDelete = false;
        }

        $canCreateComment = hasPermission('oldGenealogyFE.comment.create');
        $canDeleteComment = hasPermission('oldGenealogyFE.comment.delete');
        $canViewComment = hasPermission('oldGenealogyFE.comment');

        $oldGenealogies = OldGenealogy::where('status', 'publish')->ordered()->paginate(10);
        $canCreate = hasPermission('oldGenealogyFE.create');

        return Theme::scope('campus.old-genealogy.details', [
            'oldGenealogy' => $oldGenealogy,
            'comments' => $comments,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => $top_comments,
            'subList' => [
                'oldGenealogy' => $oldGenealogies,
                'canCreate' => $canCreate,
            ]
        ])->render();
    }

    public function createComment(Request $request)
    {
        $file = "";
        if (auth()->guard('member')->check()) {
            $request->merge(['member_id' => auth()->guard('member')->user()->id, 'anonymous' => $request->is_secret_comments ?? 0]);

            if ($request->is_secret_comments == 1) {
                $ip_address = $request->ip();
            }

            $parents_id = $request->parents_id;

            //            if (isset($parents_id)) {
            //                $title = __('life.open_space.comment_on_comment');
            //            } else {
            //                $title = __('life.open_space.comment_on_post');
            //            }

            if (isset($parents_id)) {
                $title = __('life.open_space.comment_on_comment');
                $type_noti = "bulletin_comment_on_comment";
            } else {
                $title = __('life.open_space.comment_on_post');
                $type_noti = "bulletin_comment_on_post";
            }


            $request->merge(['ip_address' => $ip_address ?? null]);

            $commentObj = new OldGenealogyComments;

            $comments = $commentObj->create($request->input());


            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comments->id,
                'slug' => "comment-old-genealogy-".$comments->id,
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
            $oldGenealogyInstance = OldGenealogy::find($request->old_genealogy_id);

            $this->notify($title, $request->input('content'), [
                $oldGenealogyInstance->member_id
            ], $type_noti);

            return redirect()->back();
        } else {
            // return to login
            return redirect()->back();
        }
    }

    public function deleteComment($id)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $comments = OldGenealogyComments::findOrFail($id);
        } else {
            $comments = OldGenealogyComments::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }


        // foreach ($comments->getAllCommentByParentsID($id) as $item) {
        //     $item->delete();
        // }
        // $comments->delete();

        if($comments->parents_id > 0){
            $parentComment = OldGenealogyComments::findOrFail($comments->parents_id);
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

            $folder = MediaFolder::where('slug', "comment-old-genealogy-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }



        return redirect()->back()->with('success', __('controller.deletecomment'));
    }

    public static function getList()
    {

        $oldGenealogy = OldGenealogy::where('status', 'draft')->orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);

        Theme::breadcrumb()->add(__('campus.old_genealogy'), route('campus.old.genealogy'))->add(__('campus.old_genealogy.genealogy_list'), 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.old_genealogy.genealogy_list'));

        return Theme::scope('campus.old-genealogy.genealogy-fe-list', ['oldGenealogy' => $oldGenealogy])->render();
    }

    public static function getCreate()
    {
        $oldGenealogy = OldGenealogy::where('status', 'draft')->orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->first();
        if (is_null($oldGenealogy)) {
            $description = DescriptionCampus::where('code', 'OLD_GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

            Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
                ->add(__('campus.old_genealogy'), route('campus.old.genealogy'))
                ->add(__('campus.old_genealogy.create_old_genealogy'), 'http:...');
            Theme::setTitle(__('campus') . ' | ' . __('campus.old_genealogy') . ' | ' . __('campus.old_genealogy.create_old_genealogy'));

            return Theme::scope('campus.old-genealogy.genealogy-fe-create', ['oldGenealogy' => null, 'description' => $description])->render();
        } else {
            return redirect()->route('oldGenealogyFE.edit', ['id' => $oldGenealogy->id]);
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
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb

        ]);
        if ($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $images = $request->input('imagesValue');
        $oldGenealogy = new OldGenealogy;
        $oldGenealogy = $oldGenealogy->create($request->input());

        $parent = MediaFolder::where('slug', 'old-genealogy-fe')->first();
        $folder = MediaFolder::create([
            'name' => $oldGenealogy->id,
            'slug' => $oldGenealogy->id,
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
                        } else {
                            return redirect()->back()->with('err', __('controller.save_failed'));
                        }
                    }
                }
            }
        }
        $oldGenealogy->images = json_encode($images);
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
            $oldGenealogy->file_upload = $file;
        }

        $oldGenealogy->save();

        addPointForMembers(8);
        $this->deleteFilePreview();

        event(new CreatedContentEvent(OLD_GENEALOGY_MODULE_SCREEN_NAME, $request, $oldGenealogy));

        return redirect()->route('campus.old.genealogy')->with('success', __('controller.create_successful', ['module' => __('campus.old_genealogy')]));
    }

    public static function getEdit($id)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $oldGenealogy = OldGenealogy::findOrFail($id);
        } else {
            $oldGenealogy = OldGenealogy::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        $description = DescriptionCampus::where('code', 'OLD_GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
            ->add(__('campus.old_genealogy'), route('campus.old.genealogy'))
            ->add(__('campus.old_genealogy.edit_old_data_genealogy'), 'http:...');
        Theme::setTitle(__('campus') . ' | ' . __('campus.old_genealogy') . ' | ' . __('campus.old_genealogy.edit_old_data_genealogy') . ' #' . $id);

        return Theme::scope('campus.old-genealogy.genealogy-fe-create', ['oldGenealogy' => $oldGenealogy, 'description' => $description])->render();
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
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb

        ]);
        $images = $request->input('imagesValue');
        if (hasPermission('memberFE.isAdmin')) {
            $oldGenealogy = OldGenealogy::findOrFail($id);
        } else {
            $oldGenealogy = OldGenealogy::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        if ($oldGenealogy->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }

        $parent = MediaFolder::where('slug', 'old-genealogy-fe')->first();
        $folder = MediaFolder::where('slug', $oldGenealogy->id)->where('parent_id', $parent->id ?? 0)->first();
        if (is_null($folder)) {
            $folder = MediaFolder::create([
                'name' => $oldGenealogy->id,
                'slug' => $oldGenealogy->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }
        if ($oldGenealogy->images != "") {
            foreach ($oldGenealogy->images as $key => $item) {
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
        $file_delete = $oldGenealogy->file_upload;
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
        $oldGenealogy->file_upload = $file_upload;

        $oldGenealogy = $oldGenealogy->update($request->input());
        $this->deleteFilePreview();
        event(new CreatedContentEvent(OLD_GENEALOGY_MODULE_SCREEN_NAME, $request, $oldGenealogy));

        return redirect()->route('campus.old.genealogy')->with('success', __('controller.update_successful', ['module' => __('campus.old_genealogy')]));
    }

    public function delete(Request $request)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $oldGenealogy = OldGenealogy::where('id', $request->input('id'))->firstOrFail();
        } else {
            $oldGenealogy = OldGenealogy::where('id', $request->input('id'))->where('member_id', auth()->guard('member')->user()->id)->firstOrFail();
        }


        $parent = MediaFolder::where('slug', 'old-genealogy-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id', $parent->id)->first();
        if ($folder) {
            $directory = str_replace(basename(geFirsttImageInArray($oldGenealogy->images, null, 1)), '', geFirsttImageInArray($oldGenealogy->images, null, 1));
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
            $oldGenealogy->delete();

            event(new DeletedContentEvent(OLD_GENEALOGY_MODULE_SCREEN_NAME, $request, $oldGenealogy));

            return redirect()->route('campus.old.genealogy')->with('success', __('controller.delete_successful', ['module' => __('campus.old_genealogy')]));
        } catch (Exception $exception) {
            return redirect()->route('campus.old.genealogy')->with('error', __('controller.delete_failed'));
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
        $request->merge(['link' => $link]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        $request->merge(['imagesBase64' => json_encode($request->imagesBase64)]);
        $request->validate([
            'title' => 'required|max:120',
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);

        $oldGenealogy = new OldGenealogy;
        $oldGenealogy->title = $request->title;
        $oldGenealogy->detail = $request->detail;
        $oldGenealogy->images = $request->imagesBase64;
        $oldGenealogy->categories = $request->categories;
        $oldGenealogy->link = $request->link;


        if (!is_null($request->idPreview)) {
            if (hasPermission('memberFE.isAdmin')) {
                $ads_item = OldGenealogy::findOrFail($request->idPreview);
            } else {
                $ads_item = OldGenealogy::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idPreview);
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

            $parent = MediaFolder::where('slug', 'old-genealogy-fe')->first();
            $folder = MediaFolder::where('slug', auth()->guard('member')->user()->id_login)->where('parent_id', $parent->id)->first();
            if (is_null($folder)) {
                $folder = MediaFolder::create([
                    'name' => auth()->guard('member')->user()->id_login,
                    'slug' => auth()->guard('member')->user()->id_login,
                    'user_id' => '0',
                    'parent_id' => $parent->id ?? 0,
                ]);
            }
            //xóa file cũ
            if (!is_null($folder) && count($folder->files) > 0) {

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
                    return redirect()->back()->with('err', __('controller.save_file_failed', ['file' => ($key + 1)]));
                }
            }
            $file_upload = array_merge($file, $file_upload);
        }


        $oldGenealogy->file_upload = $file_upload;

        Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
            ->add(__('campus.old_genealogy'), route('campus.old.genealogy'))
            ->add(__('campus.old_genealogy.preview'), 'http:...');
        Theme::setTitle(__('campus') . ' | ' . __('campus.old_genealogy') . ' | ' . __('campus.old_genealogy.preview'));

        return Theme::scope('campus.old-genealogy.preview', ['oldGenealogy' => $oldGenealogy])->render();
    }

    function deleteFilePreview()
    {
        $parent = MediaFolder::where('slug', 'old-genealogy-fe')->first();
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
        $sympathy = OldGenealogyComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_old_genealogy_comments.member_id', $user->id);
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
                    sympathyCommentDetail("old-genealogy", $post_id,$reason,"dislike",$comment_id);
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnComment("old-genealogy",$comment_id,"dislike");
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
                sympathyCommentDetail("old-genealogy", $post_id,$reason,"dislike",$comment_id);
            }
        }
        $sympathy = OldGenealogyComments::withCount([
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
        $sympathy = OldGenealogyComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_old_genealogy_comments.member_id', $user->id);
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
                    sympathyCommentDetail("old-genealogy", $post_id,$reason,"like",$comment_id);
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnComment("old-genealogy",$comment_id,"like");
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
                sympathyCommentDetail("old-genealogy", $post_id,$reason,"like",$comment_id);
            }
        }
        $sympathy = OldGenealogyComments::withCount([
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
        $commentOwnerID = OldGenealogyComments::find($commentId)->member_id;
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
