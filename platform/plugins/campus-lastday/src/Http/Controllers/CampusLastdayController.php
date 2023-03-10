<?php

namespace Botble\CampusLastday\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\CampusLastday\Http\Requests\CampusLastdayRequest;
use Botble\CampusLastday\Repositories\Interfaces\CampusLastdayInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\CampusLastday\Tables\CampusLastdayTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CampusLastday\Forms\CampusLastdayForm;
use Botble\Base\Forms\FormBuilder;

class CampusLastdayController extends BaseController
{
    /**
     * @var CampusLastdayInterface
     */
    protected $campusLastdayRepository;

    /**
     * CampusLastdayController constructor.
     * @param CampusLastdayInterface $campusLastdayRepository
     * @author Sang Nguyen
     */
    public function __construct(CampusLastdayInterface $campusLastdayRepository)
    {
        $this->campusLastdayRepository = $campusLastdayRepository;
    }

    /**
     * Display all campus_lastdays
     * @param CampusLastdayTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CampusLastdayTable $table)
    {

        page_title()->setTitle(trans('종강일관리'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('종강일관리'));

        return $formBuilder->create(CampusLastdayForm::class)->renderForm();
    }

    /**
     * Insert new CampusLastday into database
     *
     * @param CampusLastdayRequest $request
     * @return BaseHttpResponsey
     * @author Sang Nguyen
     */
    public function postCreate(CampusLastdayRequest $request, BaseHttpResponse $response)
    {
        $campus_lastday = $this->campusLastdayRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(CAMPUS_LASTDAY_MODULE_SCREEN_NAME, $request, $campus_lastday));

        return $response
            ->setPreviousUrl(route('campus_lastday.list'))
            ->setNextUrl(route('campus_lastday.edit', $campus_lastday->id))
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
        $campus_lastday = $this->campusLastdayRepository->findOrFail($id);

        event(new BeforeEditContentEvent(CAMPUS_LASTDAY_MODULE_SCREEN_NAME, $request, $campus_lastday));

        page_title()->setTitle(trans('plugins/campus-lastday::campus-lastday.edit') . ' #' . $id);

        return $formBuilder->create(CampusLastdayForm::class, ['model' => $campus_lastday])->renderForm();
    }

    /**
     * @param $id
     * @param CampusLastdayRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, CampusLastdayRequest $request, BaseHttpResponse $response)
    {
        $campus_lastday = $this->campusLastdayRepository->findOrFail($id);

        $campus_lastday->fill($request->input());

        $this->campusLastdayRepository->createOrUpdate($campus_lastday);

        event(new UpdatedContentEvent(CAMPUS_LASTDAY_MODULE_SCREEN_NAME, $request, $campus_lastday));

        return $response
            ->setPreviousUrl(route('campus_lastday.list'))
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
            $campus_lastday = $this->campusLastdayRepository->findOrFail($id);

            $this->campusLastdayRepository->delete($campus_lastday);

            event(new DeletedContentEvent(CAMPUS_LASTDAY_MODULE_SCREEN_NAME, $request, $campus_lastday));

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
            $campus_lastday = $this->campusLastdayRepository->findOrFail($id);
            $this->campusLastdayRepository->delete($campus_lastday);
            event(new DeletedContentEvent(CAMPUS_LASTDAY_MODULE_SCREEN_NAME, $request, $campus_lastday));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
