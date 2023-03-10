<?php

namespace Botble\Events\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Events\Forms\EventsCmtForm;
use Botble\Events\Forms\EventsForm;
use Botble\Events\Http\Requests\EventsCmtRequest;
use Botble\Events\Http\Requests\EventsRequest;
use Botble\Events\Repositories\Interfaces\CategoryEventsInterface;
use Botble\Events\Repositories\Interfaces\EventsCmtInterface;
use Botble\Events\Repositories\Interfaces\EventsInterface;
use Botble\Events\Tables\EventsCmtTable;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class EventsCmtController extends BaseController
{
    /**
     * @var EventsCmtInterface
     */
    protected $eventsCmtRepository;

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
     * @param EventsCmtInterface $eventsCmtRepository
     * @author Sang Nguyen
     */
    public function __construct(EventsCmtInterface $eventsCmtRepository, CategoryEventsInterface $categoryEventsRepository, MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository)
    {
        $this->eventsCmtRepository = $eventsCmtRepository;
        $this->categoryEventsRepository = $categoryEventsRepository;
        $this->folderRepository = $folderRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * Display all events
     * @param EventsCmtTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(EventsCmtTable $table)
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
            return $formBuilder->create(EventsCmtForm::class)->renderForm();
        } else {
            return $response
                ->setNextUrl(route('events.cmt.list'))
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
    public function postCreate(EventsCmtRequest $request, BaseHttpResponse $response)
    {
        if($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $events = $this->eventsCmtRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(EVENTS_CMT_MODULE_SCREEN_NAME, $request, $events));

        return $response
            ->setPreviousUrl(route('events.cmt.list'))
            ->setNextUrl(route('events.cmt.edit', $events->id))
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

        $events = $this->eventsCmtRepository->findOrFail($id);

        event(new BeforeEditContentEvent(EVENTS_CMT_MODULE_SCREEN_NAME, $request, $events));

        page_title()->setTitle(trans('plugins/events::events.edit') . ' #' . $id);

        return $formBuilder->create(EventsCmtForm::class, ['model' => $events])->renderForm();
    }

    /**
     * @param $id
     * @param EventsCmtRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, EventsCmtRequest $request, BaseHttpResponse $response)
    {
        $events = $this->eventsCmtRepository->findOrFail($id);
        if($events->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);
        $events->fill($request->input());

        $this->eventsCmtRepository->createOrUpdate($events);

        event(new UpdatedContentEvent(EVENTS_CMT_MODULE_SCREEN_NAME, $request, $events));

        return $response
            ->setPreviousUrl(route('events.cmt.list'))
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
        try {
            $events = $this->eventsCmtRepository->findOrFail($id);

            $this->eventsCmtRepository->delete($events);

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

            $events = $this->eventsCmtRepository->findOrFail($id);
            $this->eventsCmtRepository->delete($events);
            event(new DeletedContentEvent(EVENTS_CMT_MODULE_SCREEN_NAME, $request, $events));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
