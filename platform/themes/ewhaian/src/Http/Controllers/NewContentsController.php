<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Botble\NewContents\Models\CategoriesNewContents;
use Botble\NewContents\Models\CommentsNewContents;
use Botble\NewContents\Models\NewContents;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Theme;

class NewContentsController extends Controller
{

    /**
     * @return \Response
     */
    public static function index($idCategory=null)
    {
        $categories = CategoriesNewContents::where('status', 'publish')->get();
        $selectCategories = null;

        if (is_null($idCategory)) {
            $selectCategories = CategoriesNewContents::where('status', 'publish')->orderBy('created_at', 'DESC')->firstOrFail();
        } else {
            $selectCategories = CategoriesNewContents::where('status', 'publish')->where('id', $idCategory)->firstOrFail();

        }

        $newContents = NewContents::where('status', 'publish')->where('categories_new_contents_id', $selectCategories->id)->ordered()->paginate(10);

        Theme::breadcrumb()->add(__('new_contents'), route('newContentsFE.list'))->add($selectCategories->name, 'http:...');

        Theme::setTitle(__('new_contents').' | ' . $selectCategories->name);

        return Theme::scope('new-contents.index', ['newContents' => $newContents, 'categories' => $categories, 'idCategory' => $selectCategories->id])->render();
    }

    public function show($id, Request $request)
    {
        $idCategory = $request->input('idCategory');

        $newContents = NewContents::where('status', 'publish')->findOrFail($id);

        $newContents->lookup = $newContents->lookup + 1;
        $newContents->save();

        $comments = CommentsNewContents::where('new_contents_id', $id)->where('parents_id', null)->orderBy('created_at', 'DESC')->paginate(5);

        $selectCategories = CategoriesNewContents::where('status', 'publish')->findOrFail($idCategory);
        Theme::breadcrumb()->add(__('new_contents'), route('newContentsFE.list'))->add($selectCategories->name, route('newContentsFE.list', ['idCategory' => $idCategory]))
//            ->add($newContents->title, 'http:...')
        ;

        $categories = CategoriesNewContents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        Theme::setTitle(__('new_contents').' | ' . $selectCategories->name . ' | ' . $newContents->title);

        return Theme::scope('new-contents.details', ['newContents' => $newContents, 'comments' => $comments, 'categories' => $categories, 'idCategory' => $idCategory])->render();
    }

    public function createComment(Request $request)
    {
        if (auth()->guard('member')->check()) {

            $anonymous = $request->is_secret_comments ?? 0;

            $new_contents_id = $request->new_contents_id;
            $content = $request->content;
            $parents_id = $request->parents_id;

            $comments = new CommentsNewContents;
            $comments->new_contents_id = $new_contents_id;
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

    public function deleteComment($id)
    {
        $comments = CommentsNewContents::findOrFail($id);

        foreach ($comments->getAllCommentByParentsID($id) as $item) {
            $item->delete();
        }
        $comments->delete();
        return redirect()->back()->with('success', '댓글을 삭제하셨습니다');
    }

    public static function getList()
    {
        //getDataByCurrentLanguageCode

        $newContents = NewContents::where('status', 'draft')->orderBy('start', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);

        Theme::breadcrumb()->add(__('new_contents'), route('newContentsFE.list'))->add(__('new_contents.new_contents_list'), 'http:...');

        Theme::setTitle(__('new_contents.new_contents_list'));

        return Theme::scope('new-contents.new-contents-fe-list', ['newContents' => $newContents])->render();
    }

    public static function getCreate($idCategory)
    {

        $newContents = NewContents::where('status','draft')->where('member_id', auth()->guard('member')->user()->id)->where('categories_new_contents_id',$idCategory)->first();

        if(is_null( $newContents )){
            $categories = CategoriesNewContents::where('status','publish')->get();
            $nameCategories = CategoriesNewContents::where('id',$idCategory)->where('status','publish')->orderBy('created_at', 'DESC')->first()->name;

            Theme::breadcrumb()->add(__('new_contents'), route('newContentsFE.list'))
            ->add($nameCategories, route('newContentsFE.list',['idCategory'=>$idCategory]))
            ->add(__('new_contents.create_new_contents'), 'http:...');
            Theme::setTitle(__('new_contents'). ' | '.$nameCategories.' | ' .__('new_contents.create_new_contents'));

            return Theme::scope('new-contents.new-contents-fe-create', ['categories' => $categories,'newContents'=>null,'idCategory'=>$idCategory])->render();
        }else {
            return redirect()->route('newContentsFE.edit',['id'=>$newContents->id]);
        }


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
//            'enrollment_limit' => 'required',
            'status' => 'required',
//            'description' => 'required',
//            'notice' => 'required',
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:2000', // max 2000kb = 2Mb
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
        if($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $newContents = new NewContents;
        $newContents = $newContents->create($request->input());

        $parent = MediaFolder::where('slug', 'new-contents-fe')->first();
        $folder = MediaFolder::create([
            'name' => $newContents->id,
            'slug' => $newContents->id,
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
                    return redirect()->back()->with('err', __('controller.save_file_failed',['file'=>($key + 1)]) );
                }
            }
            $newContents->file_upload = $file;
        }
        if ($image_link['error'] == false) {
            $newContents->banner = $image_link['data']->url;
            $newContents->save();

            addPointForMembers();

            event(new CreatedContentEvent(NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $newContents));

            return redirect()->route('newContentsFE.list',['idCategory'=>$newContents->categories->id])->with('success', __('controller.create_successful',['module'=>__('new_contents')]));
        } else {
            return redirect()->back()->with('err', __('controller.save_failed'));        }
    }

    public static function getEdit($id)
    {

        $newContents = NewContents::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        $categories = CategoriesNewContents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $nameCategories =  $newContents->categories->name;
        $idCategory=  $newContents->categories->id;

        Theme::breadcrumb()->add(__('new_contents'), route('newContentsFE.list'))
        ->add($nameCategories, route('newContentsFE.list',['idCategory'=>$idCategory]))
        ->add(__('new_contents.edit_new_contents').' #'.$newContents->id, 'http:...');
        Theme::setTitle(__('new_contents'). ' | '.$nameCategories.' | ' .__('new_contents.edit_new_contents').' #'.$newContents->id);

        return Theme::scope('new-contents.new-contents-fe-create', ['newContents' => $newContents, 'categories' => $categories,'idCategory'=>$idCategory])->render();
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
            'enrollment_limit' => 'required',
            'status' => 'required',
            'description' => 'required',
            'notice' => 'required',
            'content' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2 Mb
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
        $newContents = NewContents::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        if($newContents->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $parent = MediaFolder::where('slug', 'new-contents-fe')->first();
        $folder = MediaFolder::where('slug', $newContents->id)->where('parent_id', $parent->id ?? 0)->first();
        if(is_null($folder)){
            $folder = MediaFolder::create([
                'name' => $newContents->id,
                'slug' => $newContents->id,
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
            $file = MediaFile::where('url', $newContents->banner)->first();
            if ($file) {
                $file->forceDelete();
            }

            $uploadManager = new UploadsManager;
            $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $newContents->banner);
            $uploadManager->deleteFile($path, 1);
            //---------- ------------------

        }
        //delete old file
        $file_delete = $newContents->file_upload;
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
        $newContents->file_upload = $file_upload;

        $newContents->update($request->input());
        event(new CreatedContentEvent(NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $newContents));

        return redirect()->route('newContentsFE.list',['idCategory'=>$newContents->categories->id])->with('success', __('controller.update_successful',['module'=>__('new_contents')]));

    }

    public function delete(Request $request)
    {
        $newContents = NewContents::where('id', $request->input('id'))->where('member_id', auth()->guard('member')->user()->id)->firstOrFail();

        $parent = MediaFolder::where('slug', 'new-contents-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id', $parent->id)->first();
        if ($folder) {
            $directory = str_replace(basename($newContents->banner), '', $newContents->banner);
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
            $newContents->delete();

            event(new DeletedContentEvent(NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $newContents));

            return redirect()->route('newContentsFE.list',['idCategory'=>$newContents->categories->id])->with('success', __('controller.delete_successful',['module'=>__('new_contents')]));
        } catch (Exception $exception) {
            return redirect()->route('newContentsFE.list',['idCategory'=>$newContents->categories->id])->with('error', __('controller.delete_failed'));
        }
    }
}
