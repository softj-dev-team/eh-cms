<?php

namespace Botble\Events\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Events\Forms\CommentsEventsCmtForm;
use Botble\Events\Http\Requests\CommentsEventsCmtRequest;
use Botble\Events\Models\Comments;
use Botble\Events\Models\CommentsEventsCmt;
use Botble\Events\Repositories\Interfaces\CommentsEventsCmtInterface;
use Botble\Events\Tables\CommentsEventsCmtTable;
use Botble\Events\Tables\CommentsTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsEventsCmtController extends BaseController
{
    /**
     * @var CommentsEventsCmtInterface
     */
    protected $commentsRepository;

    /**
     * EventsController constructor.
     * @param EventsInterface $eventsRepository
     * @author Sang Nguyen
     */
    public function __construct(CommentsEventsCmtInterface $commentsRepository)
    {
        $this->commentsRepository = $commentsRepository;

    }

    /**
     * Display all events
     * @param CommentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CommentsEventsCmtTable $table, $id)
    {
        page_title()->setTitle('Events Cmt / Comments / List');

        $data = [];
        $data['id'] = $id;
        return $table->renderTable($data);
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder, $id)
    {

        page_title()->setTitle('Events Cmt / Comments / Create');

        return $formBuilder->create(CommentsEventsCmtForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CommentsEventsCmtRequest $request, BaseHttpResponse $response, $id)
    {
        $request->merge(['events_cmt_id' => $id, 'parents_id' => null]);
        $comments = $this->commentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COMMENTS_EVENTS_CMT_MODULE_SCREEN_NAME, $request, $comments));

        return $response
            ->setPreviousUrl(route('events.cmt.comments.list', ['id' => $id]))
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
    // public function getEdit($id, FormBuilder $formBuilder, Request $request)
    // {
    //     $events = $this->eventsRepository->findOrFail($id);
    //     //dd( date('dd/mm/yyyy',  $events->start));
    //    // $events->start = date('Y-m-d\TH:i:sP',  $events->start);
    //    // dd( $events->start);
    //    $events->start=  strftime('%Y-%m-%dT%H:%M:%S', strtotime(  $events->start));
    //    $events->end=  strftime('%Y-%m-%dT%H:%M:%S', strtotime(  $events->end));
    //     event(new BeforeEditContentEvent(EVENTS_MODULE_SCREEN_NAME, $request, $events));

    //     page_title()->setTitle(trans('plugins/events::events.edit') . ' #' . $id);

    //     return $formBuilder->create(EventsForm::class, ['model' => $events])->renderForm();
    // }

    /**
     * @param $id
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    // public function postEdit($id, EventsRequest $request, BaseHttpResponse $response)
    // {
    //     $events = $this->eventsRepository->findOrFail($id);

    //     $events->fill($request->input());

    //     $this->eventsRepository->createOrUpdate($events);

    //     event(new UpdatedContentEvent(EVENTS_MODULE_SCREEN_NAME, $request, $events));

    //     return $response
    //         ->setPreviousUrl(route('events.list'))
    //         ->setMessage(trans('core/base::notices.update_success_message'));
    // }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function getDelete(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $comments = $this->commentsRepository->findOrFail($id);

            $this->commentsRepository->delete($comments);

            event(new DeletedContentEvent(COMMENTS_EVENTS_CMT_MODULE_SCREEN_NAME, $request, $comments));

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
            $comments = $this->commentsRepository->findOrFail($id);
            $this->commentsRepository->delete($comments);
            event(new DeletedContentEvent(COMMENTS_EVENTS_CMT_MODULE_SCREEN_NAME, $request, $comments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
