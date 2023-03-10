<?php

namespace Botble\Campus\Http\Controllers\Schedule;

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
use Botble\Base\Forms\FormBuilder;
use Botble\Campus\Forms\Schedule\ScheduleTimeForm;
use Botble\Campus\Http\Requests\Schedule\ScheduleTimeRequest;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleInterface;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleTimeInterface;
use Botble\Campus\Tables\Schedule\ScheduleTimeTable;

class ScheduleTimeController extends BaseController
{
    /**
     * @var ScheduleInterface
     */
    protected $scheduleTimeRepository;

    /**
     * CampusController constructor.
     * @param ScheduleTimeInterface $scheduleTimeRepository
     * @author Sang Nguyen
     */
    public function __construct(ScheduleTimeInterface $scheduleTimeRepository)
    {
        $this->scheduleTimeRepository = $scheduleTimeRepository;
    }

    /**
     * Display all campuses
     * @param CampusTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(ScheduleTimeTable $table)
    {

        page_title()->setTitle("시간표");

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle("만들다 시간표");

        return $formBuilder->create(ScheduleTimeForm::class)->renderForm();
    }

    /**
     * Insert new Campus into database
     *
     * @param CampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(ScheduleTimeRequest $request, BaseHttpResponse $response)
    {
        if($request->input('from') > $request->input('to') ){
            return $response
            ->setError()
            ->setMessage('시작은 종료보다 작아야 합니다.');
        }
        $scheduleTime = $this->scheduleTimeRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SCHEDULE_TIME_MODULE_SCREEN_NAME, $request, $scheduleTime));

        return $response
            ->setPreviousUrl(route('campus.schedule.time.list'))
            ->setNextUrl(route('campus.schedule.time.edit', $scheduleTime->id))
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
        $scheduleTime = $this->scheduleTimeRepository->findOrFail($id);

        event(new BeforeEditContentEvent(SCHEDULE_TIME_MODULE_SCREEN_NAME, $request, $scheduleTime));

        page_title()->setTitle('시간표 편집하다' . ' #' . $id);

        return $formBuilder->create(ScheduleTimeForm::class, ['model' => $scheduleTime])->renderForm();
    }

    /**
     * @param $id
     * @param CampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, ScheduleTimeRequest $request, BaseHttpResponse $response)
    {
        if($request->input('from') > $request->input('to') ){
            return $response
            ->setError()
            ->setMessage('시작은 종료보다 작아야 합니다.');
        }

        $scheduleTime = $this->scheduleTimeRepository->findOrFail($id);

        $scheduleTime->fill($request->input());

        $this->scheduleTimeRepository->createOrUpdate($scheduleTime);

        event(new UpdatedContentEvent(SCHEDULE_TIME_MODULE_SCREEN_NAME, $request, $scheduleTime));

        return $response
            ->setPreviousUrl(route('campus.schedule.time.list'))
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
            $scheduleTime = $this->scheduleTimeRepository->findOrFail($id);

            $this->scheduleTimeRepository->delete($scheduleTime);

            event(new DeletedContentEvent(SCHEDULE_TIME_MODULE_SCREEN_NAME, $request, $scheduleTime));

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
            $scheduleTime = $this->scheduleTimeRepository->findOrFail($id);
            $this->scheduleTimeRepository->delete($scheduleTime);
            event(new DeletedContentEvent(SCHEDULE_TIME_MODULE_SCREEN_NAME, $request, $scheduleTime));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
