<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Events\Models\CategoryEvents;
use Botble\Events\Models\Comments;
use Botble\Events\Models\CommentsEventsCmt;
use Botble\Events\Models\Events;
use Botble\Events\Models\EventsCmt;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Theme;

class EventsController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (hasPermission('eventsFE.list')) {
                return $next($request);
            };
            if ($request->ajax()) {
                return response()->json([], 403);
            }
            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        });
    }
    /**
     * @return \Response
     */
    public static function getList()
    {
        //getDataByCurrentLanguageCode

        $events = Events::where('status', 'draft')->orderBy('start', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);
        $categories = CategoryEvents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $categories->first->id]))->add(__('event.events_list'), 'http:...');

        Theme::setTitle(__('event.events_list'));

        return Theme::scope('event.events-fe-list', ['events' => $events])->render();

    }

    public static function getCreate($idCategory)
    {
        $currentCategories = CategoryEvents::where('id', $idCategory)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $permission = '';
        switch ($currentCategories->permission) {
            case CategoryEvents::EVENT:
                $permission = 'eventsFE.create.event';
                break;
            case CategoryEvents::EVENT_SKETCH:
                $permission = 'eventsFE.create.sketch';
                break;
            case CategoryEvents::AFFLIATION_EVENT:
                $permission = 'eventsFE.create.affliation';
                break;
            default:
                $permission = 'eventsFE.create.event';
                break;
        }
        if (!hasPermission($permission)) {
            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        }

        $event = Events::where('status', 'draft')->where('member_id', auth()->guard('member')->user()->id)->where('category_events_id', $idCategory)->first();
        if (is_null($event)) {
            $categories = CategoryEvents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
            $nameCategories = $currentCategories->name;

            Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $categories->first->id]))
                ->add($nameCategories, route('event.event_list', ['idCategory' => $idCategory]))
                ->add(__('event.create_events'), 'http:...');
            Theme::setTitle(__('event.menu__title') . ' | ' . $nameCategories . ' | ' . __('event.create_events'));

            return Theme::scope('event.events-fe-create', ['event' => null, 'categories' => $categories, 'idCategory' => $idCategory])->render();
        } else {
            return redirect()->route('eventsFE.edit', ['id' => $event->id]);
        }
    }

    public function postStore(Request $request, $idCategory)
    {
        $currentCategories = CategoryEvents::where('id', $idCategory)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $permission = '';
        switch ($currentCategories->permission) {
            case CategoryEvents::EVENT:
                $permission = 'eventsFE.create.event';
                break;
            case CategoryEvents::EVENT_SKETCH:
                $permission = 'eventsFE.create.sketch';
                break;
            case CategoryEvents::AFFLIATION_EVENT:
                $permission = 'eventsFE.create.affliation';
                break;
            default:
                $permission = 'eventsFE.create.event';
                break;
        }
        if (!hasPermission($permission)) {
            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        }

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
            'start' => 'required',
            'end' => 'required',
            'enrollment_limit' => 'required',
            'status' => 'required',
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        try {
            $start = Carbon::createFromFormat('Y.m.d', $request->start)->startOfDay();
            $end = Carbon::createFromFormat('Y.m.d', $request->end)->endOfDay();
        } catch (\Exception $ex) {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
        }

        $request->merge(['start' => $start]);
        $request->merge(['end' => $end]);
        if ($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }

        $events = new Events;
        $events = $events->create($request->input());

        $parent = MediaFolder::where('slug', 'events-fe')->first();
        $folder = MediaFolder::create([
            'name' => $events->id,
            'slug' => $events->id,
            'user_id' => '0',
            'parent_id' => $parent->id ?? 0,
        ]);
        $image_link = \RvMedia::handleUpload($request->image, $folder->id ?? 0);

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
            $events->file_upload = $file;
        }

        if ($image_link['error'] == false) {
            $events->banner = $image_link['data']->url;
            $events->save();

            $this->deleteFilePreview();
            addPointForMembers();
            event(new CreatedContentEvent(EVENTS_MODULE_SCREEN_NAME, $request, $events));

            return redirect()->route('event.event_list', ['idCategory' => $idCategory])->with('success', __('controller.create_successful', ['module' => __('event.menu__title')]));
        } else {
            return redirect()->back()->with('err', __('controller.save_failed'));
        }

    }

    public static function getEdit($id)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $event = Events::findOrFail($id);
        } else {
            $event = Events::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }
        $idCategory = $event->category_events_id;
        $currentCategories = CategoryEvents::where('id', $idCategory)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $categories = CategoryEvents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $nameCategories = $currentCategories->name;
        $permission = '';

        switch ($currentCategories->permisions) {
            case CategoryEvents::EVENT:
                $permission = 'eventsFE.edit.event';
                break;
            case CategoryEvents::EVENT_SKETCH:
                $permission = 'eventsFE.edit.sketch';
                break;
            case CategoryEvents::AFFLIATION_EVENT:
                $permission = 'eventsFE.edit.affliation';
                break;
            default:
                $permission = 'eventsFE.edit.event';
                break;
        }
        if (!hasPermission($permission)) {
            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        }
        Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $idCategory]))
            ->add($nameCategories, route('event.event_list', ['idCategory' => $idCategory]))
            ->add(__('event.edit_event', ['id' => $idCategory]), 'http:...');
        Theme::setTitle(__('event.menu__title') . ' | ' . $nameCategories . ' | ' . __('event.edit_event', ['id' => $idCategory]));

        return Theme::scope('event.events-fe-create', ['event' => $event, 'categories' => $categories, 'idCategory' => $idCategory])->render();
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
            'start' => 'required',
            'end' => 'required',
            'enrollment_limit' => 'required',
            'status' => 'required',
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        try {
            $start = Carbon::createFromFormat('Y.m.d', $request->start)->startOfDay();
            $end = Carbon::createFromFormat('Y.m.d', $request->end)->endOfDay();
        } catch (\Exception $ex) {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
        }

        $request->merge(['start' => $start]);
        $request->merge(['end' => $end]);

        if (hasPermission('memberFE.isAdmin')) {
            $events = Events::findOrFail($id);
        } else {
            $events = Events::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        $parent = MediaFolder::where('slug', 'events-fe')->first();
        $folder = MediaFolder::where('slug', $events->id)->where('parent_id', $parent->id ?? 0)->first();

        if ($request->hasFile('image')) {
            //lưu hình mới
            $image_link = \RvMedia::handleUpload($request->image, $folder->id ?? 0);

            if ($image_link['error'] != false) {
                return redirect()->route('eventsFE.create')->with('err', __('controller.save_failed'));
            }

            $request->merge(['banner' => $image_link['data']->url]);

            //---------- xóa hình cũ ------------
            $file = MediaFile::where('url', $events->banner)->first();
            if ($file) {
                $file->delete();
            }

            $uploadManager = new UploadsManager;
            $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $events->banner);
            $uploadManager->deleteFile($path, 1);
            //---------- ------------------

        }
        //delete old file
        $file_delete = $events->file_upload;
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
        $events->file_upload = $file_upload;
        if ($events->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $events->update($request->input());
        $this->deleteFilePreview();
        event(new CreatedContentEvent(EVENTS_MODULE_SCREEN_NAME, $request, $events));

        return redirect()->route('event.event_list', ['idCategory' => $events->category_events_id])->with('success', __('controller.update_successful', ['module' => __('event.menu__title')]));

    }

    /**
     * @return \Response
     */
    public static function index($idCategory, Request $request)
    {
        $selectCategories = CategoryEvents::where('status', 'publish')->findOrFail($idCategory);
        $events = Events::where('category_events_id', $idCategory)->where('status', 'publish')->ordered()->paginate(9);

        $categories = CategoryEvents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $categories->first->id]))->add($selectCategories->name, 'http:...');

        Theme::setTitle(__('event.menu__title') . ' | ' . $selectCategories->name);

        $style = $request->style ?? 1 ;

        switch ($selectCategories->permisions) {
            case CategoryEvents::EVENT:
                $permission = 'eventsFE.create.event';
                break;
            case CategoryEvents::EVENT_SKETCH:
                $permission = 'eventsFE.create.sketch';
                break;
            case CategoryEvents::AFFLIATION_EVENT:
                $permission = 'eventsFE.create.affliation';
                break;
            default:
                $permission = 'eventsFE.create.event';
                break;
        }
        $canCreate = hasPermission($permission);
        return Theme::scope('event.index', [
            'events' => $events,
            'category' => $categories,
            'idCategory' => $idCategory,
            'selectCategories' => $selectCategories,
            'style' => $style,
            'canCreate' => $canCreate,
        ])->render();

    }

    public function show($idCategory, $id)
    {

        $event = Events::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->where('id', $id)->where('status', '!=', 'draft')->firstOrFail();

        $event->views = $event->views + 1;
        $event->save();

        $comments = Comments::where('event_id', $id)
            ->where('status', 'publish')
            ->where('parents_id', null)
            ->withCount([
                'dislikes',
            ])
            ->withCount([
                'likes',
            ])
            ->paginate(10);

        $top_comments = Comments::where('event_id', $id)
            ->where('status', 'publish')
            ->withCount([
                'dislikes',
            ])
            ->withCount([
                'likes',
            ])
            ->having('likes_count', '>', 0)
            ->orderBy('likes_count', 'DESC')
            ->take(3)->get();
        $category = CategoryEvents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $selectCategories = CategoryEvents::find($idCategory);
        Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $category->first->id]))->add($selectCategories->name, route('event.event_list', ['idCategory' => $idCategory]))
//            ->add($event->title, 'http:...')
        ;

        Theme::setTitle(__('event.menu__title') . ' | ' . $selectCategories->name . ' | ' . $event->title);
        switch ($selectCategories->permisions) {
            case CategoryEvents::EVENT:
                $permission = 'eventsFE.create.event';
                $permissionEdit = 'eventsFE.edit.event';
                $permissionDelete = 'eventsFE.delete.event';
                $canCreateComment = 'eventsFE.create.comment.event';
                $canDeleteComment = 'eventsFE.delete.comment.event';
                break;
            case CategoryEvents::EVENT_SKETCH:
                $permission = 'eventsFE.create.sketch';
                $permissionEdit = 'eventsFE.edit.sketch';
                $permissionDelete = 'eventsFE.delete.sketch';
                $canCreateComment = 'eventsFE.create.comment.sketch';
                $canDeleteComment = 'eventsFE.delete.comment.sketch';
                break;
            case CategoryEvents::AFFLIATION_EVENT:
                $permission = 'eventsFE.create.affliation';
                $permissionEdit = 'eventsFE.edit.affliation';
                $permissionDelete = 'eventsFE.delete.affliation';
                $canCreateComment = 'eventsFE.create.comment.affliation';
                $canDeleteComment = 'eventsFE.delete.comment.affliation';
                break;
            default:
                $permission = 'eventsFE.create.event';
                $permissionEdit = 'eventsFE.edit.event';
                $permissionDelete = 'eventsFE.delete.event';
                $canCreateComment = 'eventsFE.create.comment.event';
                $canDeleteComment = 'eventsFE.delete.comment.event';
                break;
        }
        if (hasPermission('memberFE.isAdmin') || $event->member_id == auth()->guard('member')->user()->id) {
            $canEdit = hasPermission($permissionEdit);
            $canDelete = hasPermission($permissionDelete);
        } else {
            $canEdit = false;
            $canDelete = false;
        }
        $canDeleteComment = hasPermission($canDeleteComment);
        $canViewComment = hasPermission('eventsFE.comment');
        $canCreateComment = hasPermission($canCreateComment);

        $events = Events::where('category_events_id', $idCategory)->where('status', 'publish')->ordered()->paginate(9);
        $categories = CategoryEvents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $style = 1;
        $canCreate = hasPermission($permission);

        return Theme::scope('event.details', [
            'event' => $event,
            'comments' => $comments,
            'category' => $category,
            'idCategory' => $idCategory,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => $top_comments,
            'subList' => [
                'events' => $events,
                'category' => $categories,
                'idCategory' => $idCategory,
                'selectCategories' => $selectCategories,
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
            if ($anonymous == 1) {
                $ip_address = $request->ip();
            }

            $event_id = $request->event_id;
            $content = $request->content;
            $parents_id = $request->parents_id;

            $comments = new Comments;
            $comments->event_id = $event_id;
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->anonymous = $anonymous;
            $comments->ip_address = $ip_address ?? null;

            $comments->save();


            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comments->id,
                'slug' => "comment-event-".$comments->id,
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

    public function delete(Request $request)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $events = Events::where('id', $request->input('id'))->firstOrFail();
        } else {
            $events = Events::where('id', $request->input('id'))->where('member_id', auth()->guard('member')->user()->id)->firstOrFail();
        }

        $parent = MediaFolder::where('slug', 'events-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id', $parent->id)->first();
        if ($folder) {
            $directory = str_replace(basename($events->banner), '', $events->banner);
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
            $events->delete();

            event(new DeletedContentEvent(EVENTS_MODULE_SCREEN_NAME, $request, $events));

            return redirect()->route('event.event_list', ['idCategory' => $events->category_events_id])->with('success', __('controller.delete_successful', ['module' => __('event.menu__title')]));

        } catch (Exception $exception) {
            return redirect()->route('event.event_list', ['idCategory' => $events->category_events_id])->with('success', __('controller.delete_failed'));
        }
    }

    public function deleteComment($id)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $comments = Comments::findOrFail($id);
        } else {
            $comments = Comments::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        // foreach ($comments->getAllCommentByParentsID($id) as $item) {
        //     $item->delete();
        // }
        // $comments->delete();


        if($comments->parents_id > 0){
            $parentComment = Comments::findOrFail($comments->parents_id);
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

            $folder = MediaFolder::where('slug', "comment-event-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }



        return redirect()->back()->with('success', __('controller.deletecomment'));
    }

    public function listComment()
    {
        $category = CategoryEvents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $events = EventsCmt::where('status', 'publish')->ordered()->paginate(10);

        Theme::setTitle(__('event.event_comments'));
        Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $category->first->id]))->add(__('event.event_comments'), 'http:...');

        return Theme::scope('event.eventscmt.index', ['events' => $events, 'category' => $category])->render();
    }

    public function detailComments($id)
    {

        $event = EventsCmt::where('status', 'publish')->findOrFail($id);
        $event->views = $event->views + 1;
        $event->save();

        $comments = CommentsEventsCmt::where('events_cmt_id', $id)->where('status', 'publish')->where('parents_id', null)
            ->withCount([
                'dislikes',
            ])->withCount([
            'likes',
        ])
            ->paginate(10);
        $top_comments = CommentsEventsCmt::where('events_cmt_id', $id)
            ->where('status', 'publish')
            ->withCount([
                'dislikes',
            ])->withCount([
            'likes',
        ])
        ->having('likes_count', '>', 0)
        ->orderBy('likes_count', 'DESC')
        ->take(3)->get();
        $category = CategoryEvents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $category->first->id]))->add(__('event.event_comments.list'), route('event.cmt.list'))
//            ->add($event->title, 'http:...')
        ;

        Theme::setTitle(__('event.menu__title') . ' |  ' . __('event.event_comments.list') . ' | ' . $event->title);

        if (hasPermission('memberFE.isAdmin') || $event->member_id == auth()->guard('member')->user()->id) {
            $canEdit = hasPermission('eventsFE.cmt.edit');
            $canDelete = hasPermission('eventsFE.cmt.delete');
        } else {
            $canEdit = false;
            $canDelete = false;
        }
        $canDeleteComment = hasPermission('eventsFE.delete.comment.cmt');
        $canCreateComment = hasPermission('eventsFE.create.comment.cmt');
        $canViewComment = hasPermission('eventsFE.comment');

        $events = EventsCmt::where('status', 'publish')->ordered()->paginate(10);

        return Theme::scope('event.eventscmt.details', [
            'event' => $event,
            'comments' => $comments,
            'category' => $category,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => $top_comments,
            'subList' => [
                'events' => $events,
                'category' => $category
            ]
        ])->render();
    }

    public function createEventsCmtComment(Request $request)
    {
        $file = "";
        if (auth()->guard('member')->check()) {

            $anonymous = $request->is_secret_comments ?? 0;
            $events_cmt_id = $request->events_cmt_id;
            $content = $request->content;
            $parents_id = $request->parents_id;
            if ($anonymous == 1) {
                $ip_address = $request->ip();
            }

            $comments = new CommentsEventsCmt;
            $comments->events_cmt_id = $events_cmt_id;
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->anonymous = $anonymous;
            $comments->ip_address = $ip_address ?? null;
            $comments->save();

            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comments->id,
                'slug' => "comment-event-cmt-".$comments->id,
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

    public function getEventCmtCreate()
    {
        $events = EventsCmt::where('member_id', auth()->guard('member')->user()->id)->where('status', 'draft')->orderby('created_at', 'DESC')->first();

        if (is_null($events)) {
            $categories = CategoryEvents::orderBy('created_at', 'DESC')->get();

            Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $categories->first()->id]))
                ->add(__('event.event_comments'), route('event.cmt.list'))
                ->add(__('event.event_comments.create_event_comments'), 'http:...');
            Theme::setTitle(__('event.menu__title') . ' | ' . __('event.event_comments') . ' | ' . __('event.event_comments.create_event_comments'));

            return Theme::scope('event.eventscmt.create', ['event' => null, 'categories' => $categories])->render();
        } else {
            return redirect()->route('eventsFE.cmt.edit', ['id' => $events->id]);
        }

    }

    public function postEventCmtStore(Request $request)
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
            'detail' => 'required',
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        if ($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $events = new EventsCmt;
        $events = $events->create($request->input());

        $parent = MediaFolder::where('slug', 'event-cmt-fe')->first();
        $folder = MediaFolder::create([
            'name' => $events->id,
            'slug' => $events->id,
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
            $events->file_upload = $file;
        }
        $events->save();
        addPointForMembers();
        $this->deleteFilePreview('event-cmt-fe');
        event(new CreatedContentEvent(EVENTS_CMT_MODULE_SCREEN_NAME, $request, $events));

        return redirect()->route('event.cmt.list')->with('success', __('controller.create_successful', ['module' => __('event.event_comments')]));
    }

    public function getEventCmtList()
    {
        $events = EventsCmt::where('member_id', auth()->guard('member')->user()->id)->where('status', 'draft')->orderby('created_at', 'DESC')->paginate(10);
        Theme::breadcrumb()->add(__('event.event_comments'), route('event.cmt.list'))->add(__('event.event_comments.list'), 'http:...');

        Theme::setTitle(__('event.event_comments.list'));

        return Theme::scope('event.eventscmt.list', ['events' => $events])->render();

    }

    public function getEventCmtEdit($id)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $event = EventsCmt::where('id', $id)->firstOrFail();
        } else {
            $event = EventsCmt::where('member_id', auth()->guard('member')->user()->id)->where('id', $id)->firstOrFail();
        }

        $categories = CategoryEvents::orderBy('created_at', 'DESC')->get();

        Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $categories->first()->id]))
            ->add(__('event.event_comments'), route('event.cmt.list'))
            ->add(__('event.event_comments.edit_event_comments'), 'http:...');
        Theme::setTitle(__('event.menu__title') . ' | ' . __('event.event_comments') . ' | ' . __('event.event_comments.edit_event_comments'));

        return Theme::scope('event.eventscmt.create', ['event' => $event, 'categories' => $categories])->render();

    }

    public function postEventCmtUpdate($id, Request $request)
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
            'status' => 'required',
            'detail' => 'required',
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        if (hasPermission('memberFE.isAdmin')) {
            $event = EventsCmt::where('id', $id)->firstOrFail();
        } else {
            $event = EventsCmt::where('member_id', auth()->guard('member')->user()->id)->where('id', $id)->firstOrFail();
        }

        if ($event->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $parent = MediaFolder::where('slug', 'event-cmt-fe')->first();
        $folder = MediaFolder::where('slug', $event->id)->where('parent_id', $parent->id ?? 0)->first();
        if (is_null($folder)) {
            $folder = MediaFolder::create([
                'name' => $event->id,
                'slug' => $event->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }
        //delete old file
        $file_delete = $event->file_upload;
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
        $event->file_upload = $file_upload;
        $events = $event->update($request->input());
        $this->deleteFilePreview('event-cmt-fe');
        event(new CreatedContentEvent(EVENTS_CMT_MODULE_SCREEN_NAME, $request, $events));

        return redirect()->route('event.cmt.list')->with('success', __('controller.update_successful', ['module' => __('event.menu__title')]));
    }

    public function deleteEventCmt(Request $request)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $event = EventsCmt::where('id', $request->input('id'))->firstOrFail();
        } else {
            $event = EventsCmt::where('member_id', auth()->guard('member')->user()->id)->where('id', $request->input('id'))->firstOrFail();
        }

        $parent = MediaFolder::where('slug', 'event-cmt-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id', $parent->id)->first();
        if ($folder) {
            $directory = str_replace(basename(geFirsttImageInArray($event->file_upload, null, 1)), '', geFirsttImageInArray($event->file_upload, null, 1));
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
            $event->delete();

            event(new DeletedContentEvent(EVENTS_CMT_MODULE_SCREEN_NAME, $request, $event));

            return redirect()->route('event.cmt.list')->with('success', __('controller.delete_successful', ['module' => __('event.menu__title')]));

        } catch (Exception $exception) {
            return redirect()->route('eventsFE.cmt.list')->with('error', __('controller.delete_failed'));
        }

    }

    public function deleteEventsCmtComment($id)
    {
        if (hasPermission('memberFE.isAdmin')) {
            $comments = CommentsEventsCmt::findOrFail($id);
        } else {
            $comments = CommentsEventsCmt::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        // foreach ($comments->getAllCommentByParentsID($id) as $item) {
        //     $item->delete();
        // }
        // $comments->delete();

        if($comments->parents_id > 0){
            $parentComment = CommentsEventsCmt::findOrFail($comments->parents_id);
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

            $folder = MediaFolder::where('slug', "comment-event-cmt-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }


        return redirect()->back()->with('success', __('controller.deletecomment'));
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
        $request->validate([
            'title' => 'required|max:120',
            'start' => 'required',
            'end' => 'required',
            'enrollment_limit' => 'required',
            'status' => 'required',
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
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

        $event = new Events;
        $event->title = $request->title;
        $event->start = $request->start;
        $event->end = $request->end;
        $event->enrollment_limit = $request->enrollment_limit;
        $event->banner = $request->base64Image;
        $event->content = $request->content;
        $event->category_events_id = $request->category_events_id;
        $event->member_id = $request->member_id;
        $event->link = $request->link;

        $parent = MediaFolder::where('slug', 'events-fe')->first();
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
                $preview_item = Events::findOrFail($request->idPreview);
            } else {
                $preview_item = Events::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idPreview);
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

        $event->file_upload = $file_upload;

        $category = CategoryEvents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $selectCategories = CategoryEvents::find($event->category_events_id);
        Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $category->first->id]))->add($selectCategories->name, route('event.event_list', ['idCategory' => $event->category_events_id]))
//            ->add($event->title, 'http:...')
        ;

        Theme::setTitle(__('event.menu__title') . ' | ' . $selectCategories->name . ' | ' . $event->title);

        return Theme::scope('event.preview', ['event' => $event, 'category' => $category, 'idCategory' => $selectCategories->id ?? 0])->render();

    }

    public function deleteFilePreview($parentFolder = 'events-fe')
    {
        $parent = MediaFolder::where('slug', $parentFolder)->first();
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

    public function previewEventCmt(Request $request)
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
            'status' => 'required',
            'detail' => 'required',
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);

        $event = new EventsCmt;
        $event->title = $request->title;
        $event->detail = $request->detail;
        $event->member_id = $request->member_id;
        $event->link = $request->link;

        $parent = MediaFolder::where('slug', 'event-cmt-fe')->first();
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
                $ads_item = EventsCmt::findOrFail($request->idPreview);
            } else {
                $ads_item = EventsCmt::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idPreview);
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

        $event->file_upload = $file_upload;
        $category = CategoryEvents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $category->first->id]))->add(__('event.event_comments.list'), route('event.cmt.list'))
//            ->add($event->title, 'http:...')
        ;

        Theme::setTitle(__('event.menu__title') . ' |  ' . __('event.event_comments.list') . ' | ' . $event->title);

        return Theme::scope('event.eventscmt.preview', ['event' => $event, 'category' => $category])->render();

    }

    public static function dislike(Request $request)
    {
        $events_id = $request->post_id;
        $reason = $request->reason;
        $comment_id = $request->comment_id;
        $sympathy = Comments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_event_comments.member_id', $user->id);
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 0);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 1,
                    'events_id' => $events_id,
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
                    'events_id' => $events_id,
                    'reason' => $reason,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $dislike = 2;
        }
        $sympathy = Comments::withCount([
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

    public static function like(Request $request)
    {
        $events_id = $request->post_id;
        $comment_id = $request->comment_id;
        $sympathy = Comments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_event_comments.member_id', $user->id);
        $liked = 0;
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 1);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 0,
                    'events_id' => $events_id,
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
                    'events_id' => $events_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $liked = 1;
        }
        $sympathy = Comments::withCount([
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
    public static function dislikeEventCmt(Request $request)
    {
        $events_id = $request->post_id;
        $reason = $request->reason;
        $comment_id = $request->comment_id;
        $sympathy = CommentsEventsCmt::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_eventcmt_comment.member_id', $user->id);
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 0);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 1,
                    'ecmt_id' => $events_id,
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
                    'ecmt_id' => $events_id,
                    'reason' => $reason,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $dislike = 2;
        }
        $sympathy = CommentsEventsCmt::withCount([
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

    public static function likeEventCmt(Request $request)
    {
        $events_id = $request->post_id;
        $comment_id = $request->comment_id;
        $sympathy = CommentsEventsCmt::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_eventcmt_comment.member_id', $user->id);
        $liked = 0;
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 1);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 0,
                    'ecmt_id' => $events_id,
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
                    'ecmt_id' => $events_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $liked = 1;
        }
        $sympathy = CommentsEventsCmt::withCount([
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

        $eventOwnerID = Events::find($id)->member_id;
        $currentUserID = auth()->guard('member')->user()->id;
        $allow = 1;

        if($eventOwnerID == $currentUserID){
            $allow = 0;
        }

        return response()->json(
            [
                'valid' =>$allow,
            ]
        );
    }

    public static function dislikePost(Request $request) {
        $id = $request->post_id;
        $reason = $request->reason;
        $sympathy = Events::findOrFail($id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_events.member_id', $user->id);
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
                    sympathyCommentDetail("events",$id,$reason,"dislike");
                }
            } else {
                cancelSympathyCommentOnPost("events",$id,"dislike");
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
                sympathyCommentDetail("events",$id,$reason,"dislike");
            }
            $dislike = 2;
        }
        $sympathy = Events::withCount([
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


    public static function likePost(Request $request) {

        $id = $request->id;
        $reason = $request->reason;
        $sympathy = Events::findOrFail($id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_events.member_id', $user->id);
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
                    sympathyCommentDetail("events",$id,$reason,"like");
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnPost("events",$id,"like");
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
                sympathyCommentDetail("events",$id,$reason,"like");
            }
        }
        $sympathy = Events::withCount([
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

    public static function checkSympathyPermissionOnComment(Request $request) {
        $commentId = $request->comment_id;
        $commentOwnerID = Comments::find($commentId)->member_id;
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

    public static function checkSympathyPermissionOnEventComment(Request $request) {
        $commentId = $request->comment_id;
        $commentOwnerID = CommentsEventsCmt::find($commentId)->member_id;
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
