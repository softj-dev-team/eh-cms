<?php

namespace Botble\Campus\Http\Controllers\Genealogy;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Campus\Http\Requests\CampusRequest;
use Botble\Campus\Repositories\Interfaces\CampusInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Campus\Tables\CampusTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\CampusForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Campus\Forms\Genealogy\GenealogyCommentsForm;
use Botble\Campus\Forms\StudyRoom\StudyRoomCommentsForm;
use Botble\Campus\Http\Requests\Genealogy\GenealogyCommentsRequest;
use Botble\Campus\Http\Requests\StudyRoom\StudyRoomCommentsRequest;
use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyCommentsInterface;
use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomCommentsInterface;
use Botble\Campus\Tables\Genealogy\GenealogyCommentsTable;
use Botble\Campus\Tables\StudyRoom\StudyRoomCommentsTable;
use Illuminate\Support\Facades\Auth;

class GenealogyCommentsController extends BaseController
{
     /**
     * @var GenealogyCommentsInterface
     */
    protected $genelogyCommentsRepository;

    /**
     * EventsController constructor.
     * @param GenealogyCommentsInterface $jobsCommentsRepository
     * @author Sang Nguyen
     */
    public function __construct(GenealogyCommentsInterface $genelogyCommentsRepository)
    {
        $this->genelogyCommentsRepository = $genelogyCommentsRepository;
    }

    /**
     * Display all events
     * @param ShelterCommentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(GenealogyCommentsTable $table, $id)
    {
        page_title()->setTitle('캠퍼스 / 계보 / 댓글 #'.$id);

        $data =  [];
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
        page_title()->setTitle('캠퍼스 / 스터디룸 / 댓글 #'.$id.' / 만들다');


        return $formBuilder->create(GenealogyCommentsForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(GenealogyCommentsRequest $request, BaseHttpResponse $response, $id)
    {

        $request->merge(['genealogy_id' => $id, 'parents_id' => null]);

        $genealogyComments = $this->genelogyCommentsRepository->createOrUpdate($request->input());


        event(new CreatedContentEvent(GENEALOGY_COMMENTS_MODULE_SCREEN_NAME, $request, $genealogyComments));

        return $response
            ->setPreviousUrl(route('campus.genealogy.comments.list', ['id' => $id]))
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
            $genealogyComments = $this->genelogyCommentsRepository->findOrFail($id);

            $this->genelogyCommentsRepository->delete($genealogyComments);

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
            $genealogyComments = $this->genelogyCommentsRepository->findOrFail($id);
            $this->genelogyCommentsRepository->delete($genealogyComments);
            event(new DeletedContentEvent(GENEALOGY_COMMENTS_MODULE_SCREEN_NAME, $request, $genealogyComments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
