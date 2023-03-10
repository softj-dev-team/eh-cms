<?php

namespace Botble\Campus\Http\Controllers;

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

class CampusController extends BaseController
{
    /**
     * @var CampusInterface
     */
    protected $campusRepository;

    /**
     * CampusController constructor.
     * @param CampusInterface $campusRepository
     * @author Sang Nguyen
     */
    public function __construct(CampusInterface $campusRepository)
    {
        $this->campusRepository = $campusRepository;
    }

    /**
     * Display all campuses
     * @param CampusTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CampusTable $table)
    {

        page_title()->setTitle(__('plugins/campus::campus.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle(__('plugins/campus::campus.create'));

        return $formBuilder->create(CampusForm::class)->renderForm();
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
        $campus = $this->campusRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(CAMPUS_MODULE_SCREEN_NAME, $request, $campus));

        return $response
            ->setPreviousUrl(route('campus.list'))
            ->setNextUrl(route('campus.edit', $campus->id))
            ->setMessage(__('core/base::notices.create_success_message'));
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
        $campus = $this->campusRepository->findOrFail($id);

        event(new BeforeEditContentEvent(CAMPUS_MODULE_SCREEN_NAME, $request, $campus));

        page_title()->setTitle(__('plugins/campus::campus.edit') . ' #' . $id);

        return $formBuilder->create(CampusForm::class, ['model' => $campus])->renderForm();
    }

    /**
     * @param $id
     * @param CampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, CampusRequest $request, BaseHttpResponse $response)
    {
        $campus = $this->campusRepository->findOrFail($id);

        $campus->fill($request->input());

        $this->campusRepository->createOrUpdate($campus);

        event(new UpdatedContentEvent(CAMPUS_MODULE_SCREEN_NAME, $request, $campus));

        return $response
            ->setPreviousUrl(route('campus.list'))
            ->setMessage(__('core/base::notices.update_success_message'));
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
            $campus = $this->campusRepository->findOrFail($id);

            $this->campusRepository->delete($campus);

            event(new DeletedContentEvent(CAMPUS_MODULE_SCREEN_NAME, $request, $campus));

            return $response->setMessage(__('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(__('core/base::notices.cannot_delete'));
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
                ->setMessage(__('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $campus = $this->campusRepository->findOrFail($id);
            $this->campusRepository->delete($campus);
            event(new DeletedContentEvent(CAMPUS_MODULE_SCREEN_NAME, $request, $campus));
        }

        return $response->setMessage(__('core/base::notices.delete_success_message'));
    }
}
