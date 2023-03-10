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
use Botble\Campus\Forms\Schedule\ScheduleDayForm;
use Botble\Campus\Http\Requests\Schedule\ScheduleDayRequest;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleDayInterface;
use Botble\Campus\Tables\Schedule\ScheduleDayTable;

class ScheduleDayController extends BaseController
{
    /**
     * @var ScheduleDayInterface
     */
    protected $scheduleDayRepository;

    /**
     * CampusController constructor.
     * @param ScheduleDayInterface $scheduleDayRepository
     * @author Sang Nguyen
     */
    public function __construct(ScheduleDayInterface $scheduleDayRepository)
    {
        $this->scheduleDayRepository = $scheduleDayRepository;
    }

    /**
     * Display all campuses
     * @param CampusTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(ScheduleDayTable $table)
    {

        page_title()->setTitle("스케줄 요일");

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle("일정 / 일정 만들기 요일");

        return $formBuilder->create(ScheduleDayForm::class)->renderForm();
    }

    /**
     * Insert new Campus into database
     *
     * @param ScheduleDayRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(ScheduleDayRequest $request, BaseHttpResponse $response)
    {
        $request->merge(['name' => strtolower($request->input('name'))  ]);
        $scheduleDay = $this->scheduleDayRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SCHEDULE_DAY_MODULE_SCREEN_NAME, $request, $scheduleDay));

        return $response
            ->setPreviousUrl(route('campus.schedule.day.list'))
            ->setNextUrl(route('campus.schedule.day.edit', $scheduleDay->id))
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
        $scheduleDay = $this->scheduleDayRepository->findOrFail($id);

        event(new BeforeEditContentEvent(SCHEDULE_DAY_MODULE_SCREEN_NAME, $request, $scheduleDay));

        page_title()->setTitle('스케줄 요일 편집' . ' #' . $id);

        return $formBuilder->create(ScheduleDayForm::class, ['model' => $scheduleDay])->renderForm();
    }

    /**
     * @param $id
     * @param CampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, ScheduleDayRequest $request, BaseHttpResponse $response)
    {
        $request->merge(['name' => strtolower($request->input('name'))  ]);

        $scheduleDay = $this->scheduleDayRepository->findOrFail($id);

        $scheduleDay->fill($request->input());

        $this->scheduleDayRepository->createOrUpdate($scheduleDay);

        event(new UpdatedContentEvent(SCHEDULE_DAY_MODULE_SCREEN_NAME, $request, $scheduleDay));

        return $response
            ->setPreviousUrl(route('campus.schedule.day.list'))
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
            $scheduleDay = $this->scheduleDayRepository->findOrFail($id);

            $this->scheduleDayRepository->delete($scheduleDay);

            event(new DeletedContentEvent(SCHEDULE_DAY_MODULE_SCREEN_NAME, $request, $scheduleDay));

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
            $scheduleDay = $this->scheduleDayRepository->findOrFail($id);
            $this->scheduleDayRepository->delete($scheduleDay);
            event(new DeletedContentEvent(SCHEDULE_DAY_MODULE_SCREEN_NAME, $request, $scheduleDay));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
