<?php

namespace Botble\Campus\Http\Controllers\Schedule;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\Schedule\ScheduleFilterForm;
use Botble\Campus\Forms\Schedule\ScheduleForm;
use Botble\Campus\Http\Requests\Schedule\ScheduleFilterRequest;
use Botble\Campus\Http\Requests\Schedule\ScheduleRequest;
use Botble\Campus\Models\Schedule\ScheduleShare;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleFilterInterface;
use Botble\Campus\Tables\CampusTable;
use Botble\Campus\Tables\Schedule\ScheduleFilterTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleFilterController extends BaseController
{
    /**
     * @var ScheduleFilterInterface
     */
    protected $scheduleFilterRepository;

    /**
     * CampusController constructor.
     * @param ScheduleFilterInterface $scheduleFilterRepository
     * @author Sang Nguyen
     */
    public function __construct(ScheduleFilterInterface $scheduleFilterRepository)
    {
        $this->scheduleFilterRepository = $scheduleFilterRepository;
    }

    /**
     * Display all campuses
     * @param CampusTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(ScheduleFilterTable $table)
    {

        page_title()->setTitle("캠퍼스 / 일정 / 필터 / 목록");

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/datetimepicker.css']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/multi-select.css']);
        \Assets::addStylesDirectly(['/vendor/core/packages/bootstrap-datepicker/css/bootstrap-datepicker3.min.css']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/jquery.quicksearch.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/jquery.multi-select.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/run-datetime.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/run-multiSelect.js']);

        page_title()->setTitle("캠퍼스 / 일정 / 필터 / 새 필터 만들기");

        return $formBuilder->create(ScheduleFilterForm::class)->renderForm();
    }

    /**
     * Insert new Campus into database
     *
     * @param ScheduleFilterRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(ScheduleFilterRequest $request, BaseHttpResponse $response)
    {

        $scheduleFilter = $this->scheduleFilterRepository->createOrUpdate($request->input());
        event(new CreatedContentEvent(SCHEDULE_FILTER_MODULE_SCREEN_NAME, $request, $scheduleFilter));

        return $response
            ->setPreviousUrl(route('campus.schedule.filter.list'))
            ->setNextUrl(route('campus.schedule.filter.edit', $scheduleFilter->id))
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
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/datetimepicker.css']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/multi-select.css']);
        \Assets::addStylesDirectly(['/vendor/core/packages/bootstrap-datepicker/css/bootstrap-datepicker3.min.css']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/jquery.quicksearch.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/jquery.multi-select.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/run-datetime.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/run-multiSelect.js']);

        $scheduleFilter = $this->scheduleFilterRepository->findOrFail($id);

        event(new BeforeEditContentEvent(SCHEDULE_FILTER_MODULE_SCREEN_NAME, $request, $scheduleFilter));

        page_title()->setTitle('캠퍼스 / 일정 / 필터 / 편집' . ' #' . $id);

        return $formBuilder->create(ScheduleFilterForm::class, ['model' => $scheduleFilter])->renderForm();
    }

    /**
     * @param $id
     * @param ScheduleRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, ScheduleRequest $request, BaseHttpResponse $response)
    {

        $scheduleFilter = $this->scheduleFilterRepository->findOrFail($id);

        $scheduleFilter->fill($request->input());

        $this->scheduleFilterRepository->createOrUpdate($scheduleFilter);

        event(new UpdatedContentEvent(SCHEDULE_FILTER_MODULE_SCREEN_NAME, $request, $scheduleFilter));
        return $response
            ->setPreviousUrl(route('campus.schedule.filter.list'))
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
            $scheduleFilter = $this->scheduleFilterRepository->findOrFail($id);

            $this->scheduleFilterRepository->delete($scheduleFilter);

            event(new DeletedContentEvent(SCHEDULE_FILTER_MODULE_SCREEN_NAME, $request, $scheduleFilter));

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
            $scheduleFilter = $this->scheduleFilterRepository->findOrFail($id);
            $this->scheduleFilterRepository->delete($scheduleFilter);
            event(new DeletedContentEvent(SCHEDULE_FILTER_MODULE_SCREEN_NAME, $request, $scheduleFilter));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
