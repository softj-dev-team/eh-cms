<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Contents\Models\CategoriesContents;
use Botble\Contents\Models\CommentsContents;
use Botble\Contents\Models\Contents;
use Botble\MasterRoom\Models\CategoriesMasterRoom;
use Botble\MasterRoom\Models\CommentsMasterRoom;
use Botble\MasterRoom\Models\CommentsMasterRoomReply;
use Botble\MasterRoom\Models\MasterRoom;
use Botble\MasterRoom\Models\MasterRoomReply;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Theme;

class MasterRoomController extends Controller
{

    /**
     * @return \Response
     */
    public static function index($idCategory=null)
    {
        $categories = CategoriesMasterRoom::where('status','publish')->orderBy('position')->get();
        $selectCategories = null;

        if(is_null( $idCategory )){
            $selectCategories = CategoriesMasterRoom::where('status','publish')->orderBy('created_at', 'ASC')->firstOrFail();
        }else{
            $selectCategories = CategoriesMasterRoom::where('status','publish')->where('id', $idCategory )->firstOrFail();

        }

        $masterRoom = MasterRoom::where('status','publish')->where('categories_master_rooms_id', $selectCategories->id )
            ->with('masterRoomReplies')->withCount('comments')
            ->ordered()->paginate(10);

        Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))->add(  $selectCategories->name, 'http:...');

        Theme::setTitle(__('master_room').' | ' . $selectCategories->name);

        return Theme::scope('master-room.index', ['masterRoom' => $masterRoom, 'categories' => $categories, 'idCategory' =>  $selectCategories->id ])->render();
    }

    public function show($id,Request $request)
    {
        $idCategory = $request->input('idCategory');

        $masterRoom = MasterRoom::where('status','publish')->findOrFail($id);

        $masterRoom->lookup = $masterRoom->lookup + 1;
        $masterRoom->save();

        $comments = CommentsMasterRoom::where('master_rooms_id', $id)->where('parents_id', null)->paginate(10);

        $selectCategories = CategoriesMasterRoom::where('status','publish')->findOrFail($idCategory);
        Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))->add($selectCategories->name, route('masterRoomFE.list', ['idCategory' => $idCategory]))
//            ->add($masterRoom->title, 'http:...')
        ;

        $categories = CategoriesMasterRoom::where('status','publish')->orderBy('created_at', 'DESC')->get();


        Theme::setTitle(__('master_room').' | ' . $selectCategories->name . ' | ' . $masterRoom->title);
        // $top_comments = CommentsMasterRoom::where('master_rooms_id', $id)
        //     // ->withCount([
        //     //     'dislikes',
        //     // ])->withCount([
        //     //     'likes',
        //     // ])
        //     // ->having('likes_count', '>', 0)
        //     // ->orderBy('likes_count', 'DESC')
        //     ->take(3)->get();

        if (hasPermission('memberFE.isAdmin') || $masterRoom->member_id == auth()->guard('member')->user()->id) {
            $canEdit = hasPermission('masterRoomFE.edit');
            $canDelete = hasPermission('masterRoomFE.delete');
        } else {
            $canEdit = false;
            $canDelete = false;
        }
        $canCreateComment = hasPermission('masterRoomFE.create');
        $canDeleteComment = hasPermission('masterRoomFE.delete');
        $canViewComment = hasPermission('masterRoomFE.list');

        $masterRooms = MasterRoom::where('status','publish')->where('categories_master_rooms_id', $selectCategories->id )
            ->with('masterRoomReplies')->withCount('comments')
            ->ordered()->paginate(10);

        return Theme::scope('master-room.details', [
            'masterRoom' => $masterRoom,
            'comments' => $comments,
            'categories' => $categories,
            'idCategory' => $idCategory,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => null,
            'masterRoomParent' => null,
            'subList' => [
                'masterRoom' => $masterRooms,
                'categories' => $categories,
                'idCategory' =>  $selectCategories->id
            ]
        ])->render();
    }

    public function createComment(Request $request)
    {
        if (auth()->guard('member')->check()) {

            $anonymous = $request->is_secret_comments ?? 0;

            // $prefixContent = '';
            // if (!$request->parents_id) {
            //     $masterRoom = MasterRoom::where('id', $request->master_rooms_id)->first();
            //     if ($masterRoom) {
            //         $prefixContent = '[답장: ' . $masterRoom->title . ']<br>';
            //     }
            // }

            $master_rooms_id = $request->master_rooms_id;
            $content = $request->content;
            $parents_id = $request->parents_id;

            if ($request->isMasterRoomReply) {
                $comments = new CommentsMasterRoomReply;
                $comments->master_room_reply_id = $master_rooms_id;
            } else {
                $comments = new CommentsMasterRoom;
                $comments->master_rooms_id = $master_rooms_id;
            }
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->anonymous = $anonymous;
            $comments->save();

            addPointForMembers(1);

            return redirect()->back();
        } else {
            // return to login
            return redirect()->back();
        }
    }

    public function deleteComment($id){
        $comments = CommentsMasterRoom::findOrFail($id);

        foreach ($comments->getAllCommentByParentsID($id) as $item){
            $item->delete();
        }
        $comments->delete();
        return redirect()->back()->with('success', __('controller.deletecomment'));
    }

    public static function getList()
    {
        //getDataByCurrentLanguageCode

        $masterRoom = MasterRoom::where('status','draft')->orderBy('start', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);

        Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))->add(__('master_room.master_room_list'), 'http:...');

        Theme::setTitle(__('master_room.master_room_list'));

        return Theme::scope('master-room.master-room-fe-list', ['masterRoom' => $masterRoom])->render();
    }

    public static function getCreate($idCategory)
    {
        $masterRoom = MasterRoom::where('status','draft')->where('member_id', auth()->guard('member')->user()->id)->where('categories_master_rooms_id',$idCategory)->first();

        if(is_null( $masterRoom )){
            $categories = CategoriesMasterRoom::where('status','publish')->orderBy('created_at', 'DESC')->get();
            $nameCategories = CategoriesMasterRoom::where('id',$idCategory)->where('status','publish')->orderBy('created_at', 'DESC')->first()->name;

            Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))->add($nameCategories, 'http:...');
            Theme::setTitle(__('master_room'). ' | '.$nameCategories.' | ' .__('master_room.create_master_room'));

            return Theme::scope('master-room.master-room-fe-create', [
                'categories' => $categories,
                'masterRoom'=>null,
                'idCategory'=>$idCategory,
                'masterRoomParent' => null
            ])->render();
        }else {
            return redirect()->route('masterRoomFE.edit',['id'=>$masterRoom->id]);
        }
    }

    public static function getReplyCreate($id, $idCategory)
    {
        $masterRoom = MasterRoom::where('status','draft')->where('member_id', auth()->guard('member')->user()->id)->where('categories_master_rooms_id',$idCategory)->first();

        if(is_null( $masterRoom )){
            $categories = CategoriesMasterRoom::where('status','publish')->orderBy('created_at', 'DESC')->get();
            $nameCategories = CategoriesMasterRoom::where('id',$idCategory)->where('status','publish')->orderBy('created_at', 'DESC')->first()->name;

            Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))->add($nameCategories, 'http:...');
            Theme::setTitle(__('master_room'). ' | '.$nameCategories.' | ' .__('master_room.create_master_room'));

            $masterRoomParent = MasterRoom::where('id', $id)->first();

            return Theme::scope('master-room.master-room-fe-create', [
                'categories' => $categories,
                'masterRoom'=> null,
                'masterRoomParent'=> $masterRoomParent,
                'masterRoomId' => $id,
                'idCategory'=>$idCategory,
            ])->render();
        }else {
            return redirect()->route('masterRoomFE.edit',['id'=>$masterRoom->id]);
        }
    }

    public function showReply($id,Request $request)
    {
        $idCategory = $request->input('idCategory');

        $masterRoom = MasterRoomReply::where('status','publish')->findOrFail($id);

        $masterRoom->lookup = $masterRoom->lookup + 1;
        $masterRoom->save();

        $comments = CommentsMasterRoomReply::where('master_room_reply_id', $id)->where('parents_id', null)->paginate(10);

        $selectCategories = CategoriesMasterRoom::where('status','publish')->findOrFail($idCategory);
        Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))->add($selectCategories->name, route('masterRoomFE.list', ['idCategory' => $idCategory]));

        $categories = CategoriesMasterRoom::where('status','publish')->orderBy('created_at', 'DESC')->get();


        Theme::setTitle(__('master_room').' | ' . $selectCategories->name . ' | ' . $masterRoom->title);

        if (hasPermission('memberFE.isAdmin') || $masterRoom->member_id == auth()->guard('member')->user()->id) {
            $canEdit = hasPermission('masterRoomFE.edit');
            $canDelete = hasPermission('masterRoomFE.delete');
        } else {
            $canEdit = false;
            $canDelete = false;
        }
        $canCreateComment = hasPermission('masterRoomFE.create');
        $canDeleteComment = hasPermission('masterRoomFE.delete');
        $canViewComment = hasPermission('masterRoomFE.list');

        $masterRooms = MasterRoom::where('status','publish')->where('categories_master_rooms_id', $selectCategories->id )
            ->with('masterRoomReplies')->withCount('comments')
            ->ordered()->paginate(10);

        return Theme::scope('master-room.reply-details', [
            'masterRoom' => $masterRoom,
            'comments' => $comments,
            'categories' => $categories,
            'idCategory' => $idCategory,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => null,
            'masterRoomParent' => null,
            'subList' => [
                'masterRoom' => $masterRooms,
                'categories' => $categories,
                'idCategory' =>  $selectCategories->id
            ]
        ])->render();
    }

    public static function postStore(Request $request)
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
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb
        ]);
        try {
            $start = Carbon::createFromFormat('Y.m.d',$request->start)->startOfDay();
            $end = Carbon::createFromFormat('Y.m.d',$request->end)->endOfDay();
        } catch (\Exception $ex) {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
        }
        $request->merge(['start' =>  $start]);
        $request->merge(['end' => $end]);
        $request->merge(['status' => 'publish']);
        $request->merge(['published' => Carbon::now()]);

        if($request->has('master_room_id')) {
            $masterRoomReply = new MasterRoomReply;

            $params = [];
            foreach ($request->input() as $key => $value) {
                if ($key === 'title') {
                    $params[$key] = '[답장]'.$value;
                } else {
                    $params[$key] = $value;
                }
            }
            $masterRoom = $masterRoomReply->create($params);

            $parent = MediaFolder::where('slug', 'master-room-fe')->first();
            $folder = MediaFolder::create([
                'name' => $masterRoom->id . '_reply',
                'slug' => $masterRoom->id . '_reply',
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        } else {
            $masterRoom = new MasterRoom;
            $masterRoom = $masterRoom->create($request->input());

            $parent = MediaFolder::where('slug', 'master-room-fe')->first();
            $folder = MediaFolder::create([
                'name' => $masterRoom->id,
                'slug' => $masterRoom->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
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
            $masterRoom->file_upload = $file;
        }
        if ($request->hasFile('image')) {
            $image_link = \RvMedia::handleUpload($request->image, $folder->id ?? 0);
            if ($image_link['error'] == false) {
                $masterRoom->banner = $image_link['data']->url;
                $masterRoom->save();
                //Re-slug
            } else {
                return redirect()->back()->with('err', __('controller.save_failed'));
            }
        }

        addPointForMembers();

        event(new CreatedContentEvent(MASTER_ROOM_MODULE_SCREEN_NAME, $request, $masterRoom));

        return redirect()->route('masterRoomFE.list',['idCategory'=>$masterRoom->categories->id])->with('success', __('controller.create_successful',['module'=>__('master_room')]));

    }

    public static function getEdit($id)
    {

        $masterRoom = MasterRoom::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        $categories = CategoriesMasterRoom::where('status','publish')->orderBy('created_at', 'DESC')->get();
        $nameCategories =  $masterRoom->categories->name;
        $idCategory=  $masterRoom->categories->id;

        Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))
        ->add($nameCategories, route('masterRoomFE.list',['idCategory'=>$idCategory]))
        ->add(__('master_room.edit_master_room').'  #'. $masterRoom->id, 'http:...');
        Theme::setTitle(__('master_room'). ' | '.$nameCategories.' | ' .__('master_room.edit_master_room').'  #'. $masterRoom->id);
// dd(['masterRoom' => $masterRoom, 'categories' => $categories,'idCategory'=>$masterRoom->categories->id]);
        return Theme::scope('master-room.master-room-fe-create', ['masterRoom' => $masterRoom, 'categories' => $categories,'idCategory'=>$masterRoom->categories->id, 'masterRoomParent' => null])->render();
    }

    public static function getReplyEdit($id)
    {
        $masterRoomReply = MasterRoomReply::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        $categories = CategoriesMasterRoom::where('status','publish')->orderBy('created_at', 'DESC')->get();
        $nameCategories =  $masterRoomReply->categories->name;
        $idCategory=  $masterRoomReply->categories->id;

        Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))
            ->add($nameCategories, route('masterRoomFE.list',['idCategory'=>$idCategory]))
            ->add(__('master_room.edit_master_room').'  #'. $masterRoomReply->id, 'http:...');
        Theme::setTitle(__('master_room'). ' | '.$nameCategories.' | ' .__('master_room.edit_master_room').'  #'. $masterRoomReply->id);

        return Theme::scope('master-room.master-room-fe-create', [
            'masterRoom' => $masterRoomReply,
            'categories' => $categories,
            'idCategory'=>$masterRoomReply->categories->id,
            'masterRoomParent' => null,
            'masterRoomId' => $masterRoomReply->master_room_id
        ])->render();
    }

    public static function postUpdate($id, Request $request)
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
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb
        ]);
        try {
            $start = Carbon::createFromFormat('Y.m.d',$request->start)->startOfDay();
            $end = Carbon::createFromFormat('Y.m.d',$request->end)->endOfDay();
        } catch (\Exception $ex) {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
        }
        $request->merge(['start' =>  $start]);
        $request->merge(['end' => $end]);

        if($request->has('master_room_id')) {
            $masterRoom = MasterRoomReply::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        } else {
            $masterRoom = MasterRoom::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        if ($masterRoom->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $parent = MediaFolder::where('slug', 'master-room-fe')->first();
        $folder = MediaFolder::where('slug', $masterRoom->id)->where('parent_id', $parent->id ?? 0)->first();
        if (is_null($folder)) {
            $folder = MediaFolder::create([
                'name' => $masterRoom->id,
                'slug' => $masterRoom->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }

        if ($request->hasFile('image')) {
            //lưu hình mới
            $image_link = \RvMedia::handleUpload($request->image, $folder->id ?? 0);

            if ($image_link['error'] != false) {
                return redirect()->route('contentsFE.create')->with('err', __('controller.save_failed'));
            }

            $request->merge(['banner' => $image_link['data']->url]);

            //---------- xóa hình cũ ------------
            $file = MediaFile::where('url', $masterRoom->banner)->first();
            if ($file) {
                $file->forceDelete();
            }

            $uploadManager = new UploadsManager;
            $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $masterRoom->banner);
            $uploadManager->deleteFile($path, 1);
            //---------- ------------------

        }
        //delete old file
        $file_delete = $masterRoom->file_upload;
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
        $masterRoom->file_upload = $file_upload;
        $masterRoom->update($request->input());
        event(new CreatedContentEvent(MASTER_ROOM_MODULE_SCREEN_NAME, $request, $masterRoom));

        return  redirect()->route('masterRoomFE.list',['idCategory'=>$masterRoom->categories->id])->with('success', __('controller.update_successful',['module'=>__('master_room')]));
    }

    public function delete(Request $request)
    {
        if ($request->isMasterRoomReply) {
            $masterRoom = MasterRoomReply::where('id',$request->input('id'))->where('member_id',auth()->guard('member')->user()->id)->firstOrFail();
        } else {
            $masterRoom = MasterRoom::where('id',$request->input('id'))->where('member_id',auth()->guard('member')->user()->id)->firstOrFail();
        }

        $parent = MediaFolder::where('slug', 'master-room-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id',$parent->id)->first();
        if ($folder) {
            $directory =  str_replace( basename($masterRoom->banner),'',$masterRoom->banner );
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
            $masterRoom->delete();

            event(new DeletedContentEvent(MASTER_ROOM_MODULE_SCREEN_NAME, $request, $masterRoom));

            return redirect()->route('masterRoomFE.list',['idCategory'=>$masterRoom->categories->id])->with('success', __('controller.delete_successful',['module'=>__('master_room')]));
        } catch (Exception $exception) {
            return redirect()->route('masterRoomFE.list',['idCategory'=>$masterRoom->categories->id])->with('error',  __('controller.delete_failed'));
        }
    }
}
