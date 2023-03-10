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
use Botble\Life\Forms\Ads\AdsForm;
use Botble\Life\Http\Requests\Ads\AdsRequest;
use Botble\Life\Http\Requests\Jobs\JobsPartTimeRequest;
use Botble\Life\Repositories\Interfaces\Ads\AdsInterface;
use Botble\Life\Tables\Ads\AdsTable;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Illuminate\Filesystem\Filesystem;
use Carbon\Carbon;

class AdsController extends BaseController
{
    /**
     * @var AdsInterface
     */
    protected $adsRepository;
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
    public function __construct(AdsInterface $adsRepository, MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository) {
        $this->adsRepository = $adsRepository;
        $this->folderRepository = $folderRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     * @author Sang Nguyen
     */
    public function getList(AdsTable $table) {

        page_title()->setTitle('목록');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder) {
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/run-datetime.js']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/life/css/datetimepicker.css']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/jscolor.js']);
        page_title()->setTitle("생성");

        return $formBuilder->create(AdsForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param JobsPartTimeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(AdsRequest $request, BaseHttpResponse $response) {

        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
        $deadline = Carbon::createFromFormat('Y/m/d h:i a', $request->input('deadline'))->format('Y-m-d H:i:s');

        $link = json_encode($request->input('link'));
        $file_upload = json_encode($request->input('file_upload'));

        if (is_null($request->input('is_deadline'))) {
            $request->merge(['is_deadline' => 0]);
        }
        $request->merge(['link' => $link]);
        $request->merge(['file_upload' => $file_upload]);
        $request->merge(['start' => $start]);
        $request->merge(['deadline' => $deadline]);
        $request->validate([
            'title' => 'required|max:120',
            'deadline' => 'required',
            'details' => 'required',
            'status' => 'required',

        ]);
        if ($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['categories' => $request->input('categories')]);
        $ads = $this->adsRepository->createOrUpdate($request->input());
        event(new CreatedContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));

        return $response
            ->setPreviousUrl(route('life.advertisements.list'))
            ->setNextUrl(route('life.advertisements.edit', $ads->id))
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
    public function getEdit($id, FormBuilder $formBuilder, Request $request) {
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/run-datetime.js']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/life/css/datetimepicker.css']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/jscolor.js']);

        $ads = $this->adsRepository->findOrFail($id);

        if ($ads->start) {
            $ads->start = Carbon::createFromFormat('Y-m-d H:i:s', $ads->start)->format('Y/m/d h:i a');
        }

        if ($ads->deadline) {
            $ads->deadline = Carbon::createFromFormat('Y-m-d H:i:s', $ads->deadline)->format('Y/m/d h:i a');
        }

        event(new BeforeEditContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));

        page_title()->setTitle("수정" . ' #' . $id);

        return $formBuilder->create(AdsForm::class, ['model' => $ads])->renderForm();
    }

    /**
     * @param $id
     * @param JobsPartTimeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, AdsRequest $request, BaseHttpResponse $response) {
        $ads = $this->adsRepository->findOrFail($id);

        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
        $deadline = Carbon::createFromFormat('Y/m/d h:i a', $request->input('deadline'))->format('Y-m-d H:i:s');

        $link = json_encode($request->input('link'));
        $file_upload = json_encode($request->input('file_upload'));

        if (is_null($request->input('is_deadline'))) {
            $request->merge(['is_deadline' => 0]);
        }

        $request->merge(['link' => $link]);
        $request->merge(['file_upload' => $file_upload]);
        $request->merge(['start' => $start]);
        $request->merge(['deadline' => $deadline]);
        $request->validate([
            'title' => 'required|max:120',
            'deadline' => 'required',
            'details' => 'required',
            'status' => 'required',

        ]);
        if ($ads->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['categories' => $request->input('categories')]);

        $ads->fill($request->input());

        $this->adsRepository->createOrUpdate($ads);

        event(new UpdatedContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));

        return $response
            ->setPreviousUrl(route('life.advertisements.list'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function getDelete(Request $request, $id, BaseHttpResponse $response) {
        $parent = MediaFolder::where('slug', 'ads-fe')->first();
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
            $ads = $this->adsRepository->findOrFail($id);
            $this->adsRepository->delete($ads);

            event(new DeletedContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));

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
     * @throws Exception
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request, BaseHttpResponse $response) {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $parent = MediaFolder::where('slug', 'ads-fe')->first();
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
            $ads = $this->adsRepository->findOrFail($id);
            $this->adsRepository->delete($ads);
            event(new DeletedContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
