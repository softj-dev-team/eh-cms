<?php

namespace Botble\Events\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Events\Http\Requests\CategoryEventsRequest;
use Botble\Events\Repositories\Interfaces\CategoryEventsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Events\Tables\CategoryEventsTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Events\Forms\EventsForm;
use Botble\Base\Forms\FormBuilder;
use Carbon\Carbon;
use Botble\Events\Forms\CategoryEventsForm;

class CategoryEventsController extends BaseController
{
    /**
     * @var CategoryEventsInterface
     */
    protected $categoryEventsRepository;

    /**
     * EventsController constructor.
     * @param CategoryEventsInterface $eventsRepository
     * @author Sang Nguyen
     */
    public function __construct(CategoryEventsInterface $categoryEventsRepository)
    {
        $this->categoryEventsRepository = $categoryEventsRepository;
    }

    /**
     * Display all events
     * @param EventsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CategoryEventsTable $table)
    {
 
        page_title()->setTitle('Category');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        
        page_title()->setTitle(trans('plugins/events::events.createCategory'));

        return $formBuilder->create(CategoryEventsForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CategoryEventsRequest $request, BaseHttpResponse $response)
    {
        $categoryEvents = $this->categoryEventsRepository->createOrUpdate($request->input());
    
        event(new CreatedContentEvent(CATEGORY_EVENTS_MODULE_SCREEN_NAME, $request, $categoryEvents));

        return $response
            ->setPreviousUrl(route('events.category.list'))
            ->setNextUrl(route('events.category.edit', $categoryEvents->id))
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
        $categoryEvents = $this->categoryEventsRepository->findOrFail($id);
  
       
        event(new BeforeEditContentEvent(CATEGORY_EVENTS_MODULE_SCREEN_NAME, $request, $categoryEvents));

        page_title()->setTitle(trans('plugins/events::events.editCategory') . ' #' . $id);

        return $formBuilder->create(CategoryEventsForm::class, ['model' => $categoryEvents])->renderForm();
    }

    /**
     * @param $id
     * @param CategoryEventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, CategoryEventsRequest $request, BaseHttpResponse $response)
    {
        $categoryEvents = $this->categoryEventsRepository->findOrFail($id);

        $categoryEvents->fill($request->input());

        $this->categoryEventsRepository->createOrUpdate($categoryEvents);

        event(new UpdatedContentEvent(CATEGORY_EVENTS_MODULE_SCREEN_NAME, $request, $categoryEvents));

        return $response
            ->setPreviousUrl(route('events.category.list'))
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
            $categoryEvents = $this->categoryEventsRepository->findOrFail($id);

            $this->categoryEventsRepository->delete($categoryEvents);

            event(new DeletedContentEvent(CATEGORY_EVENTS_MODULE_SCREEN_NAME, $request, $categoryEvents));

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
            $categoryEvents = $this->categoryEventsRepository->findOrFail($id);
            $this->categoryEventsRepository->delete($categoryEvents);
            event(new DeletedContentEvent(CATEGORY_EVENTS_MODULE_SCREEN_NAME, $request, $categoryEvents));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
