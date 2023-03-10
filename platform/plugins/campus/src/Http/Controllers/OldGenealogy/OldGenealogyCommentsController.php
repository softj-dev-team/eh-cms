<?php

namespace Botble\Campus\Http\Controllers\OldGenealogy;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\Genealogy\GenealogyCommentsForm;
use Botble\Campus\Forms\OldGenealogy\OldGenealogyCommentsForm;
use Botble\Campus\Http\Requests\Genealogy\GenealogyCommentsRequest;
use Botble\Campus\Http\Requests\OldGenealogy\OldGenealogyCommentsRequest;
use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyCommentsInterface;
use Botble\Campus\Repositories\Interfaces\OldGenealogy\OldGenealogyCommentsInterface;
use Botble\Campus\Tables\OldGenealogy\OldGenealogyCommentsTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OldGenealogyCommentsController extends BaseController
{
    /**
     * @var GenealogyCommentsInterface
     */
    protected $oldGenelogyCommentsRepository;

    /**
     * EventsController constructor.
     * @param GenealogyCommentsInterface $jobsCommentsRepository
     * @author Sang Nguyen
     */
    public function __construct(OldGenealogyCommentsInterface $oldGenelogyCommentsRepository)
    {
        $this->oldGenelogyCommentsRepository = $oldGenelogyCommentsRepository;
    }

    /**
     * Display all events
     * @param ShelterCommentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(OldGenealogyCommentsTable $table, $id)
    {
        page_title()->setTitle('캠퍼스 / 구계보 / 댓글 #' . $id);

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
        page_title()->setTitle('캠퍼스 / 구계보 / 댓글 #' . $id . ' / 만들다');

        return $formBuilder->create(OldGenealogyCommentsForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(OldGenealogyCommentsRequest $request, BaseHttpResponse $response, $id)
    {

        $request->merge(['old_genealogy_id' => $id, 'parents_id' => null]);

        $oldGenealogyComments = $this->oldGenelogyCommentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(OLD_GENEALOGY_COMMENTS_MODULE_SCREEN_NAME, $request, $oldGenealogyComments));

        return $response
            ->setPreviousUrl(route('campus.old.genealogy.comments.list', ['id' => $id]))
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
            $oldGenealogyComments = $this->oldGenelogyCommentsRepository->findOrFail($id);

            $this->oldGenelogyCommentsRepository->delete($oldGenealogyComments);

            event(new DeletedContentEvent(OLD_GENEALOGY_COMMENTS_MODULE_SCREEN_NAME, $request, $oldGenealogyComments));

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
            $oldGenealogyComments = $this->oldGenelogyCommentsRepository->findOrFail($id);
            $this->oldGenelogyCommentsRepository->delete($oldGenealogyComments);
            event(new DeletedContentEvent(OLD_GENEALOGY_COMMENTS_MODULE_SCREEN_NAME, $request, $oldGenealogyComments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
