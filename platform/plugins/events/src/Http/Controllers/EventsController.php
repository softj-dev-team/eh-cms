<?php

namespace Botble\Events\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Events\Http\Requests\EventsRequest;
use Botble\Events\Repositories\Interfaces\EventsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Events\Tables\EventsTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Events\Forms\EventsForm;
use Botble\Base\Forms\FormBuilder;
use Carbon\Carbon;
use Botble\Events\Repositories\Interfaces\CategoryEventsInterface;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Botble\Media\Services\UploadsManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

class EventsController extends BaseController
{
    /**
     * @var EventsInterface
     */
    protected $eventsRepository;

    /**
     * @var CategoryEventsInterface
     */
    protected $categoryEventsRepository;

    /**
     * @var MediaFolderInterface
     */
    protected $folderRepository;

    /**
     * @var MediaFileInterface
     */
    protected $fileRepository;


    /**
     * EventsController constructor.
     * @param EventsInterface $eventsRepository
     * @author Sang Nguyen
     */
    public function __construct(EventsInterface $eventsRepository, CategoryEventsInterface $categoryEventsRepository, MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository)
    {
        $this->eventsRepository = $eventsRepository;
        $this->categoryEventsRepository = $categoryEventsRepository;
        $this->folderRepository = $folderRepository;
        $this->fileRepository =  $fileRepository;
    }

    /**
     * Display all events
     * @param EventsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(EventsTable $table)
    {

        page_title()->setTitle('목록');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder, BaseHttpResponse $response)
    {
        \Assets::addScriptsDirectly(['/vendor/core/plugins/events/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/events/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/events/js/run-datetime.js']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/events/css/datetimepicker.css']);

        if ($this->categoryEventsRepository->count() > 0) {
            page_title()->setTitle(trans('plugins/events::events.create'));
            return $formBuilder->create(EventsForm::class)->renderForm();
        } else {
            return $response
                ->setNextUrl(route('events.category.list'))
                ->setError()
                ->setMessage('Please create category before create events');
        }
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(EventsRequest $request, BaseHttpResponse $response)
    {
        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
        $end = Carbon::createFromFormat('Y/m/d h:i a', $request->input('end'))->format('Y-m-d H:i:s');
        $published = Carbon::createFromFormat('Y/m/d h:i a', $request->input('published'))->format('Y-m-d H:i:s');

        $request->merge(['start' => $start]);
        $request->merge(['end' => $end]);
        $request->merge(['published' => $published]);

        $request->merge(['member_id' => 0]);
        $events = $this->eventsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(EVENTS_MODULE_SCREEN_NAME, $request, $events));

        return $response
            ->setPreviousUrl(route('events.list'))
            ->setNextUrl(route('events.edit', $events->id))
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
        \Assets::addScriptsDirectly(['/vendor/core/plugins/events/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/events/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/events/js/run-datetime.js']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/events/css/datetimepicker.css']);

        $events = $this->eventsRepository->findOrFail($id);
        $events->start = ($events->start) ? Carbon::createFromFormat('Y-m-d H:i:s', $events->start)->format('Y/m/d h:i a') : null;
        $events->end = ($events->end) ? Carbon::createFromFormat('Y-m-d H:i:s',$events->end)->format('Y/m/d h:i a') : null;

        event(new BeforeEditContentEvent(EVENTS_MODULE_SCREEN_NAME, $request, $events));

        page_title()->setTitle(trans('plugins/events::events.edit') . ' #' . $id);

        return $formBuilder->create(EventsForm::class, ['model' => $events])->renderForm();
    }

    /**
     * @param $id
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, EventsRequest $request, BaseHttpResponse $response)
    {
        $events = $this->eventsRepository->findOrFail($id);

        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
        $end = Carbon::createFromFormat('Y/m/d h:i a', $request->input('end'))->format('Y-m-d H:i:s');
        $published = Carbon::createFromFormat('Y/m/d h:i a', $request->input('published'))->format('Y-m-d H:i:s');

        $request->merge(['start' => $start]);
        $request->merge(['end' => $end]);
        $request->merge(['published' => $published]);
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);

        $events->fill($request->input());

        $this->eventsRepository->createOrUpdate($events);

        event(new UpdatedContentEvent(EVENTS_MODULE_SCREEN_NAME, $request, $events));

        return $response
            ->setPreviousUrl(route('events.list'))
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

        $parent = MediaFolder::where('slug', 'events-fe')->first();
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
            $events = $this->eventsRepository->findOrFail($id);

            $this->eventsRepository->delete($events);

            event(new DeletedContentEvent(EVENTS_MODULE_SCREEN_NAME, $request, $events));

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

            $parent = MediaFolder::where('slug', 'events-fe')->first();
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



            $events = $this->eventsRepository->findOrFail($id);
            $this->eventsRepository->delete($events);
            event(new DeletedContentEvent(EVENTS_MODULE_SCREEN_NAME, $request, $events));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
