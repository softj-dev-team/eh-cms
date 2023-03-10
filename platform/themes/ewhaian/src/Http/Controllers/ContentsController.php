<?php

namespace Theme\Ewhaian\Http\Controllers;

use App\Traits\NotificationTrait;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Contents\Models\CategoriesContents;
use Botble\Contents\Models\CommentsContents;
use Botble\Contents\Models\Contents;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Life\Models\Flare;
use Botble\Life\Models\FlareCategories;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Theme;

class ContentsController extends Controller
{
    use NotificationTrait;

    /**
     * @return \Response
     */
    public static function index($idCategory, Request $request)
    {
        $contents = Contents::where('status', '!=', 'draft')->where('categories_contents_id', $idCategory)->ordered()->paginate(10);
        $selectCategories = CategoriesContents::findOrFail($idCategory);
        $categories = CategoriesContents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $notices = NoticesIntroduction::code(CONTENTS_MODULE_SCREEN_NAME . '-' . $idCategory)->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();

        Theme::breadcrumb()->add(__('contents'), route('contents.contents_list', ['idCategory' => $categories->first->id]))->add($selectCategories->name, 'http:...');
        Theme::setTitle(__('contents') . ' | ' . $selectCategories->name);

        $style = $request->style ?? 1;

        switch ($selectCategories->permisions) {
            case CategoriesContents::MULTICULTURE:
                $canCreate = 'contentsFE.create.multiculture';
                break;
            case CategoriesContents::CULTURAL_SYMPATHY:
                $canCreate = 'contentsFE.create.cultural_sympathy';
                break;
            case CategoriesContents::FINE_NOTEBOOK:
                $canCreate = 'contentsFE.create.fine_notebook';
                break;
            case CategoriesContents::WRITTEN_NOTE:
                $canCreate = 'contentsFE.create.written_note';
                break;
            case CategoriesContents::CONTRIBUTION:
                $canCreate = 'contentsFE.create.contribution';
                break;
            default:
                $canCreate = 'contentsFE.create.multiculture';
                break;
        }
        $canCreate = hasPermission($canCreate);
        return Theme::scope('contents.index', [
            'contents' => $contents,
            'categories' => $categories,
            'idCategory' => $idCategory,
            'notices' => $notices,
            'selectCategories' => $selectCategories,
            'style' => $style,
            'canCreate' => $canCreate

        ])->render();
    }

    public function detailNotice($idCategory, $id)
    {
        $selectedCategory = CategoriesContents::findOrFail($idCategory);
        $notices = NoticesIntroduction::where('id', $id)->where('status', 'publish')->first();
        $notices->lookup = $notices->lookup + 1;
        $notices->save();

        $category = CategoriesContents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        Theme::breadcrumb()->add(__('contents'), route('contents.contents_list', ['idCategory' => $category->first->id]))->add($selectedCategory->name, route('contents.contents_list', ['idCategory' => $idCategory]));
        Theme::setTitle(__('contents') . ' | ' . $selectedCategory->name );

        $subContents = Contents::where('status', '!=', 'draft')->where('categories_contents_id', $idCategory)->ordered()->paginate(10);
        $selectCategories = CategoriesContents::findOrFail($idCategory);
        $style = null;

        switch ($selectCategories->permisions) {
            case CategoriesContents::MULTICULTURE:
                $canCreate = 'contentsFE.create.multiculture';
                break;
            case CategoriesContents::CULTURAL_SYMPATHY:
                $canCreate = 'contentsFE.create.cultural_sympathy';
                break;
            case CategoriesContents::FINE_NOTEBOOK:
                $canCreate = 'contentsFE.create.fine_notebook';
                break;
            case CategoriesContents::WRITTEN_NOTE:
                $canCreate = 'contentsFE.create.written_note';
                break;
            case CategoriesContents::CONTRIBUTION:
                $canCreate = 'contentsFE.create.contribution';
                break;
            default:
                $canCreate = 'contentsFE.create.multiculture';
                break;
        }
        $canCreate = hasPermission($canCreate);

        return Theme::scope('contents.notice', [
            'contents' => $subContents,
            'category' => $category,
            'idCategory' => $idCategory,
            'notices' => $notices,
            'canCreate' => $canCreate,
            'subList' => [
                'contents' => $subContents,
                'categories' => $category,
                'idCategory' => $idCategory,
                'selectCategories' => $selectCategories,
                'style' => $style,
                'canCreate' => $canCreate
            ]
        ])->render();


    }

    public function show($idCategory, $id)
    {
        $selectedCategory = CategoriesContents::findOrFail($idCategory);
        $contents = Contents::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->where('id', $id)->where('status', '!=', 'draft')->firstOrFail();

        //dd($contents->dd());

        $contents->lookup = $contents->lookup + 1;
        $contents->save();

        switch ($selectedCategory->permisions) {
            case CategoriesContents::MULTICULTURE:
                $permissionEdit = 'contentsFE.edit.multiculture';
                $permissionDelete = 'contentsFE.delete.multiculture';
                $canCreateComment = 'contentsFE.create.comment.multiculture';
                $canDeleteComment = 'contentsFE.delete.comment.multiculture';
                break;
            case CategoriesContents::CULTURAL_SYMPATHY:
                $permissionEdit = 'contentsFE.edit.cultural_sympathy';
                $permissionDelete = 'contentsFE.delete.cultural_sympathy';
                $canCreateComment = 'contentsFE.create.comment.cultural_sympathy';
                $canDeleteComment = 'contentsFE.delete.comment.cultural_sympathy';
                break;
            case CategoriesContents::FINE_NOTEBOOK:
                $permissionEdit = 'contentsFE.edit.fine_notebook';
                $permissionDelete = 'contentsFE.delete.fine_notebook';
                $canCreateComment = 'contentsFE.create.comment.fine_notebook';
                $canDeleteComment = 'contentsFE.delete.comment.fine_notebook';
                break;
            case CategoriesContents::WRITTEN_NOTE:
                $permissionEdit = 'contentsFE.edit.written_note';
                $permissionDelete = 'contentsFE.delete.written_note';
                $canCreateComment = 'contentsFE.create.comment.written_note';
                $canDeleteComment = 'contentsFE.delete.comment.written_note';
                break;
            case CategoriesContents::CONTRIBUTION:
                $permissionEdit = 'contentsFE.edit.contribution';
                $permissionDelete = 'contentsFE.delete.contribution';
                $canCreateComment = 'contentsFE.create.comment.contribution';
                $canDeleteComment = 'contentsFE.delete.comment.contribution';
                break;
            default:
                $permissionEdit = 'contentsFE.edit.event';
                $permissionDelete = 'contentsFE.delete.event';
                $canCreateComment = 'contentsFE.create.comment.event';
                $canDeleteComment = 'contentsFE.delete.comment.event';
                break;
        }

        if (hasPermission('memberFE.isAdmin') || isAuthor($contents)) {
            $canEdit = hasPermission($permissionEdit);
            $canDelete = hasPermission($permissionDelete);
        } else {
            $canEdit = false;
            $canDelete = false;
        }
        $canDeleteComment = hasPermission($canDeleteComment);
        $canCreateComment = hasPermission($canCreateComment);
        $canViewComment = hasPermission('contentsFE.comment');
        $canViewCommenter = hasPermission('contentsFE.show.commenter.comment');
        $comments = CommentsContents::where('contents_id', $id)->where('parents_id', null)->where('status', 'publish')
            ->withCount([
                'dislikes',
            ])
            ->withCount([
                'likes',
            ])->orderBy('created_at', 'DESC')->paginate(10);
        $top_comments = CommentsContents::where('contents_id', $id)->where('status', 'publish')
            ->withCount([
                'dislikes',
            ])
            ->withCount([
                'likes',
            ])->having('likes_count', '>', 0)
            ->orderBy('likes_count', 'DESC')
            ->take(3)->get();
        $category = CategoriesContents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        Theme::breadcrumb()->add(__('contents'), route('contents.contents_list', ['idCategory' => $category->first->id]))->add($selectedCategory->name, route('contents.contents_list', ['idCategory' => $idCategory]));
        Theme::setTitle(__('contents') . ' | ' . $selectedCategory->name . ' | ' . $contents->title);

        $subContents = Contents::where('status', '!=', 'draft')->where('categories_contents_id', $idCategory)->ordered()->paginate(10);
        $selectCategories = CategoriesContents::findOrFail($idCategory);
        $categories = CategoriesContents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $style = null;
        switch ($selectCategories->permisions) {
            case CategoriesContents::MULTICULTURE:
                $canCreate = 'contentsFE.create.multiculture';
                break;
            case CategoriesContents::CULTURAL_SYMPATHY:
                $canCreate = 'contentsFE.create.cultural_sympathy';
                break;
            case CategoriesContents::FINE_NOTEBOOK:
                $canCreate = 'contentsFE.create.fine_notebook';
                break;
            case CategoriesContents::WRITTEN_NOTE:
                $canCreate = 'contentsFE.create.written_note';
                break;
            case CategoriesContents::CONTRIBUTION:
                $canCreate = 'contentsFE.create.contribution';
                break;
            default:
                $canCreate = 'contentsFE.create.multiculture';
                break;
        }
        $canCreate = hasPermission($canCreate);

        return Theme::scope('contents.details', [
            'contents' => $contents,
            'comments' => $comments,
            'category' => $category,
            'idCategory' => $idCategory,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'canViewCommenter' => $canViewCommenter,
            'top_comments' => $top_comments,
            'subList' => [
                'contents' => $subContents,
                'categories' => $categories,
                'idCategory' => $idCategory,
                'selectCategories' => $selectCategories,
                'style' => $style,
                'canCreate' => $canCreate
            ]
        ])->render();
    }

    public function createComment(Request $request)
    {
        if (auth()->guard('member')->check()) {
            $anonymous = $request->input('is_secret_comments') ?? 0;
            $contents_id = $request->input('contents_id');
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


            //            if (isset($parents_id)) {
            //                $title = __('life.open_space.comment_on_comment');
            //            } else {
            //                $title = __('life.open_space.comment_on_post');
            //            }
            //
            //            if ($anonymous == 1) {
            //                $ip_address = $request->ip();
            //            }

            $comments = new CommentsContents;
            $comments->contents_id = $contents_id;
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->anonymous = $anonymous;
            $comments->ip_address = $ip_address ?? null;
            $comments->save();

            addPointForMembers(1);

            // notify to owner
            $contentsInstance = Contents::find($contents_id);

            $this->notify($title, $content, [
                $contentsInstance->member_id
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
            $comments = CommentsContents::findOrFail($id);
        } else {
            $comments = CommentsContents::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }
        // foreach ($comments->getAllCommentByParentsID($id) as $item) {
        //     $item->delete();
        // }
        // $comments->delete();

        if($comments->parents_id > 0){
            $parentComment = CommentsContents::findOrFail($comments->parents_id);
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

        return redirect()->back()->with('success', __('controller.deletecomment'));
    }

    public static function getList()
    {
        //getDataByCurrentLanguageCode

        $contents = Contents::where('status', 'draft')->orderBy('start', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);
        $category = CategoriesContents::where('status', 'publish')->orderBy('created_at', 'DESC')->firstOrFail();

        Theme::breadcrumb()->add(__('contents'), route('contents.contents_list', ['idCategory' => $category->id]))->add(__('contents.contents_list'), 'http:...');

        Theme::setTitle(__('contents.contents_list'));

        return Theme::scope('contents.contents-fe-list', ['contents' => $contents])->render();
    }

    public static function getCreate($idCategory)
    {
        $contents = Contents::where('status', 'draft')->where('member_id', auth()->guard('member')->user()->id)->where('categories_contents_id', $idCategory)->first();

        if (is_null($contents)) {
            $categories = CategoriesContents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
            $nameCategories = CategoriesContents::where('id', $idCategory)->where('status', 'publish')->orderBy('created_at', 'DESC')->first()->name;

            Theme::breadcrumb()->add(__('contents'), route('contents.contents_list', ['idCategory' => $categories->first()->id]))
                ->add($nameCategories, route('contents.contents_list', ['idCategory' => $idCategory]))
                ->add(__('contents.create_contents'), 'http:...');
            Theme::setTitle(__('contents') . ' | ' . $nameCategories . ' | ' . __('contents.create_contents'));

            return Theme::scope('contents.contents-fe-create', ['contents' => null, 'categories' => $categories, 'idCategory' => $idCategory])->render();
        } else {
            return redirect()->route('contentsFE.edit', ['id' => $contents->id]);
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
            // 'end' => 'required',
            // 'enrollment_limit' => 'required',
            'status' => 'required',
            // 'notice' => 'required',
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb
        ]);
        try {
            $start = Carbon::createFromFormat('Y.m.d', $request->start)->startOfDay();
            // $end = Carbon::createFromFormat('Y.m.d',$request->end)->endOfDay();
        } catch (\Exception $ex) {
            $start = now()->startOfDay();
            // $end = now()->endOfDay();
        }
        $request->merge(['start' => $start]);
        // $request->merge(['end' => $end]);
        if ($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $contents = new Contents;
        $contents = $contents->create($request->input());

        $parent = MediaFolder::where('slug', 'contents-fe')->first();
        $folder = MediaFolder::create([
            'name' => $contents->id,
            'slug' => $contents->id,
            'user_id' => '0',
            'parent_id' => $parent->id ?? 0,
        ]);

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
            $contents->file_upload = $file;
        }

        $image_link = \RvMedia::handleUpload($request->image, $folder->id ?? 0);

        if ($image_link['error'] == false) {
            $contents->banner = $image_link['data']->url;
            $contents->save();
            //Re-slug

            addPointForMembers();
            $this->deleteFilePreview();

            event(new CreatedContentEvent(CONTENTS_MODULE_SCREEN_NAME, $request, $contents));

            return redirect()->route('contents.contents_list', ['idCategory' => $contents->categories_contents_id])->with('success', __('controller.create_successful', ['module' => __('contents')]));
        } else {
            return redirect()->back()->with('err', __('controller.save_failed'));
        }
    }

    public static function getEdit($id)
    {
        $contents = Contents::findOrFail($id);
        $categories = CategoriesContents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        Theme::breadcrumb()->add(__('contents'), route('contents.contents_list', ['idCategory' => $categories->first()->id]))
            ->add($contents->categories_contents->name, route('contents.contents_list', ['idCategory' => $contents->categories_contents_id]))
            ->add(__('contents.create_contents'), 'http:...');
        Theme::setTitle(__('contents') . ' | ' . $contents->categories_contents->name . ' | ' . __('contents.create_contents'));

        return Theme::scope('contents.contents-fe-create', ['contents' => $contents, 'categories' => $categories, 'idCategory' => $contents->categories_contents_id])->render();
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
            // 'end' => 'required',
            'enrollment_limit' => 'nullable',
            'status' => 'required',
            'notice' => 'notice',
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2 Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb
        ]);

        try {
            $start = Carbon::createFromFormat('Y.m.d', $request->start)->startOfDay();
            // $end = Carbon::createFromFormat('Y.m.d',$request->end)->endOfDay();
        } catch (\Exception $ex) {
            $start = now()->startOfDay();
            // $end = now()->endOfDay();
        }
        $request->merge(['start' => $start]);
        // $request->merge(['end' => $end]);
        $contents = Contents::findOrFail($id);
        if ($contents->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $parent = MediaFolder::where('slug', 'contents-fe')->first();
        $folder = MediaFolder::where('slug', $contents->id)->where('parent_id', $parent->id ?? 0)->first();
        if (is_null($folder)) {
            $folder = MediaFolder::create([
                'name' => $contents->id,
                'slug' => $contents->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }
        if ($request->hasFile('image')) {
            //lưu hình mới
            $image_link = \RvMedia::handleUpload($request->image, $folder->id ?? 0);

            if ($image_link['error'] != false) {
                return redirect()->route('contentsFE.create')->with('err', __('controller.save_image_failed'));
            }

            $request->merge(['banner' => $image_link['data']->url]);

            //---------- xóa hình cũ ------------
            $file = MediaFile::where('url', $contents->banner)->first();
            if ($file) {
                $file->delete();
            }

            $uploadManager = new UploadsManager;
            $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $contents->banner);
            $uploadManager->deleteFile($path, 1);
            //---------- ------------------

        }
        //delete old file
        $file_delete = $contents->file_upload;
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
        $contents->file_upload = $file_upload;
        $contents->update($request->input());
        $this->deleteFilePreview();
        event(new CreatedContentEvent(CONTENTS_MODULE_SCREEN_NAME, $request, $contents));

        return redirect()->route('contents.contents_list', ['idCategory' => $contents->categories_contents_id])->with('success', __('controller.update_successful', ['module' => __('contents')]));
    }

    public function delete(Request $request)
    {
        $contents = Contents::where('id', $request->input('id'))->firstOrFail();

        $parent = MediaFolder::where('slug', 'contents-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id', $parent->id)->first();
        if ($folder) {
            $directory = str_replace(basename($contents->banner), '', $contents->banner);
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
            $contents->delete();

            event(new DeletedContentEvent(CONTENTS_MODULE_SCREEN_NAME, $request, $contents));

            return redirect()->route('contents.contents_list', ['idCategory' => $contents->categories_contents_id])->with('success', __('controller.delete_successful', ['module' => __('contents')]));
        } catch (Exception $exception) {
            return redirect()->route('contents.contents_list')->with('error', __('controller.save_failed'));
        }
    }

    public function preview($request)
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
        $request->validate([
            'title' => 'required|max:120',
            'start' => 'required',
            'end' => 'required',
            'enrollment_limit' => 'required',
            'status' => 'required',
            'description' => 'required',
            'notice' => 'required',
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2 Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        try {
            $start = Carbon::createFromFormat('Y.m.d', $request->start)->startOfDay();
            $end = Carbon::createFromFormat('Y.m.d', $request->end)->endOfDay();
        } catch (\Exception $ex) {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
        }

        $request->merge(['base64Image' => json_encode($request->base64Image)]);
        $request->merge(['start' => $start]);
        $request->merge(['end' => $end]);

        $content = new Contents;
        $content->title = $request->title;
        $content->start = $request->start;
        $content->end = $request->end;
        $content->enrollment_limit = $request->enrollment_limit;
        $content->banner = $request->base64Image;
        $content->content = $request->content;
        $content->categories_contents_id = $request->categories_contents_id;
        $content->member_id = $request->member_id;
        $content->link = $request->link;

        $parent = MediaFolder::where('slug', 'contents-fe')->first();
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
            $preview_item = Contents::findOrFail($request->idPreview);
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

        $content->file_upload = $file_upload;


        $category = CategoriesContents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $temp = CategoriesContents::find($content->categories_contents_id);
        Theme::breadcrumb()->add(__('contents'), route('contents.contents_list', ['idCategory' => $category->first->id]))->add($temp->name, route('contents.contents_list', ['idCategory' => $content->categories_contents_id]))
//            ->add($content->title, 'http:...')
        ;
        Theme::setTitle(__('contents') . ' | ' . $temp->name . ' | ' . $content->title);

        return Theme::scope('contents.preview', ['contents' => $content, 'category' => $category, 'idCategory' => $content->categories_contents_id])->render();
    }

    function deleteFilePreview()
    {
        $parent = MediaFolder::where('slug', 'contents-fe')->first();
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
        $sympathy = Contents::findOrFail($id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_contents.member_id', $user->id);
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
                    sympathyCommentDetail("contents",$id,$reason,"dislike");
                }
            } else {
                cancelSympathyCommentOnPost("contents",$id,"dislike");
                $check->detach($user->id);

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
                sympathyCommentDetail("contents",$id,$reason,"dislike");
            }
            $dislike = 2;
        }
        $sympathy = Contents::withCount([
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
        $sympathy = Contents::findOrFail($id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_contents.member_id', $user->id);
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
                    sympathyCommentDetail("contents",$id,$reason,"like");
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnPost("contents",$id,"like");
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
                sympathyCommentDetail("contents",$id,$reason,"like");
            }
        }
        $sympathy = Contents::withCount([
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
        $contents_id = $request->post_id;
        $reason = $request->reason;
        $comment_id = $request->comment_id;
        $sympathy = CommentsContents::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_contents_comments.member_id', $user->id);
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 0);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 1,
                    'contents_id' => $contents_id,
                    'reason' => $reason,
                    'updated_at' => Carbon::now()
                ]);
                $dislike = 2;
                if($reason!=""){
                    sympathyCommentDetail("contents", $contents_id,$reason,"dislike",$comment_id);
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnComment("contents",$comment_id,"dislike");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 1,
                    'contents_id' => $contents_id,
                    'reason' => $reason,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $dislike = 2;
            if($reason!=""){
                sympathyCommentDetail("contents", $contents_id,$reason,"dislike",$comment_id);
            }
        }
        $sympathy = CommentsContents::withCount([
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
        $sympathy = CommentsContents::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_contents_comments.member_id', $user->id);
        $liked = 0;
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 1);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 0,
                    'contents_id' => $post_id,
                    'reason' => $reason,
                    'updated_at' => Carbon::now()
                ]);
                $liked = 2;
                if($reason!=""){
                    sympathyCommentDetail("contents", $post_id,$reason,"like",$comment_id);
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnComment("contents",$comment_id,"like");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 0,
                    'contents_id' => $post_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $liked = 1;
            if($reason!=""){
                sympathyCommentDetail("contents", $post_id,$reason,"like",$comment_id);
            }
        }
        $sympathy = CommentsContents::withCount([
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

        $gardenOwnerID = Contents::find($id)->member_id;
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
        $commentOwnerID = CommentsContents::find($commentId)->member_id;
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
