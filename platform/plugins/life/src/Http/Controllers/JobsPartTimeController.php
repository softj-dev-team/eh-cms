<?php

namespace Botble\Life\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Life\Repositories\Interfaces\LifeInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Life\Tables\LifeTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Life\Forms\Jobs\JobsPartTimeForm;
use Botble\Life\Http\Requests\Jobs\JobsPartTimeRequest;
use Botble\Life\Repositories\Interfaces\Jobs\JobsPartTimeInterface;
use Botble\Life\Tables\Jobs\JobsPartTimeTable;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;

class JobsPartTimeController extends BaseController
{
    /**
     * @var JobsPartTimeInterface
     */
    protected $jobsPartTimeRepository;
    /**
     * @var MediaFileInterface
     */
    protected $fileRepository;
    /**
     * @var MediaFolderInterface
     */
    protected $folderRepository;

    /**
     * LifeController constructor.
     * @param LifeInterface $flareRepository
     * @author Sang Nguyen
     */
    public function __construct(JobsPartTimeInterface $jobsPartTimeRepository, MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository)
    {
        $this->jobsPartTimeRepository = $jobsPartTimeRepository;
        $this->folderRepository = $folderRepository;
        $this->fileRepository =  $fileRepository;
    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(JobsPartTimeTable $table)
    {

        page_title()->setTitle('목록');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle("새로운 직업");

        return $formBuilder->create(JobsPartTimeForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param JobsPartTimeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(JobsPartTimeRequest $request, BaseHttpResponse $response)
    {

        if($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $jobsPartTime = $this->jobsPartTimeRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(JOBS_PART_TIME_MODULE_SCREEN_NAME, $request, $jobsPartTime));

        return $response
            ->setPreviousUrl(route('life.jobs_part_time.list'))
            ->setNextUrl(route('life.jobs_part_time.edit', $jobsPartTime->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * Show edit form
     *
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getEdit($id, FormBuilder $formBuilder, Request $request)
    {
        $jobsPartTime = $this->jobsPartTimeRepository->findOrFail($id);

        event(new BeforeEditContentEvent(JOBS_PART_TIME_MODULE_SCREEN_NAME, $request, $jobsPartTime));

        page_title()->setTitle("수정" . ' #' . $id);

        return $formBuilder->create(JobsPartTimeForm::class, ['model' => $jobsPartTime])->renderForm();
    }

    /**
     * @param $id
     * @param JobsPartTimeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, JobsPartTimeRequest $request, BaseHttpResponse $response)
    {

        $jobsPartTime = $this->jobsPartTimeRepository->findOrFail($id);
        if($jobsPartTime->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);
        $jobsPartTime->fill($request->input());

        $this->jobsPartTimeRepository->createOrUpdate($jobsPartTime);

        event(new UpdatedContentEvent(JOBS_PART_TIME_MODULE_SCREEN_NAME, $request, $jobsPartTime));

        return $response
            ->setPreviousUrl(route('life.jobs_part_time.list'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function getDelete(Request $request, $id, BaseHttpResponse $response)
    {
        $parent = MediaFolder::where('slug', 'part-time-jobs-fe')->first();
        $folder = MediaFolder::where('slug', $id)->where('parent_id', $parent->id)->first();
        if ($folder) {
            $directory = config('media.driver.' . config('filesystems.default') . '.path') . $this->folderRepository->getFullPath($folder->id);
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
                    return $response
                        ->setError()
                        ->setMessage(trans('core/base::notices.cannot_delete'));
                }
            }
            // xóa trong database media
            $this->fileRepository->deleteBy(['folder_id' => $folder->id]);
            $this->folderRepository->deleteFolder($folder->id, true);

        }

        try {
            $jobsPartTime = $this->jobsPartTimeRepository->findOrFail($id);

            $this->jobsPartTimeRepository->delete($jobsPartTime);

            event(new DeletedContentEvent(JOBS_PART_TIME_MODULE_SCREEN_NAME, $request, $jobsPartTime));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     * @throws Exception
     */
    public function postDeleteMany(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $parent = MediaFolder::where('slug', 'part-time-jobs-fe')->first();
            $folder = MediaFolder::where('slug', $id)->where('parent_id', $parent->id)->first();
            if ($folder) {
                $directory = config('media.driver.' . config('filesystems.default') . '.path') . $this->folderRepository->getFullPath($folder->id);
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
                        return $response
                            ->setError()
                            ->setMessage(trans('core/base::notices.cannot_delete'));
                    }
                }
                // xóa trong database media
                $this->fileRepository->deleteBy(['folder_id' => $folder->id]);
                $this->folderRepository->deleteFolder($folder->id, true);
            }
            $jobsPartTime = $this->jobsPartTimeRepository->findOrFail($id);
            $this->jobsPartTimeRepository->delete($jobsPartTime);
            event(new DeletedContentEvent(JOBS_PART_TIME_MODULE_SCREEN_NAME, $request, $jobsPartTime));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
