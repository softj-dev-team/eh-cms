<?php

namespace Botble\Contents\Http\Controllers;


use Botble\Events\Http\Requests\CommentsRequest;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Contents\Forms\CommentsContentsForm;
use Botble\Contents\Http\Requests\CommentsContentsRequest;
use Botble\Contents\Models\CommentsContents;
use Illuminate\Support\Facades\Auth;

use Botble\Contents\Repositories\Interfaces\CommentsContentsInterface;
use Botble\Contents\Tables\CommentsContentsTable;

class CommentsContentsController extends BaseController
{
    /**
     * @var CommentsContentsInterface
     */
    protected $commentsContentsRepository;

    /**
     * EventsController constructor.
     * @param EventsInterface $eventsRepository
     * @author Sang Nguyen
     */
    public function __construct(CommentsContentsInterface $commentsContentsRepository)
    {
        $this->commentsContentsRepository = $commentsContentsRepository;

    }

    /**
     * Display all events
     * @param CommentsContentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CommentsContentsTable $table,$id)
    {
        page_title()->setTitle('목차 / 댓글 / 목록');

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

        page_title()->setTitle('목차 / 댓글 / 작성');


        return $formBuilder->create(CommentsContentsForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CommentsContentsRequest $request, BaseHttpResponse $response,$id)
    {
        $request->merge(['contents_id' => $id, 'parents_id' => null]);
        $commentsContents = $this->commentsContentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COMMENTS_CONTENTS_MODULE_SCREEN_NAME, $request, $commentsContents));

        return $response
            ->setPreviousUrl(route('contents.comments.list',['id'=>$id]))
            ->setMessage(trans('core/base::notices.create_success_message'));
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
            $commentsContents = $this->commentsContentsRepository->findOrFail($id);

            $this->commentsContentsRepository->delete($commentsContents);

            event(new DeletedContentEvent(COMMENTS_CONTENTS_MODULE_SCREEN_NAME, $request, $commentsContents));

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
            $commentsContents = $this->commentsContentsRepository->findOrFail($id);
            $this->commentsRepository->delete($commentsContents);
            event(new DeletedContentEvent(COMMENTS_CONTENTS_MODULE_SCREEN_NAME, $request, $commentsContents));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
