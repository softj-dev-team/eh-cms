<?php

namespace Botble\Events\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Events\Http\Requests\CommentsRequest;
use Botble\Events\Repositories\Interfaces\CommentsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Events\Tables\CommentsTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Events\Forms\CommentsForm;
use Botble\Base\Forms\FormBuilder;
use Carbon\Carbon;
use Botble\Events\Models\Comments;
use Illuminate\Support\Facades\Auth;
use Botble\Base\Facades\PageTitleFacade;

class CommentsController extends BaseController
{
    /**
     * @var CommentsInterface
     */
    protected $commentsRepository;

    /**
     * EventsController constructor.
     * @param EventsInterface $eventsRepository
     * @author Sang Nguyen
     */
    public function __construct(CommentsInterface $commentsRepository)
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
    public function getList(CommentsTable $table,$id)
    {
        page_title()->setTitle('Events / Comments / List');

        $data =  [];
        $data['id'] = $id;
        return $table->renderTable($data );
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder,$id)
    {

        page_title()->setTitle('Events / Comments / Create');


        return $formBuilder->create(CommentsForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CommentsRequest $request, BaseHttpResponse $response,$id)
    {
        $request->merge(['event_id' => $id, 'parents_id' => null]);
        $comments = $this->commentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COMMENTS_MODULE_SCREEN_NAME, $request, $comments));

        return $response
            ->setPreviousUrl(route('events.comments.list',['id'=>$id]))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function getDelete(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $comments = $this->commentsRepository->findOrFail($id);

            $this->commentsRepository->delete($comments);

            event(new DeletedContentEvent(COMMENTS_MODULE_SCREEN_NAME, $request, $comments));

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
            event(new DeletedContentEvent(COMMENTS_MODULE_SCREEN_NAME, $request, $comments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
