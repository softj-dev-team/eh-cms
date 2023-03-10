<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Campus\Models\Description\DescriptionCampus;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Life\Models\Ads\Ads;
use Botble\Life\Models\Ads\AdsCategories;
use Botble\Life\Models\Description;
use Botble\Life\Models\Jobs\JobsCategories;
use Botble\Life\Models\Jobs\JobsComments;
use Botble\Life\Models\Jobs\JobsPartTime;
use Botble\Life\Models\Notices;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Theme;

class JobsPartTimeController extends Controller
{

    public function __construct() {
        $this->middleware(function ($request, $next) {
            if(hasPermission('jobsPartTimeFE.list')){
                return $next($request);
            };
            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        });
    }

    /**
     * @return \Response
     */
    public function index(Request $request)
    {
        $style = $request->style ?? 0;
        $firstParent = JobsCategories::withcondition()->where('parent_id', 1 )->where('status','publish')->first();
        // $jobs = JobsPartTime::withCount(['dislikes'])
        // ->has('dislikes','<',10)
        // ->rejectcategories()
        // ->ordered()->paginate(10);

        $jobs = JobsPartTime::rejectcategories()->ordered()->paginate(10);

        Theme::breadcrumb()->add(__('life.part-time_job'), route('life.part_time_jobs_list'))->add(__('life.part-time_job'), 'http:...');

        $categories = JobsCategories::withcondition()->where('status', 'publish')->get();

        Theme::setTitle(__('life').' | '.__('life.part-time_job') );

        $notices = NoticesIntroduction::code('JOBS_PART_TIME_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = Description::where('code', 'JOBS_PART_TIME_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        $canCreate = hasPermission('jobsPartTimeFE.create');

        return Theme::scope('life.jobs.index', [
            'jobs' => $jobs,
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'style' => $style,
            'idFirstParent'=> $firstParent->id,
            'canCreate' => $canCreate
        ])->render();

    }

    public static function detailNotice($id)
    {
        $notices = NoticesIntroduction::where('id', $id)->where('status', 'publish')->first();
        $notices->lookup = $notices->lookup + 1;
        $notices->save();

        $subJobs = JobsPartTime::rejectcategories()->ordered()->paginate(10);

        Theme::breadcrumb()->add(__('life.part-time_job'), route('life.part_time_jobs_list'))
        ;

        Theme::setTitle(__('life').' | '.__('life.part-time_job'));

        $style = 0;
        $firstParent = JobsCategories::withcondition()->where('parent_id', 1 )->where('status','publish')->first();
        $categories = JobsCategories::withcondition()->where('status', 'publish')->get();
        $canCreate = hasPermission('jobsPartTimeFE.create');
        return Theme::scope('life.jobs.notice', [
            'jobs' => $subJobs,
            'notices' => $notices,
            'canCreate' => $canCreate,
            'subList' => [
                'jobs' => $subJobs,
                'categories' => $categories,
                'style' => $style,
                'idFirstParent'=> $firstParent->id,
                'canCreate' => $canCreate
            ]
        ])->render();


    }

    public function show($id)
    {
        $jobs = JobsPartTime::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])
        ->has('dislikes','<',10)
        ->where('status','!=','draft')
        ->rejectcategories()
        ->findOrFail($id);
        $jobs->lookup = $jobs->lookup + 1;
        $jobs->save();

        $comments = JobsComments::where('jobs_part_time_id', $id)->where('parents_id', null)->where('status','publish')
        ->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->paginate(10);

        $top_comments = JobsComments::where('jobs_part_time_id', $id)->where('status','publish')
        ->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])
        ->having('likes_count', '>', 0)
        ->orderBy('likes_count', 'DESC')
        ->take(3)->get();

        Theme::breadcrumb()->add(__('life'), route('life.flare_market_list'))->add(__('life.part-time_job'), route('life.part_time_jobs_list'))
//            ->add($jobs->title, 'http:...')
        ;

        Theme::setTitle(__('life').' | '.__('life.part-time_job').' | ' . $jobs->title);

        if(hasPermission('memberFE.isAdmin') || $jobs->member_id ==  auth()->guard('member')->user()->id ) {
            $canEdit = hasPermission('jobsPartTimeFE.edit');
            $canDelete = hasPermission('jobsPartTimeFE.delete');
        } else {
            $canEdit = false;
            $canDelete = false;
        }
        if($jobs->status=='approve'){
            $canEdit = false;
        }
        $canCreateComment = hasPermission('jobsPartTimeFE.comment.create');
        $canDeleteComment = hasPermission('jobsPartTimeFE.comment.delete');
        $canViewComment = hasPermission('jobsPartTimeFE.comment');

        $style = 0;
        $firstParent = JobsCategories::withcondition()->where('parent_id', 1 )->where('status','publish')->first();
        $categories = JobsCategories::withcondition()->where('status', 'publish')->get();
        $canCreate = hasPermission('jobsPartTimeFE.create');
        $subJobs = JobsPartTime::rejectcategories()->ordered()->paginate(10);

        return Theme::scope('life.jobs.details', [
            'jobs' => $jobs,
            'comments' => $comments,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => $top_comments,
            'subList' => [
                'jobs' => $subJobs,
                'categories' => $categories,
                'style' => $style,
                'idFirstParent'=> $firstParent->id,
                'canCreate' => $canCreate
            ]
        ])->render();
    }

    public function createComment(Request $request)
    {
        $file = "";
        if (auth()->guard('member')->check()) {

            $anonymous = $request->is_secret_comments ?? 0;
            $jobs_part_time_id = $request->jobs_part_time_id;
            $content = $request->content;
            $parents_id = $request->parents_id;
            if( $anonymous == 1) {
                $ip_address = $request->ip();
            }

            $comments = new JobsComments;
            $comments->jobs_part_time_id = $jobs_part_time_id;
            $comments->member_id = auth()->guard('member')->user()->id ;
            $comments->anonymous = $anonymous;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->ip_address = $ip_address ?? null;
            $comments->save();

            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comments->id,
                'slug' => "comment-life-job-".$comments->id,
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
        if(hasPermission('memberFE.isAdmin')) {
            $comments = JobsComments::findOrFail($id);
        } else {
            $comments = JobsComments::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }
        // foreach ($comments->getAllCommentByParentsID($id) as $item){
        //     $item->delete();
        // }
        // $comments->delete();

        if($comments->parents_id > 0){
            $parentComment = JobsComments::findOrFail($comments->parents_id);
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

            $folder = MediaFolder::where('slug', "comment-life-job-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }



        return redirect()->back()->with('success', '댓글을 삭제하셨습니다');
    }

    public static function getList()
    {
        //getDataByCurrentLanguageCode

        $jobs = JobsPartTime::orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);

        Theme::breadcrumb()->add(__('life.part-time_job'), route('life.part_time_jobs_list'))->add(__('life.part-time_job.part-time_job_list'), 'http:...');

        Theme::setTitle(__('life.part-time_job').' | '.__('life.part-time_job.part-time_job_list'));

        return Theme::scope('life.jobs.jobs-fe-list', ['jobs' => $jobs])->render();

    }

    public static function getCreate($categoryId )
    {
        $jobs = JobsPartTime::where('status','draft')
        ->rejectcategories()
        ->orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->first();


        if( is_null($jobs)){
            Theme::breadcrumb()->add(__('life'), route('life.open_space_list'))
            ->add(__('life.part-time_job'), route('life.part_time_jobs_list'))
            ->add(__('life.part-time_job.create_part-time_job'), 'http:...');
            Theme::setTitle(__('life') . ' | '.__('life.part-time_job').' | ' .__('life.part-time_job.create_part-time_job'));

            $parent = JobsCategories::withcondition()->where('id', $categoryId)->firstOrFail();
            $firstParent = JobsCategories::withcondition()->where('parent_id', 1 )->where('status','publish')->get();
            $categories = JobsCategories::withcondition()->where('status', 'publish')->get();
            $description = Description::where('code', 'JOBS_PART_TIME_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

            return Theme::scope('life.jobs.jobs-fe-create', ['jobs' => null, 'categories' => $categories,'description'=>$description, 'firstParent' => $firstParent, 'parent' => $parent])->render();
        } else {
            return redirect()->route('jobsPartTimeFE.edit',['id'=>$jobs->id]);
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
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        $request->validate([
            'title' => 'required|max:120',
            'contact' => 'required',
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        if($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['categories' => $request->input('categories')]);

        $jobs = new JobsPartTime;
        $jobs = $jobs->create($request->input());

        $parent = MediaFolder::where('slug', 'part-time-jobs-fe')->first();
        $folder = MediaFolder::create([
            'name' => $jobs->id,
            'slug' => $jobs->id,
            'user_id' => '0',
            'parent_id' => $parent->id ?? 0,
        ]);
        if ($request->hasFile('images')) {
            $image_link = \RvMedia::handleUpload($request->images, $folder->id ?? 0);
            if ($image_link['error'] == false) {
                $jobs->images = $image_link['data']->url;


            } else {
                return redirect()->back()->with('err', __('controller.save_failed'));
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
            $jobs->file_upload = $file;
        }
        $jobs->save();
        addPointForMembers();
        $this->deleteFilePreview();

        event(new CreatedContentEvent(JOBS_PART_TIME_MODULE_SCREEN_NAME, $request, $jobs));

        return redirect()->route('life.part_time_jobs_list')->with('success', __('controller.create_successful',['module'=>__('life.part-time_job')]));

    }

    public static function getEdit($id)
    {
        Theme::breadcrumb()->add(__('life.part-time_job'), route('jobsPartTimeFE.list'))->add(__('life.part-time_job.edit_part-time_job'), 'http:...');
        if(hasPermission('memberFE.isAdmin')) {
            $jobs = JobsPartTime::rejectcategories()->findOrFail($id);

        } else {
            $jobs = JobsPartTime::where('member_id', auth()->guard('member')->user()->id)->rejectcategories()->findOrFail($id);
        }

        $categories = JobsCategories::withcondition()->where('status', 'publish')->get();
        $selectedCategories = JobsCategories::withcondition()->where('id', $jobs->categories)->where('status', 'publish')->first();
        $description = Description::where('code', 'JOBS_PART_TIME_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $parent = JobsCategories::withcondition()->where('id', $jobs->categories[1])->firstOrFail();
        $firstParent = JobsCategories::withcondition()->where('parent_id', 1 )->where('status','publish')->first();
        Theme::setTitle(__('life.part-time_job').' | '.__('life.part-time_job.edit_part-time_job').' #' . $jobs->id);
        return Theme::scope('life.jobs.jobs-fe-edit', ['jobs' => $jobs, 'categories' => $categories, 'selectedCategories' => $selectedCategories,'description'=>$description, 'parent' => $parent, 'firstParent' => $firstParent])->render();
    }

    public function postUpdate($id, Request $request)
    {
        if(hasPermission('memberFE.isAdmin')) {
            $jobs = JobsPartTime::rejectcategories()->findOrFail($id);
        } else {
            $jobs = JobsPartTime::where('member_id', auth()->guard('member')->user()->id)->rejectcategories()->findOrFail($id);
        }

        $jobs->status = $request->status;
        $jobs->save();

        event(new CreatedContentEvent(JOBS_PART_TIME_MODULE_SCREEN_NAME, $request, $jobs));

        return redirect()->route('life.part_time_jobs_list')->with('success', __('controller.update_successful',['module'=>__('life.part-time_job')]));


    }
    public function postUpdateBackUp($id, Request $request)
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
        $request->merge(['categories' => $request->input('categories')]);
        $request->validate([
            'title' => 'required|max:120',
            'contact' => 'required',
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);
        $jobs = JobsPartTime::where('member_id', auth()->guard('member')->user()->id)
        ->rejectcategories()
        ->findOrFail($id);
        if($jobs->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $parent = MediaFolder::where('slug', 'part-time-jobs-fe')->first();
        $folder = MediaFolder::where('slug', $jobs->id)->where('parent_id', $parent->id ?? 0)->first();
        if(is_null($folder)){
            $folder = MediaFolder::create([
                'name' => $jobs->id,
                'slug' => $jobs->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }
        if ($request->hasFile('images')) {
            //lưu hình mới
            $image_link = \RvMedia::handleUpload($request->images, $folder->id ?? 0);

            if ($image_link['error'] != false) {
                return redirect()->route('jobsPartTimeFE.edit', ['id' => $id])->with('err', __('controller.save_failed'));
            }

            $request->merge(['images' => $image_link['data']->url]);

            //---------- xóa hình cũ ------------
            $file = MediaFile::where('url', $jobs->images)->first();
            if ($file) {
                $file->forceDelete();
            }

            $uploadManager = new UploadsManager;
            $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $jobs->images);
            $uploadManager->deleteFile($path, 1);
            //---------- ------------------

        }
        //delete old file
        $file_delete = $jobs->file_upload;
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
        $jobs->file_upload = $file_upload;
        $jobs = $jobs->update($request->input());
        $this->deleteFilePreview();
        event(new CreatedContentEvent(JOBS_PART_TIME_MODULE_SCREEN_NAME, $request, $jobs));

        return redirect()->route('life.part_time_jobs_list')->with('success', __('controller.update_successful',['module'=>__('life.part-time_job')]));

    }

    public function delete(Request $request)
    {
        if(hasPermission('memberFE.isAdmin')) {
            $jobs = JobsPartTime::where('id',$request->input('id'))
            ->rejectcategories()
            ->firstOrFail();

        } else {
            $jobs = JobsPartTime::where('id',$request->input('id'))->where('member_id', auth()->guard('member')->user()->id)
            ->rejectcategories()
            ->firstOrFail();
        }

        $parent = MediaFolder::where('slug', 'part-time-jobs-fe')->first();
        $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id',$parent->id)->first();
        if ($folder) {
            $directory =  str_replace( basename($jobs->images),'',$jobs->images );
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
            $jobs->delete();

            event(new DeletedContentEvent(JOBS_PART_TIME_MODULE_SCREEN_NAME, $request, $jobs));

            return redirect()->route('life.part_time_jobs_list')->with('success', __('controller.delete_successful',['module'=>__('life.part-time_job')]));

        } catch (Exception $exception) {
            return redirect()->route('life.part_time_jobs_list')->with('success', __('controller.delete_failed'));
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
        $request->merge(['categories' => $request->input('categories')]);
        $request->validate([
            'title' => 'required|max:120',
            'contact' => 'required',
            'detail' => 'required',
            'status' => 'required',
            'policy_confirm' => 'required',
            'images' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb

        ]);

        $jobs = new JobsPartTime;
        $jobs->title = $request->title;
        $jobs->contact = $request->contact;
        $jobs->detail = $request->detail;
        $jobs->status = $request->status;
        $jobs->images = $request->base64Image;
        $jobs->categories = $request->categories;
        $jobs->link = $request->link;
        $jobs->pay = $request->pay;
        $jobs->location = $request->location;
        $jobs->period = $request->period;
        $jobs->day = $request->day;
        $jobs->time = $request->time;
        $jobs->resume = $request->resume;
        $jobs->working_period = $request->working_period;
        $jobs->applying_period = $request->applying_period;
        $jobs->open_position = $request->open_position;
        $jobs->exact_location = $request->exact_location;
        $jobs->prefer_requirements = $request->prefer_requirements;

        $parent = MediaFolder::where('slug', 'part-time-jobs-fe')->first();
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
            if(hasPermission('memberFE.isAdmin')) {
                $preview_item = JobsPartTime::findOrFail($request->idPreview);
            } else {
                $preview_item = JobsPartTime::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idPreview);
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

        $jobs->file_upload = $file_upload;

        Theme::breadcrumb()->add(__('life.part-time_job'), route('life.part_time_jobs_list'))
//            ->add($jobs->title, 'http:...')
        ;

        Theme::setTitle(__('life').' | '.__('life.part-time_job').' | ' . $jobs->title);

        return Theme::scope('life.jobs.preview', ['jobs' => $jobs])->render();
    }

    function deleteFilePreview()
    {
        $parent = MediaFolder::where('slug', 'part-time-jobs-fe')->first();
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

        $sympathy = JobsPartTime::rejectcategories()->findOrFail($id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_jobs_part_time.member_id',$user->id);
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
                    sympathyCommentDetail("part-time-job",$id,$reason,"dislike");
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnPost("part-time-job",$id,"dislike");
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
                sympathyCommentDetail("part-time-job",$id,$reason,"dislike");
            }
        }
        $sympathy = JobsPartTime::rejectcategories()->withCount([
            'dislikes',
        ])
        ->withCount([
            'likes',
        ])
        ->rejectcategories()
        ->findOrFail($id);

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

        $sympathy = JobsPartTime::rejectcategories()->findOrFail($id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_jobs_part_time.member_id',$user->id);
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
                    sympathyCommentDetail("part-time-job",$id,$reason,"like");
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnPost("part-time-job",$id,"like");
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
                sympathyCommentDetail("part-time-job",$id,$reason,"like");
            }
        }
        $sympathy = JobsPartTime::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])
        ->rejectcategories()
        ->findOrFail($id);
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
        $sympathy = JobsComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_jobs_comments.member_id', $user->id);
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
                    sympathyCommentDetail("part-time-job", $post_id,$reason,"dislike",$comment_id);
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnComment("part-time-job",$comment_id,"dislike");
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
                sympathyCommentDetail("part-time-job", $post_id,$reason,"dislike",$comment_id);
            }
        }
        $sympathy = JobsComments::withCount([
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
        $reason = $request->reason;
        $sympathy = JobsComments::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_jobs_comments.member_id', $user->id);
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
                    sympathyCommentDetail("part-time-job", $post_id,$reason,"like",$comment_id);
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnComment("part-time-job",$comment_id,"like");
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
                sympathyCommentDetail("part-time-job", $post_id,$reason,"like",$comment_id);
            }
        }
        $sympathy = JobsComments::withCount([
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

        $jobOwnerID = JobsPartTime::find($id)->member_id;
        $currentUserID = auth()->guard('member')->user()->id;
        $allow = 1;

        if($jobOwnerID == $currentUserID){
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
        $commentOwnerID = JobsComments::find($commentId)->member_id;
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
