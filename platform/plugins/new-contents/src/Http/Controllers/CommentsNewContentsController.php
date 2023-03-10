<?php

namespace Botble\NewContents\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\NewContents\Http\Requests\NewContentsRequest;
use Botble\NewContents\Repositories\Interfaces\NewContentsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\NewContents\Tables\NewContentsTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\NewContents\Forms\NewContentsForm;
use Botble\Base\Forms\FormBuilder;
use Botble\NewContents\Forms\CommentsNewContentsForm;
use Botble\NewContents\Http\Requests\CommentsNewContentsRequest;
use Botble\NewContents\Repositories\Interfaces\CommentsNewContentsInterface;
use Botble\NewContents\Tables\CommentsNewContentsTable;

class CommentsNewContentsController extends BaseController
{
    /**
     * @var NewContentsInterface
     */
    protected$commentsNewContentsRepository;

    /**
     * NewContentsController constructor.
     * @param NewContentsInterface $commentsNewContentsRepository
     * @author Sang Nguyen
     */
    public function __construct(CommentsNewContentsInterface $commentsNewContentsRepository)
    {
        $this->commentsNewContentsRepository =$commentsNewContentsRepository;
    }

    /**
     * Display all new_contents
     * @param NewContentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CommentsNewContentsTable $table,$id)
    {

        page_title()->setTitle('New Contents #'.$id.' / Comments / List');
        $data = [];
        $data['id'] = $id;

        return $table->renderTable($data);
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder,$id)
    {
        page_title()->setTitle('New Contents #'.$id.' / Comments / Create');

        return $formBuilder->create(CommentsNewContentsForm::class)->renderForm();
    }

    /**
     * Insert new NewContents into database
     *
     * @param CommentsNewContentsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CommentsNewContentsRequest $request, BaseHttpResponse $response,$id)
    {
        $request->merge(['new_contents_id' => $id, 'parents_id' => null]);
        $comments_new_contents = $this->commentsNewContentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COMMENTS_NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $comments_new_contents));

        return $response
            ->setPreviousUrl(route('new_contents.comments.list',['id'=>$id]))
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
            $new_contents = $this->commentsNewContentsRepository->findOrFail($id);

            $this->commentsNewContentsRepository->delete($new_contents);

            event(new DeletedContentEvent(COMMENTS_NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $new_contents));

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
            $new_contents = $this->commentsNewContentsRepository->findOrFail($id);
            $this->commentsNewContentsRepository->delete($new_contents);
            event(new DeletedContentEvent(COMMENTS_NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $new_contents));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
