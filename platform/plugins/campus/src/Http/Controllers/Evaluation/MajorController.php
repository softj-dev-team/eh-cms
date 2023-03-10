<?php

namespace Botble\Campus\Http\Controllers\Evaluation;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Campus\Http\Requests\CampusRequest;
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
use Botble\Campus\Forms\Evaluation\MajorForm;
use Botble\Campus\Repositories\Interfaces\Evaluation\MajorInterface;
use Botble\Campus\Tables\Evaluation\MajorTable;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

class MajorController extends BaseController
{
    /**
     * @var MajorInterface
     */
    protected $majorRepository;

    /**
     * CampusController constructor.
     * @param MajorInterface $majorRepository
     * @author Sang Nguyen
     */
    public function __construct(MajorInterface $majorRepository)
    {
        $this->majorRepository = $majorRepository;
    }

    /**
     * Display all campuses
     * @param CampusTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(MajorTable $table)
    {

        page_title()->setTitle('카테고리 목록');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle('캠퍼스 / 평가 / 새로운 카테고리');

        return $formBuilder->create(MajorForm::class)->renderForm();
    }

    /**
     * Insert new Campus into database
     *
     * @param CampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CampusRequest $request, BaseHttpResponse $response)
    {
        $major = $this->majorRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(MAJOR_MODULE_SCREEN_NAME, $request, $major));

        return $response
            ->setPreviousUrl(route('campus.evaluation.major.list'))
            ->setNextUrl(route('campus.evaluation.major.edit', $major->id))
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

        $major = $this->majorRepository->findOrFail($id);

        event(new BeforeEditContentEvent(MAJOR_MODULE_SCREEN_NAME, $request, $major));

        page_title()->setTitle('캠퍼스 / 평가 / 카테고리 편집' . ' #' . $id);

        return $formBuilder->create(MajorForm::class, ['model' => $major])->renderForm();
    }

    /**
     * @param $id
     * @param CampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, CampusRequest $request, BaseHttpResponse $response)
    {
        $major = $this->majorRepository->findOrFail($id);

        $major->fill($request->input());

        $this->majorRepository->createOrUpdate($major);

        event(new UpdatedContentEvent(MAJOR_MODULE_SCREEN_NAME, $request, $major));

        return $response
            ->setPreviousUrl(route('campus.evaluation.major.list'))
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
            $major = $this->majorRepository->findOrFail($id);

            $this->majorRepository->delete($major);

            event(new DeletedContentEvent(MAJOR_MODULE_SCREEN_NAME, $request, $major));

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
            $major = $this->majorRepository->findOrFail($id);
            $this->majorRepository->delete($major);
            event(new DeletedContentEvent(MAJOR_MODULE_SCREEN_NAME, $request, $major));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
