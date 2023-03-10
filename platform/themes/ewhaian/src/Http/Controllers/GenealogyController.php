<?php

namespace Theme\Ewhaian\Http\Controllers;

use App\Traits\NotificationTrait;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Campus\Models\Description\DescriptionCampus;
use Botble\Campus\Models\Evaluation\Major;
use Botble\Campus\Models\Genealogy\Genealogy;
use Botble\Campus\Models\Genealogy\GenealogyComments;
use Botble\Campus\Models\Notices\NoticesCampus;
use Botble\Campus\Models\OldGenealogy\OldGenealogy;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Theme;

class GenealogyController extends Controller
{
    use NotificationTrait;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (hasPermission('genealogyFE.list')) {
                return $next($request);
            };
            if(!is_null( auth()->guard('member')->user())){
                if(getLevelMember()<3) return redirect()->route('home.index')->with('permission', __('home.no_permisson_leve_3'));
            }
            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        });
    }

    /**
     * @return \Response
     */
    public static function index()
    {

        $genealogy = Genealogy::where('status', 'publish')->ordered()->paginate(10);
        $notices = NoticesIntroduction::code('GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = DescriptionCampus::where('code', 'GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        Theme::breadcrumb()->add(__('campus.genealogy'), route('campus.genealogy_list'))->add("List", 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.genealogy'));

        $canCreate = hasPermission('genealogyFE.create');

        return Theme::scope('campus.genealogy.index', [
            'genealogy' => $genealogy,
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

        $genealogies = Genealogy::where('status', 'publish')->ordered()->paginate(10);

        Theme::breadcrumb()->add(__('campus.genealogy'), route('campus.genealogy_list'))
            ->add(__('campus.genealogy'), 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.genealogy') );

        $description = DescriptionCampus::where('code', 'GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('genealogyFE.create');
        return Theme::scope('campus.genealogy.notice', [
            'genealogy' => $genealogies,
            'notices' => $notices,
            'description' => $description,
            'canCreate' => $canCreate,
            'subList' => [
                'genealogy' => $genealogies,
                'canCreate' => $canCreate,
            ]
        ])->render();


    }

    public function show($id)
    {

        $genealogy = Genealogy::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])
        ->has('dislikes','<',10)
        ->where('id', $id)->where('status', 'publish')->firstOrFail();
        $genealogy->lookup = $genealogy->lookup + 1;
        $genealogy->save();

        $comments = GenealogyComments::where('genealogy_id', $id)->where('parents_id', null)
            ->withCount([
                'dislikes',
            ])->withCount([
                'likes',
            ])->orderBy("created_at","DESC")
            ->paginate(10);

        $top_comments = GenealogyComments::where('genealogy_id', $id)
            ->withCount([
                'dislikes',
            ])->withCount([
                'likes',
            ])
            ->having('likes_count', '>', 0)
            ->orderBy('likes_count', 'DESC')
            ->take(3)->get();

        // Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
        //     ->add(__('campus.genealogy'), route('campus.genealogy_list'))
        //     ->add(titleGenealogy($genealogy, ''), 'http:...');

        Theme::breadcrumb()->add(__('campus.genealogy'), route('campus.genealogy_list'))
            ->add(__('campus.genealogy'), 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.genealogy') . ' | ' . titleGenealogy($genealogy, ''));

        if (hasPermission('memberFE.isAdmin') || $genealogy->member_id == auth()->guard('member')->user()->id) {
            $canEdit = hasPermission('genealogyFE.edit');
            $canDelete = hasPermission('genealogyFE.delete');
        } else {
            $canEdit = false;
            $canDelete = false;
        }

        $canCreateComment = hasPermission('genealogyFE.comment.create');
        $canDeleteComment = hasPermission('genealogyFE.comment.delete');
        $canViewComment = hasPermission('genealogyFE.comment');

        $genealogies = Genealogy::where('status', 'publish')->ordered()->paginate(10);
        $canCreate = hasPermission('genealogyFE.create');

        return Theme::scope('campus.genealogy.details', [
            'genealogy' => $genealogy,
            'comments' => $comments,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => $top_comments,
            'subList' => [
                'genealogy' => $genealogies,
                'canCreate' => $canCreate
            ]
        ])->render();
    }

    public function createComment(Request $request)
    {
        $file = "";
        if (auth()->guard('member')->check()) {
            $anonymous = $request->input('is_secret_comments') ?? 0;
            $genealogy = $request->input('genealogy_id');
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


            $comments = new GenealogyComments;
            $comments->genealogy_id = $genealogy;
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->anonymous = $anonymous;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->ip_address = $ip_address ?? null;
            $comments->save();


            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comments->id,
                'slug' => "comment-genealogy-".$comments->id,
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
            $genealogyInstance = Genealogy::find($genealogy);

            $this->notify($title, $content, [
                $genealogyInstance->member_id
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
            $comments = GenealogyComments::findOrFail($id);
        } else {
            $comments = GenealogyComments::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }


        // foreach ($comments->getAllCommentByParentsID($id) as $item) {
        //     $item->delete();
        // }
        // $comments->delete();

        if($comments->parents_id > 0){
            $parentComment = GenealogyComments::findOrFail($comments->parents_id);
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

            $folder = MediaFolder::where('slug', "comment-genealogy-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }


        return redirect()->back()->with('success', __('controller.deletecomment'));
    }

    public static function getList()
    {

        $genealogy = Genealogy::where('status', 'draft')->orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);

        Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
            ->add(__('campus.genealogy'), route('campus.genealogy_list'))
            ->add(__('campus.genealogy.genealogy_list'), 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.genealogy.genealogy_list'));

        return Theme::scope('campus.genealogy.genealogy-fe-list', ['genealogy' => $genealogy])->render();
    }

    public static function getCreate()
    {
        $genealogy = Genealogy::where('status', 'draft')->orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->first();
        if (is_null($genealogy)) {
            $major = Major::where('parents_id', '0')->where('status', 'publish')->get();
            $description = DescriptionCampus::where('code', 'GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

            Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
                ->add(__('campus.genealogy'), route('campus.genealogy_list'))
                ->add(__('campus.genealogy.create_genealogy'), 'http:...');
            Theme::setTitle(__('campus') . ' | ' . __('campus.genealogy') . ' | ' . __('campus.genealogy.create_genealogy'));

            return Theme::scope('campus.genealogy.genealogy-fe-create', ['genealogy' => null, 'description' => $description, 'major' => $major])->render();
        } else {
            return redirect()->route('genealogyFE.edit', ['id' => $genealogy->id]);
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
        $major = $request->input('major');

        $request->merge(['link' => $link]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);

        $request->validate([
            'semester_year' => 'required',
            'semester_session' => 'required',
            'class_name' => 'required',
            'exam_name' => 'required',
            'professor_name' => 'required',
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);

        if ($request->input('semester_session') == 'other') {
            $request->validate([
                'semester_session_text' => 'required',
            ]);
            $semester_session_text = $request->input('semester_session_text');
            $request->merge(['semester_session' => $semester_session_text]);
        }
        if ($request->input('exam_name') == 'other') {
            $request->validate([
                'exam_name_text' => 'required',
            ]);
            $exam_name_text = $request->input('exam_name_text');
            $request->merge(['exam_name' => $exam_name_text]);
        }
        if ($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $images = $request->input('imagesValue');
        $genealogy = new Genealogy;
        $genealogy = $genealogy->create($request->input());

        if (!is_null($request->images)) {
            $parent = MediaFolder::where('slug', 'genealogy-fe')->first();
            $folder = MediaFolder::create([
                'name' => $genealogy->id,
                'slug' => $genealogy->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);

            foreach ($request->images as $key => $value) {
                if ($images[$key] == 1) {
                    if ($request->hasFile('images.' . $key)) {
                        $image_link = \RvMedia::handleUpload($request->images[$key], $folder->id ?? 0);
                        if ($image_link['error'] == false) {
                            $images[$key] = $image_link['data']->url;
                        } else {
                            return redirect()->back()->with('err', __('controller.save_image_failed'));
                        }
                    }
                }
            }
            $genealogy->images = $images;
        }

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
            $genealogy->file_upload = $file;
        }

        $genealogy->major()->sync($major);

        $genealogy->save();

        addPointForMembers(8);

        $this->deleteFilePreview();

        event(new CreatedContentEvent(GENEALOGY_MODULE_SCREEN_NAME, $request, $genealogy));


        return redirect()->route('campus.genealogy_list')->with('success', __('controller.create_successful', ['module' => __('campus.genealogy')]));
    }

    public static function getEdit($id)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $genealogy = Genealogy::findOrFail($id);
        } else {
            $genealogy = Genealogy::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }


        $description = DescriptionCampus::where('code', 'GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        $major = Major::where('parents_id', '0')->where('status', 'publish')->get();

        Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
            ->add(__('campus.genealogy'), route('campus.genealogy_list'))
            ->add(__('campus.genealogy.edit_genealogy'), 'http:...');
        Theme::setTitle(__('campus') . ' | ' . __('campus.old_genealogy') . ' | ' . __('campus.genealogy.edit_genealogy') . ' #' . $genealogy->id);

        return Theme::scope('campus.genealogy.genealogy-fe-create', ['genealogy' => $genealogy, 'description' => $description, 'major' => $major])->render();
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
        $major = $request->input('major');

        $request->merge(['link' => $link]);
        $request->validate([
            'semester_year' => 'required',
            'semester_session' => 'required',
            'class_name' => 'required',
            'exam_name' => 'required',
            'professor_name' => 'required',
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);

        if ($request->input('semester_session') == 'other') {
            $request->validate([
                'semester_session_text' => 'required',
            ]);
            $semester_session_text = $request->input('semester_session_text');
            $request->merge(['semester_session' => $semester_session_text]);
        }
        if ($request->input('exam_name') == 'other') {
            $request->validate([
                'exam_name_text' => 'required',
            ]);
            $exam_name_text = $request->input('exam_name_text');
            $request->merge(['exam_name' => $exam_name_text]);
        }

        $images = $request->input('imagesValue');
        if (hasPermission('memberFE.isAdmin')) {
            $genealogy = Genealogy::findOrFail($id);
        } else {
            $genealogy = Genealogy::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        if ($genealogy->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $parent = MediaFolder::where('slug', 'genealogy-fe')->first();
        $folder = MediaFolder::where('slug', $genealogy->id)->where('parent_id', $parent->id ?? 0)->first();
        if (is_null($folder)) {
            $folder = MediaFolder::create([
                'name' => $genealogy->id,
                'slug' => $genealogy->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }

        if ($genealogy->images != "") {
            foreach ($genealogy->images as $key => $item) {
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
        //delete old file
        $file_delete = $genealogy->file_upload;
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
        $genealogy->file_upload = $file_upload;

        $request->merge(['images' => $images]);

        $genealogy->major()->sync($major);

        $genealogy = $genealogy->update($request->input());

        $this->deleteFilePreview();
        event(new CreatedContentEvent(GENEALOGY_MODULE_SCREEN_NAME, $request, $genealogy));

        return redirect()->route('campus.genealogy_list')->with('success', __('controller.update_successful', ['module' => __('campus.genealogy')]));
    }

    public function delete(Request $request)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $genealogy = Genealogy::where('id', $request->input('id'))->firstOrFail();
        } else {
            $genealogy = Genealogy::where('id', $request->input('id'))->where('member_id', auth()->guard('member')->user()->id)->firstOrFail();
        }
        $parent = MediaFolder::where('slug', 'genealogy-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id', $parent->id)->first();
        if ($folder) {
            $directory = str_replace(basename(geFirsttImageInArray($genealogy->images, null, 1)), '', geFirsttImageInArray($genealogy->images, null, 1));
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
            $genealogy->delete();

            event(new DeletedContentEvent(GENEALOGY_MODULE_SCREEN_NAME, $request, $genealogy));

            return redirect()->route('campus.genealogy_list')->with('success', __('controller.delete_successful', ['module' => __('campus.genealogy')]));
        } catch (Exception $exception) {
            return redirect()->route('campus.genealogy_list')->with('error', __('controller.delete_failed'));
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
        $request->merge(['imagesBase64' => $request->imagesBase64]);
        $request->validate([
            'semester_year' => 'required',
            'semester_session' => 'required',
            'class_name' => 'required',
            'exam_name' => 'required',
            'professor_name' => 'required',
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        if ($request->input('semester_session') == 'other') {
            $request->validate([
                'semester_session_text' => 'required',
            ]);
            $semester_session_text = $request->input('semester_session_text');
            $request->merge(['semester_session' => $semester_session_text]);
        }
        if ($request->input('exam_name') == 'other') {
            $request->validate([
                'exam_name_text' => 'required',
            ]);
            $exam_name_text = $request->input('exam_name_text');
            $request->merge(['exam_name' => $exam_name_text]);
        }

        $genealogy = new Genealogy;
        $genealogy->title = $request->title;
        $genealogy->detail = $request->detail;
        $genealogy->images = $request->imagesBase64;
        $genealogy->semester_year = $request->semester_year;

        $genealogy->semester_session = $request->semester_session;
        $genealogy->class_name = $request->class_name;
        $genealogy->exam_name = $request->exam_name;
        $genealogy->professor_name = $request->professor_name;
        $genealogy->link = $request->link;

        $major = $request->input('major');

        $majorName = [];

        foreach ($major as $key => $item) {
            array_push($majorName, Major::where('id', $item)->first()->name);
        }


        if (!is_null($request->idGenealogy)) {
            if (hasPermission('memberFE.isAdmin')) {
                $ads_item = Genealogy::findOrFail($request->idGenealogy);
            } else {
                $ads_item = Genealogy::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idGenealogy);
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

            $parent = MediaFolder::where('slug', 'genealogy-fe')->first();
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


        $genealogy->file_upload = $file_upload;

        Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))
            ->add(__('campus.genealogy'), route('campus.genealogy_list'))
            ->add(__('campus.genealogy.preview'), 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.genealogy') . ' | ' . __('campus.genealogy.preview'));

        return Theme::scope('campus.genealogy.preview', ['genealogy' => $genealogy, 'major' => $majorName])->render();
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

    function deleteFilePreview()
    {
        $parent = MediaFolder::where('slug', 'genealogy-fe')->first();
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
        $sympathy = Genealogy::findOrFail($id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_genealogy.member_id', $user->id);
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
                    sympathyCommentDetail("genealogy",$id,$reason,"dislike");
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnPost("genealogy",$id,"dislike");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 1,
                    'reason' => $reason,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            if($reason!=""){
                sympathyCommentDetail("genealogy",$id,$reason,"dislike");
            }
            $dislike = 2;
        }
        $sympathy = Genealogy::withCount([
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

        $id = $request->id;
        $reason = $request->reason;
        $sympathy = Genealogy::findOrFail($id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_genealogy.member_id', $user->id);
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
                    sympathyCommentDetail("genealogy",$id,$reason,"like");
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnPost("genealogy",$id,"like");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 0,
                    'reason' => $reason,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $liked = 1;
            if($reason!=""){
                sympathyCommentDetail("genealogy",$id,$reason,"like");
            }
        }
        $sympathy = Genealogy::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->findOrFail($id);
        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'liked' => $liked,
            ]
        );
    }

    public static function dislikeComments(Request $request)
    {
        $post_id = $request->post_id;
        $reason = $request->reason;
        $comment_id = $request->comment_id;
        $sympathy = GenealogyComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_genealogy_comments.member_id', $user->id);
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
                    sympathyCommentDetail("genealogy", $post_id,$reason,"dislike",$comment_id);
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnComment("genealogy",$comment_id,"dislike");
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
                sympathyCommentDetail("genealogy", $post_id,$reason,"dislike",$comment_id);
            }
        }
        $sympathy = GenealogyComments::withCount([
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
        $sympathy = GenealogyComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_genealogy_comments.member_id', $user->id);
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
                    sympathyCommentDetail("genealogy", $post_id,$reason,"like",$comment_id);
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnComment("genealogy",$comment_id,"like");
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
                sympathyCommentDetail("genealogy", $post_id,$reason,"like",$comment_id);
            }
        }
        $sympathy = GenealogyComments::withCount([
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

        $gardenOwnerID = Genealogy::find($id)->member_id;
        $currentUserID = auth()->guard('member')->user()->id;
        $allow = 1;

        if($gardenOwnerID == $currentUserID){
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
        $commentOwnerID = GenealogyComments::find($commentId)->member_id;
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
