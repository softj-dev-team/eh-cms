<?php

namespace Botble\Campus\Http\Controllers\Schedule;

use App\Imports\ScheduleImport;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Campus\Forms\Schedule\ScheduleImportForm;
use Illuminate\Http\Request;
use Exception;
use Botble\Campus\Tables\CampusTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Campus\Forms\Schedule\ScheduleForm;
use Botble\Campus\Http\Requests\Schedule\ScheduleRequest;
use Botble\Campus\Models\Schedule\ScheduleShare;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleInterface;
use Botble\Campus\Tables\Schedule\ScheduleTable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends BaseController
{
    /**
     * @var ScheduleInterface
     */
    protected $scheduleRepository;

    /**
     * CampusController constructor.
     * @param ScheduleInterface $scheduleRepository
     * @author Sang Nguyen
     */
    public function __construct(ScheduleInterface $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * Display all campuses
     * @param CampusTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(ScheduleTable $table)
    {

        page_title()->setTitle("목록");

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author BM Phuoc
     */
    public function getImport(FormBuilder $formBuilder)
    {
        page_title()->setTitle("업로드");

        return $formBuilder->create(ScheduleImportForm::class)->renderForm();
    }
    /**
     * Import data from file excel
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postImport(Request $request, BaseHttpResponse $response)
    {
        if ($request->hasFile('import_file')) {
            Excel::import(new ScheduleImport, $request->file('import_file'));

            return $response
                ->setPreviousUrl(route('campus.schedule.list'))
                ->setMessage(trans('core/base::notices.import_success_message'));
        }

        return $response
            ->setPreviousUrl(route('campus.schedule.import'))
            ->setMessage(trans('core/base::notices.import_no_file'));
    }


    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle("일정 만들기");

        return $formBuilder->create(ScheduleForm::class)->renderForm();
    }

    /**
     * Insert new Campus into database
     *
     * @param ScheduleRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(ScheduleRequest $request, BaseHttpResponse $response)
    {
        // $request->merge(['id_login' => 0]);
        $schedule = $this->scheduleRepository->createOrUpdate($request->input());
        event(new CreatedContentEvent(SCHEDULE_MODULE_SCREEN_NAME, $request, $schedule));

        $memberIds = $request->input('member_id') ?? [];

        if($memberIds){
            foreach ($memberIds as $key => $item) {
                # code...
                $scheduleShare = new ScheduleShare;
                $scheduleShare->schedule_id = $schedule->id;
                $scheduleShare->member_id = $item;
                $scheduleShare->author = Auth::user()->getFullName();
                $scheduleShare->save();
            }
        }

        return $response
            ->setPreviousUrl(route('campus.schedule.list'))
            ->setNextUrl(route('campus.schedule.edit', $schedule->id))
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
        $schedule = $this->scheduleRepository->findOrFail($id);

        event(new BeforeEditContentEvent(SCHEDULE_MODULE_SCREEN_NAME, $request, $schedule));

        page_title()->setTitle(__('campus.timetable.schedule'). ' #' . $id);

        return $formBuilder->create(ScheduleForm::class, ['model' => $schedule])->renderForm();
    }

    /**
     * @param $id
     * @param ScheduleRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, ScheduleRequest $request, BaseHttpResponse $response)
    {

        // $request->merge(['id_login' => 0]);

        $schedule = $this->scheduleRepository->findOrFail($id);

        $schedule->fill($request->input());

        $this->scheduleRepository->createOrUpdate($schedule);

        event(new UpdatedContentEvent(SCHEDULE_MODULE_SCREEN_NAME, $request, $schedule));

        foreach ($schedule->scheduleShare() as $key => $item) {
            $item->delete();
        }

        if(!is_null($request->input('member_id') )){
            foreach ($request->input('member_id') as $key => $item) {
                # code...
                $scheduleShare = new ScheduleShare;
                $scheduleShare->schedule_id = $schedule->id;
                $scheduleShare->member_id = $item;
                $scheduleShare->author = Auth::user()->getFullName();
                $scheduleShare->save();
            }
        }


        return $response
            ->setPreviousUrl(route('campus.schedule.list'))
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
            $schedule = $this->scheduleRepository->findOrFail($id);

            $this->scheduleRepository->delete($schedule);

            event(new DeletedContentEvent(SCHEDULE_MODULE_SCREEN_NAME, $request, $schedule));

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
            $schedule = $this->scheduleRepository->findOrFail($id);
            $this->scheduleRepository->delete($schedule);
            event(new DeletedContentEvent(SCHEDULE_MODULE_SCREEN_NAME, $request, $schedule));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
