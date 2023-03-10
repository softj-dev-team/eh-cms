<?php

namespace Botble\Campus\Http\Controllers\Schedule;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Campus\Http\Requests\CampusRequest;
use Botble\Campus\Repositories\Interfaces\CampusInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Campus\Forms\Genealogy\GenealogyCommentsForm;
use Botble\Campus\Forms\Schedule\ScheduleTimeLineForm;
use Botble\Campus\Http\Requests\Genealogy\GenealogyCommentsRequest;
use Botble\Campus\Http\Requests\Schedule\ScheduleTimeLineRequest;
use Botble\Campus\Models\Schedule\ScheduleTimeLine;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleTimeLineInterface;
use Botble\Campus\Tables\Schedule\ScheduleTimeLineTable;
use Illuminate\Support\Facades\Auth;

class ScheduleTimeLineController extends BaseController
{
    /**
    * @var ScheduleTimeLineInterface
    */
    protected $scheduleTimeLineRepository;

    /**
     * EventsController constructor.
     * @param ScheduleTimeLineInterface $jobsCommentsRepository
     * @author Sang Nguyen
     */
    public function __construct(ScheduleTimeLineInterface $scheduleTimeLineRepository)
    {
        $this->scheduleTimeLineRepository = $scheduleTimeLineRepository;
    }

    /**
     * Display all events
     * @param ShelterCommentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(ScheduleTimeLineTable $table, $id)
    {
        page_title()->setTitle('캠퍼스 / 일정 / 타임라인 #'.$id);

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
        page_title()->setTitle('캠퍼스 / 일정 / 타임라인 #'.$id.' / 만들다');


        return $formBuilder->create(ScheduleTimeLineForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(ScheduleTimeLineRequest $request, BaseHttpResponse $response, $id)
    {
        /*
        if ($request->input('from') >= $request->input('to')) {
            return $response
            ->setError()
            ->setMessage('시작은 종료보다 작아야 합니다.');
        }

        if ($this->validateTimeLine($request->input('from'), $request->input('to'), $request->input('day'), $id)  == false) {
            return $response
            ->setError()
            ->setMessage('시간이 이미 있습니다. 다른 시간을 선택하세요!!!');
        }
        */

        $datetime = $request->input('datetime');
        unset($datetime['#template_day']);
        unset($datetime['#template_from']);
        unset($datetime['#template_to']);
        if (!count($datetime)) {
            return $response
            ->setError()
            ->setMessage('요일, 시작 시간 및 종료 시간을 추가해야 합니다.');
        }

        foreach ($datetime as $dt) {
            if ($dt['from'] >= $dt['to']) {
                return $response
                ->setError()
                ->setMessage('시작은 종료보다 작아야 합니다.');
            }

            if ($this->validateTimeLine($dt['from'], $dt['to'], $dt['day'], $id)  == false) {
                return $response
                ->setError()
                ->setMessage('시간이 이미 있습니다. 다른 시간을 선택하세요!!!');
            }
        }

        $request->merge([
            'schedule_id' => $id,
            'datetime' => $datetime
        ]);
        $scheduleTimeLine = $this->scheduleTimeLineRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SCHEDULE_TIMELINE_MODULE_SCREEN_NAME, $request, $scheduleTimeLine));

        return $response
            ->setPreviousUrl(route('campus.schedule.timeline.list', ['id' => $id]))
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
        $scheduleTimeLine = $this->scheduleTimeLineRepository->findOrFail($id);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/jscolor.js']);
        event(new BeforeEditContentEvent(SCHEDULE_TIMELINE_MODULE_SCREEN_NAME, $request, $scheduleTimeLine));

        page_title()->setTitle('스케줄 시간 편집' . ' #' . $id);

        return $formBuilder->create(ScheduleTimeLineForm::class, ['model' => $scheduleTimeLine,'idSchedule'=> $scheduleTimeLine->schedule_id])->renderForm();
    }

    /**
     * @param $id
     * @param CampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, ScheduleTimeLineRequest $request, BaseHttpResponse $response)
    {
        /*
        if ($request->input('from') > $request->input('to')) {
            return $response
            ->setError()
            ->setMessage('시작은 종료보다 작아야 합니다.');
        }
        */

        $scheduleTimeLine = $this->scheduleTimeLineRepository->findOrFail($id);

        /*
        if ($request->input('is_change_Time') != 1) {
            $request->request->remove('day');
            $request->request->remove('from');
            $request->request->remove('to');
        } else {
            if ($this->validateTimeLine($request->input('from'), $request->input('to'), $request->input('day'), $scheduleTimeLine->schedule_id)  == false) {
                return $response
                ->setError()
                ->setMessage('시간이 이미 있습니다. 다른 시간을 선택하세요!!!');
            }
        }
        */
        $currentDatetime = $scheduleTimeLine->datetime;
        $datetime = $request->input('datetime');
        unset($datetime['#template_day']);
        unset($datetime['#template_from']);
        unset($datetime['#template_to']);
        if (!count($datetime)) {
            return $response
            ->setError()
            ->setMessage('요일, 시작 시간 및 종료 시간을 추가해야 합니다.');
        }

        $dateCompares = [];
        if (count($currentDatetime)) {
            foreach ($currentDatetime as &$dt) {
                ksort($dt);
            }
            $dateCompares = array_udiff_assoc($datetime, $currentDatetime, function ($a, $b) {
                if (strcmp($a['day'], $b['day']) || strcmp($a['from'], $b['from']) || strcmp($a['to'], $b['to']) ) {
                    return true;
                }
                return false;
            });
        }

        if (count($dateCompares)) {
            foreach ($dateCompares as $dt) {
                if ($dt['from'] >= $dt['to']) {
                    return $response
                    ->setError()
                    ->setMessage('시작은 종료보다 작아야 합니다.');
                }

                if ($this->validateTimeLine($dt['from'], $dt['to'], $dt['day'], $scheduleTimeLine->schedule_id)  == false) {
                    return $response
                    ->setError()
                    ->setMessage('시간이 이미 있습니다. 다른 시간을 선택하세요!!!');
                }
            }
        }

        $request->merge([
            'datetime' => $datetime,
        ]);


        $scheduleTimeLine->fill($request->input());
        $this->scheduleTimeLineRepository->createOrUpdate($scheduleTimeLine);
        event(new UpdatedContentEvent(SCHEDULE_TIMELINE_MODULE_SCREEN_NAME, $request, $scheduleTimeLine));

        return $response
            ->setPreviousUrl(route('campus.schedule.timeline.list', ['id'=>$scheduleTimeLine->schedule_id]))
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
            $scheduleTimeLine = $this->scheduleTimeLineRepository->findOrFail($id);

            $this->scheduleTimeLineRepository->delete($scheduleTimeLine);

            event(new DeletedContentEvent(SCHEDULE_TIMELINE_MODULE_SCREEN_NAME, $request, $scheduleTimeLine));

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
            $scheduleTimeLine = $this->scheduleTimeLineRepository->findOrFail($id);
            $this->scheduleTimeLineRepository->delete($scheduleTimeLine);
            event(new DeletedContentEvent(SCHEDULE_TIMELINE_MODULE_SCREEN_NAME, $request, $scheduleTimeLine));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function validateTimeLine($from, $to, $day, $schedule_id)
    {
        $scheduleTimeLine = ScheduleTimeLine::where('schedule_id', $schedule_id)->where('day', $day)->get();

        foreach ($scheduleTimeLine as $key => $item) {
            // a, b là khoản time line đã có từ trước
            //From  nằm trong khoản a và b -> false

            if ($item->from < $from && $from < $item->to) {
                return false;
            }

            //To  nằm trong khoản a và b -> false
            if ($item->from < $to && $to < $item->to) {
                return false;
            }

            // a và b nằm trong khoản From To
            if ($from <= $item->from && $item->to <= $to) {
                return false;
            }
        }

        return true;
    }
}
