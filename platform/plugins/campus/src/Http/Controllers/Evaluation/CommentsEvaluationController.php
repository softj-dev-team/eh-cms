<?php

namespace Botble\Campus\Http\Controllers\Evaluation;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\Evaluation\CommentsEvaluationForm;
use Botble\Campus\Forms\Genealogy\GenealogyCommentsForm;
use Botble\Campus\Http\Requests\Evaluation\CommentsEvaluationRequest;
use Botble\Campus\Http\Requests\Genealogy\GenealogyCommentsRequest;
use Botble\Campus\Repositories\Interfaces\Evaluation\CommentsEvaluationInterface;
use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyCommentsInterface;
use Botble\Campus\Tables\Evaluation\CommentsEvaluationTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsEvaluationController extends BaseController
{
    /**
     * @var GenealogyCommentsInterface
     */
    protected $commentsEvaluationRepository;

    /**
     * EventsController constructor.
     * @param CommentsEvaluationInterface $commentsEvaluationRepository
     * @author Sang Nguyen
     */
    public function __construct(CommentsEvaluationInterface $commentsEvaluationRepository)
    {
        $this->commentsEvaluationRepository = $commentsEvaluationRepository;
    }

    /**
     * Display all events
     * @param ShelterCommentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CommentsEvaluationTable $table, $id)
    {
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/jquery.rateyo.css']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/jquery.rateyo.js']);

        page_title()->setTitle('캠퍼스 / 평가 / 의견 #' . $id);

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
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/jquery.rateyo.css']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/jquery.rateyo.js']);

        page_title()->setTitle('캠퍼스 / 평가 / 의견 #' . $id . ' / 만들다');

        return $formBuilder->create(CommentsEvaluationForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CommentsEvaluationRequest $request, BaseHttpResponse $response, $id)
    {
        $request->merge(['evaluation_id' => $id]);
        $request->merge(['textbook' => json_encode($request->textbook)]);
        $request->merge(['type' => json_encode($request->type)]);

        $commentsEvaluation = $this->commentsEvaluationRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COMMENTS_EVALUATION_MODULE_SCREEN_NAME, $request, $commentsEvaluation));

        return $response
            ->setPreviousUrl(route('campus.evaluation.comments.list', ['id' => $id]))
            ->setNextUrl(route('campus.evaluation.comments.edit',['id' => $commentsEvaluation->id] ))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function getEdit($id, FormBuilder $formBuilder, Request $request)
    {
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/jquery.rateyo.css']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/jquery.rateyo.js']);

        page_title()->setTitle('캠퍼스 / 평가 / 의견 #' . $id . ' / 만들다');

        $commentsEvaluation = $this->commentsEvaluationRepository->findOrFail($id);

        event(new BeforeEditContentEvent(COMMENTS_EVALUATION_MODULE_SCREEN_NAME, $request, $commentsEvaluation));

        return $formBuilder->create(CommentsEvaluationForm::class, ['model' => $commentsEvaluation])->renderForm();
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
            $genealogyComments = $this->commentsEvaluationRepository->findOrFail($id);

            $this->commentsEvaluationRepository->delete($genealogyComments);

            event(new DeletedContentEvent(GENEALOGY_COMMENTS_MODULE_SCREEN_NAME, $request, $genealogyComments));

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
            $genealogyComments = $this->commentsEvaluationRepository->findOrFail($id);
            $this->commentsEvaluationRepository->delete($genealogyComments);
            event(new DeletedContentEvent(GENEALOGY_COMMENTS_MODULE_SCREEN_NAME, $request, $genealogyComments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
