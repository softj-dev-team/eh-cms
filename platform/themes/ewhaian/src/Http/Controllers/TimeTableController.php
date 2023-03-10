<?php

namespace Theme\Ewhaian\Http\Controllers;

use Theme;
use Validator;
use Illuminate\Http\Request;
use LanguageDetection\Language;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Botble\Campus\Models\Evaluation\Major;
use Botble\Campus\Models\Schedule\Schedule;
use Botble\Campus\Models\Schedule\ScheduleDay;
use Botble\Campus\Models\Evaluation\Evaluation;
use Botble\Campus\Models\Schedule\ScheduleTime;
use Botble\Campus\Models\Schedule\ScheduleShare;
use Botble\Campus\Models\Schedule\ScheduleFilter;
use Botble\Campus\Models\Schedule\ScheduleTimeLine;
use Botble\Campus\Models\Evaluation\MajorEvaluation;
use Botble\Campus\Models\Description\DescriptionCampus;
use Botble\Campus\Models\Schedule\ScheduleConfigMember;

class TimeTableController extends Controller
{

    /**
     * @return \Response
     */
    public static function index()
    {
        $schedule = Schedule::where('id_login', auth()->guard('member')->user()->id_login)->orderBy('created_at', 'DESC')->first();
        $scheduleTime = ScheduleTime::select('from', 'to', 'unit')->orderBy('created_at', 'DESC')->firstOrFail();
        $scheduleDay = ScheduleDay::where('status', 'publish')->get();
        $scheduleAll = Schedule::where('id_login', auth()->guard('member')->user()->id_login)->where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $description = DescriptionCampus::where('code', 'SCHEDULE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $config = ScheduleConfigMember::where('member_id', auth()->guard('member')->user()->id)->where('schedule_id',$schedule->id)->first();
        Theme::setTitle('Campus | TimeTable List ');
        return Theme::scope('campus.timetable.index', [
            'schedule' => $schedule,
            'scheduleTime' => $scheduleTime,
            'scheduleDay' => $scheduleDay,
            'scheduleAll' => $scheduleAll,
            'description' => $description,
            'config' => $config,
        ])->render();

    }
    public function show($id)
    {
        $schedule = Schedule::where('id_login', auth()->guard('member')->user()->id_login)->where('id', $id)->first();
        if (is_null($schedule)) {
            return redirect()->back()->with('err', __('controller.please_choose_your_schedule'));
        }
        $scheduleTime = ScheduleTime::select('from', 'to', 'unit')->orderBy('created_at', 'DESC')->first();
        $scheduleDay = ScheduleDay::where('status', 'publish')->get();
        $scheduleAll = Schedule::where('id_login', auth()->guard('member')->user()->id_login)->where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $description = DescriptionCampus::where('code', 'SCHEDULE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $config = ScheduleConfigMember::where('member_id', auth()->guard('member')->user()->id)->where('schedule_id',$schedule->id)->first();
        // Theme::breadcrumb()->add('Evaluation', route('campus.evaluation_comments_major'))->add( "List", 'http:...');

        Theme::setTitle('Campus | TimeTable List ');

        return Theme::scope('campus.timetable.index', [
            'schedule' => $schedule,
            'scheduleTime' => $scheduleTime,
            'scheduleDay' => $scheduleDay,
            'scheduleAll' => $scheduleAll,
            'description' => $description,
            'config' => $config,
        ])->render();

    }

    public static function showFromShare()
    {
        $id = ScheduleShare::where('member_id', auth()->guard('member')->user()->id_login)->orderBy('created_at', 'DESC')->first();
        $schedule = auth()->guard('member')->user()->schedule()->orderBy('created_at', 'DESC')->first();
        $scheduleTime = ScheduleTime::select('from', 'to', 'unit')->orderBy('created_at', 'DESC')->firstOrFail();
        $scheduleDay = ScheduleDay::where('status', 'publish')->get();
        $scheduleAll = auth()->guard('member')->user()->schedule()->orderBy('created_at', 'DESC')->get();
        $description = DescriptionCampus::where('code', 'SCHEDULE_SHARE_MEMBER_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        // Theme::breadcrumb()->add('Evaluation', route('campus.evaluation_comments_major'))->add( "List", 'http:...');

        Theme::setTitle('Campus | TimeTable List ');

        return Theme::scope('campus.timetable.share', ['schedule' => $schedule, 'scheduleTime' => $scheduleTime, 'scheduleDay' => $scheduleDay, 'scheduleAll' => $scheduleAll, 'description' => $description])->render();

    }

    public static function showFromShareByID($id)
    {
        $schedule = auth()->guard('member')->user()->schedule()->where('schedule.id', $id)->orderBy('created_at', 'DESC')->firstOrFail();
        $scheduleTime = ScheduleTime::select('from', 'to', 'unit')->orderBy('created_at', 'DESC')->firstOrFail();
        $scheduleDay = ScheduleDay::where('status', 'publish')->get();
        $scheduleAll = auth()->guard('member')->user()->schedule()->orderBy('created_at', 'DESC')->get();
        $description = DescriptionCampus::where('code', 'SCHEDULE_SHARE_MEMBER_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        // Theme::breadcrumb()->add('Evaluation', route('campus.evaluation_comments_major'))->add( "List", 'http:...');

        Theme::setTitle('Campus | TimeTable List ');

        return Theme::scope('campus.timetable.share', ['schedule' => $schedule, 'scheduleTime' => $scheduleTime, 'scheduleDay' => $scheduleDay, 'scheduleAll' => $scheduleAll, 'description' => $description])->render();

    }

    public function createSchedule(Request $request)
    {
        $schedule = new Schedule;
        $schedule->name = $request->input('name');
        $schedule->id_login = auth()->guard('member')->user()->id_login;
        $schedule->start = $request->input('start');
        $schedule->end = $request->input('end');
        $schedule->save();

        return redirect('campus/timetable/schedule/v2?active_filter='.$request->input('curSem'))->with('success', __('controller.timetable.create_new_schedule_successful',['module'=>__('campus.timetable.schedule')]));
    }

    public function createScheduleTimeLine(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $validator = Validator::make($input, [
                'schedule_id' => 'bail|nullable|integer|exists:schedule,id',
                'course_code'   => 'required|numeric|digits:5',
                'division'   => 'nullable|digits:2',
                'title'   => 'required',
                'professor_name' => 'required',
                'lecture_room' => 'required',
                'datetime' => 'required|array|min:1',
                'datetime.*' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return response()->json(compact(['errors']), 422);
            }

            $datetime = $request->input('datetime');
            unset($datetime['#template_day']);
            unset($datetime['#template_from']);
            unset($datetime['#template_to']);
            if (!count($datetime)) {
                $field = 'datetime';
                $message = __('campus.timetable.no_have_datetime');
                return response()->json(compact('field', 'message'), 422);
            }

            $title = $request->input('title');
            if (!is_numeric($title)) {
                $ld = new Language;
                $validateTitle = $ld->detect($title)->whitelist('en', 'ko')->close();
                if (!count($validateTitle)) {
                    $field = 'title';
                    $message = __('campus.timetable.professor_name_validate');
                    return response()->json(compact(['field', 'message']), 422);
                } elseif (empty($validateTitle['ko']) && empty($validateTitle['en'])) {
                    $field = 'title';
                    $message = __('campus.timetable.professor_name_validate');
                    return response()->json(compact(['field', 'message']), 422);
                }
            }

            $professorName = $request->input('professor_name');
            if (!is_numeric($professorName)) {
                $ld = new Language;
                $validateProfessorName = $ld->detect($professorName)->whitelist('en', 'ko')->close();
                if (!count($validateProfessorName)) {
                    $field = 'professor_name';
                    $message = __('campus.timetable.professor_name_validate');
                    return response()->json(compact(['field', 'message']), 422);
                } elseif (empty($validateProfessorName['ko']) && empty($validateProfessorName['en'])) {
                    $field = 'professor_name';
                    $message = __('campus.timetable.professor_name_validate');
                    return response()->json(compact(['field', 'message']), 422);
                }
            }

            foreach ($datetime as $dt) {
                if ($dt['from'] >= $dt['to']) {
                    $field = 'datetime';
                    $message = __('controller.timetable.time_from_less_than_to');
                    return response()->json(compact(['field', 'message']), 422);
                }

                if ($this->validateTimeLine($dt['from'], $dt['to'], $dt['day'], $request->input('schedule_id'))  == false) {
                    $field = 'datetime';
                    $message = __('controller.time_already_exists');
                    return response()->json(compact(['field', 'message']), 422);
                }
            }

            $request->merge([
                'datetime' => $datetime,
            ]);

            $courseCode = $request->course_code;
            $division = $request->division ?? null;
            $course_division = $division ? ($courseCode . '-' . $division) : $courseCode;

            // 학수번호-분반을 입력받아 해당 강의 평가 확인
            if ($division) {
                $evaluation = Evaluation::where('course_code', $courseCode)->where('division', $division)->first();
                if (!$evaluation) {
                    $evaluation = Evaluation::where('course_code', $courseCode)->first();
                }
            } else {
                $evaluation = Evaluation::where('course_code', $courseCode)->first();
            }

            $scheduleTimeLine = new ScheduleTimeLine;
            $scheduleTimeLine->schedule_id = $request->input('schedule_id');
            $scheduleTimeLine->title = $request->input('title');
            $scheduleTimeLine->lecture_room = $request->input('lecture_room');
            $scheduleTimeLine->professor_name = $request->input('professor_name');
            $scheduleTimeLine->group_color = $evaluation->id ?? null;
            $scheduleTimeLine->course_division = $course_division;
            $scheduleTimeLine->datetime = $request->datetime ?? null;
            $scheduleTimeLine->save();

            $result = [
                'message' => __('controller.create_timetable_successful', ['module' =>__('campus.timetable.timeline')])
            ];

            return response()->json($result);
        }

        abort(404);
    }

    public function deleteScheduleTimeLine(Request $request)
    {
        if ($request->input('idScheduleTimeLine') > 0) {

            //Xóa tất cả các timeline thuộc lecture
            // $scheduleTimeLine = ScheduleTimeLine::findOrFail($request->input('idScheduleTimeLine'));

            // $scheduleTimeLine->delete();

            return redirect()->back()->with('success', __('controller.delete_timetable_successful',['module'=>__('campus.timetable.timeline')]));

        } else {
            // if ($request->input('deleteSchedule')) {
            //     $schedule = Schedule::findOrFail($request->input('schedule_id'));
            //     $schedule->delete();

            //     return redirect()->route('scheduleFE.timeline.v2')->with('success', __('controller.delete_timetable_successful',['module'=>__('campus.timetable.schedule')]) );
            // } else {
            //     $scheduleTimeLine = ScheduleTimeLine::where('schedule_id', $request->input('schedule_id'));

            //     $scheduleTimeLine->delete();

            //     $schedule = Schedule::findOrFail($request->input('schedule_id'));
            //     $schedule->total_credit = null;
            //     $schedule->save();

            // }
            $scheduleTimeLine = ScheduleTimeLine::where('schedule_id', $request->input('schedule_id'));
            if($scheduleTimeLine->get()->count() > 0){
                $scheduleTimeLine->delete();
            }
            $schedule = Schedule::where('id',$request->input('schedule_id'));
            $schedule->delete();

            $redirectAgrs = [];
            if ($request->active_filter != '') {
                $redirectAgrs = [
                    'active_filter' => $request->active_filter
                ];
            }
        }

        //return redirect()->back()->with('success',  __('controller.delete_timetable_successful',['module'=>__('campus.timetable.timeline')]));

        return redirect()->route('scheduleFE.timeline.v2', $redirectAgrs)->with('success', __('controller.delete_timetable_successful',['module'=>__('campus.timetable.timeline')]));

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
    public function checkDuplicate($lecture, $schedule_id)
    {
        $schedule = Schedule::where('id_login', auth()->guard('member')->user()->id_login)->where('id', $schedule_id)->first();
        $removeLecture = [];
        foreach ($lecture->get() as $key => $item) {
            foreach (json_decode($item->datetime, true) as $key => $subItem) {
                if ($this->validateTimeLine($subItem['from'], $subItem['to'], $subItem['day'], $schedule_id) == false) {
                    array_push($removeLecture, $item->id);
                }
            }
        }

        return $lecture->whereNotIn('id', $removeLecture)->get();
    }

    public function showV2(Request $request)
    {
        $schedule = Schedule::where('id_login', auth()->guard('member')->user()->id_login)->orderBy('created_at', 'DESC');
        if ($request->input('schedule_id')) {
            $schedule = Schedule::where('id_login', auth()->guard('member')->user()->id_login)->where('id', $request->input('schedule_id'));
            if (is_null($schedule->first())) {
                return redirect()->back()->with('err', '시간표를 선택해주세요.');
            }
        }

        $scheduleTime = ScheduleTime::select('from', 'to', 'unit')->orderBy('created_at', 'DESC')->firstOrFail();
        $scheduleDay = ScheduleDay::where('status', 'publish')->get();
        $scheduleAll = Schedule::where('id_login', auth()->guard('member')->user()->id_login)->where('status', 'publish')->orderBy('created_at', 'DESC');
        $description = DescriptionCampus::where('code', 'SCHEDULE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        // Theme::breadcrumb()->add('Evaluation', route('campus.evaluation_comments_major'))->add( "List", 'http:...');

        Theme::setTitle(__('campus').' | '.__('campus.timetable'));

        $categories = Major::where('status', 'publish')->where('parents_id', 0)->get();
        $evaluation = Evaluation::where('status', 'publish')->orderBy('created_at', 'DESC')->limit(20)->get();
        //$listFilter = ScheduleFilter::where('status', 'publish')->orderBy('created_at','ASC')->get();
        $listFilter = ScheduleFilter::where('status', 'publish')->orderBy('id','ASC')->get();
        // if (!empty($request->input('filter_id'))) {
        //     $filter = ScheduleFilter::where('id', $request->input('filter_id'))->where('status', 'publish')->firstOrFail();
        //     $scheduleAll->whereBetween('created_at', [date_format(date_create($filter->start), "Y-m-d 00:00:00"), date_format(date_create($filter->end), "Y-m-d 23:59:59")]);
        //     $schedule->whereBetween('created_at', [date_format(date_create($filter->start), "Y-m-d 00:00:00"), date_format(date_create($filter->end), "Y-m-d 23:59:59")]);

        // } else {
        //     if(!is_null( $listFilter)) {
        //        $filter =  ScheduleFilter::where('status', 'publish')->where('start','<=', today())->where('end','>=',today())->first();
        //        $scheduleAll->whereBetween('created_at', [date_format(date_create($filter->start), "Y-m-d 00:00:00"), date_format(date_create($filter->end), "Y-m-d 23:59:59")]);
        //        $schedule->whereBetween('created_at', [date_format(date_create($filter->start), "Y-m-d 00:00:00"), date_format(date_create($filter->end), "Y-m-d 23:59:59")]);

        //     } else {
        //         $filter = checkQuaterYear();
        //         $scheduleAll->whereBetween('created_at', [$filter['start'], $filter['end']]);
        //         $schedule->whereBetween('created_at', [$filter['start'], $filter['end']]);
        //     }
        // }

        $start = date("Y-m-d");
        $end = date("Y-m-d");
        if(!empty($request->input('active_filter'))) {
                $filter =  ScheduleFilter::where('status', 'publish')->where("id",$request->input('active_filter'))->first();
                //$scheduleAll->whereBetween('created_at', [date_format(date_create($filter->start), "Y-m-d 00:00:00"), date_format(date_create($filter->end), "Y-m-d 23:59:59")]);
                //$schedule->whereBetween('created_at', [date_format(date_create($filter->start), "Y-m-d 00:00:00"), date_format(date_create($filter->end), "Y-m-d 23:59:59")]);

                $startDate= date_format(date_create($filter->start), "Y-m-d 00:00:00");
                $endDate= date_format(date_create($filter->end), "Y-m-d 23:59:59");

                $scheduleAll->where('start', '>=',$startDate)
                ->where('end', '<=',$endDate);

                $schedule->where('start', '>=',$startDate)
                ->where('end', '<=',$endDate);

               // dd($schedule->dd());


                $activeFilterID = $request->input('active_filter');
                $start = date_format(date_create($filter->start), "Y-m-d 00:00:00");
                $end = date_format(date_create($filter->end), "Y-m-d 00:00:00");
        } else {
                //$filter = checkQuaterYear();
                $filter = checkHalfYear();
                //$scheduleAll->whereBetween('created_at', [$filter['start'], $filter['end']]);
                //$schedule->whereBetween('created_at', [$filter['start'], $filter['end']]);

                $startDate = $filter['start']." 00:00:00";
                $endDate = $filter['end']." 23:59:59";


                $scheduleAll->where('start', '>=',$startDate)
                ->where('end', '<=',$endDate);

                $schedule->where('start', '>=',$startDate)
                ->where('end', '<=',$endDate);


                $filterCurrentID =  ScheduleFilter::where('status', 'publish')->where('start','<=', today())->where('end','>=',today())->first();
                $activeFilterID = $filterCurrentID->id;

                $start = $filter['start']." 00:00:00";
                $end = $filter['end']." 00:00:00";
        }

        $timeline1 = collect();
        $timeline2 = collect();
        $timeline = collect();
        $config = null;
        if (!is_null($schedule->first() )) {
            $temp = $schedule->first()->timeline()->where('status','publish');
            $timeline1 = (clone $temp)->whereNotNull('datetime')->whereNull('group_color')->get();
            $timeline2 = (clone $temp)->whereNotNull('datetime')->whereNotNull('group_color')->get()->unique('group_color')->values();
            $timeline = (clone $temp)->whereNotNull('datetime')->get();
            $config = ScheduleConfigMember::where('member_id', auth()->guard('member')->user()->id)->where('schedule_id',$schedule->first()->id)->first();
        }

        $scheduleWeeks = [];
        if ($timeline->count()) {
            $timelines = $timeline->toArray();
            foreach ($timelines as $key => $time) {
                if (count($time['datetime'])) {
                    foreach ($time['datetime'] as $datetime) {
                        $scheduleWeeks[$datetime['day']][] = $time;
                    }
                }
            }
        }

        $dataSchedules = [];
        if (count($scheduleWeeks)) {
            foreach ($scheduleWeeks as $key => $scheduleWeek) {
                foreach ($scheduleWeek as $scheduleKey => $scheduleItem) {
                    foreach ($scheduleItem['datetime'] as $dt) {
                        $dataSchedules[$key][(int) $dt['from']][] = $scheduleItem;
                    }
                }
            }

            foreach ($dataSchedules as &$dataSchedule) {
                foreach ($dataSchedule as &$dataScheduleItem) {
                    $dataScheduleItem = array_map("unserialize", array_unique(array_map("serialize", $dataScheduleItem)));
                    $dataScheduleItem = array_values($dataScheduleItem);
                }
            }
        }

        return Theme::layout('timetable')->scope('campus.timetable.timetable_v2',
            [
                'categories' => $categories,
                'evaluation' => $evaluation,
                'schedule' => $schedule->first(),
                'scheduleTime' => $scheduleTime,
                'scheduleDay' => $scheduleDay,
                'scheduleAll' => $scheduleAll->get(),
                'description' => $description,
                'listFilter'=>$listFilter,
                'timeline'=> $timeline,
                'timeline1'=> $timeline1,
                'timeline2'=> $timeline2,
                'config' => $config,
                "activeID" => $activeFilterID,
                "startSemester" => $start,
                "endSemester" => $end,
                "scheduleWeeks" => $scheduleWeeks,
                "dataSchedules" => $dataSchedules,
            ])->render();

    }

    public function ajaxSearch(Request $request)
    {

        $checkDuplicate = $request->input('checkDuplicate');
        $keyword = explode(",", $request->input('keyword'));
        $schedule_id = $request->input('schedule_id');

        $checkKeyword = $request->input('keyword') ?? null;
        if($checkKeyword){
            $major = Major::whereIn('name', $keyword)->distinct('id')->select('id')->get()->toArray();
            $majorEvaluation = MajorEvaluation::whereIn('major_id', $major)->distinct('evaluation_id')->select('evaluation_id')->get()->toArray();
            $evaluation = Evaluation::whereIn('id', $majorEvaluation)->where('status', 'publish')->orderBy('created_at', 'DESC');
        }else{
            $evaluation = Evaluation::where('status', 'publish')->orderBy('created_at', 'DESC');
        }

        $result = null;
        if ($checkDuplicate == 'true') {
            $lecture = $this->checkDuplicate($evaluation, $schedule_id);
            if ($lecture->count() > 0) {
                foreach ($lecture as $item) {
                    $result = $result . "
                    <tr
                                data-json='" . toJsonLecture($item->datetime, $item->title,$item->id) . "'>
                                <td>
                                <div class='story_btn'>
                                <a href='" . route('campus.evaluation_details', ['id' => $item->id]) . "' title='' target='_blank'>
                                    <img src='/themes/ewhaian/img/classsearch_storybtn.png' alt='Image not found'>
                                </a>
                                </div>

                                </td>
                                <td>" . $item->department . "</td>
                                <td>" . implode(",", getMajor($item->major->pluck('name'), $keyword)) . "</td>
                                <td>" . $item->major_type . " </td>
                                <td>" . $item->course_code . "-".$item->division. " </td>
                                <td>$item->professor_name</td>
                                <td>$item->grade</td>
                                <td>$item->score</td>
                                <td>$item->lecture_room</td>
                                <td>" . date('월m일d', strtotime($item->created_at)) . " </td>
                                <td>$item->remark</td>
                                <td>$item->compete</td>
                        </tr>

                ";
                }
            } else {
                $result = "<tr> <td colspan='11'> ".__('campus.timetable.no_have_item')." </td> </tr>";
            }
        } else {

            if ($evaluation->count() > 0) {
                foreach ($evaluation->get() as $item) {
                    $result = $result . "
                        <tr
                                data-json='" . toJsonLecture($item->datetime, $item->title,$item->id) . "'>
                                <td>
                                <div class='story_btn'>
                                <a href='" . route('campus.evaluation_details', ['id' => $item->id]) . "' title='' target='_blank'>
                                    <img src='/themes/ewhaian/img/classsearch_storybtn.png' alt='Image not found'>
                                </a>
                                </div>

                                </td>
                                <td>" . $item->department . "</td>
                                <td>" . implode(",", getMajor($item->major->pluck('name'), $keyword, $checkKeyword)) . "</td>
                                <td>" . $item->major_type . " </td>
                                <td>" . $item->course_code . " </td>
                                <td>$item->professor_name</td>
                                <td>$item->grade</td>
                                <td>$item->score</td>
                                <td>$item->lecture_room</td>
                                <td>" . date('월m일d', strtotime($item->created_at)) . " </td>
                                <td>$item->remark</td>
                                <td>$item->compete</td>
                        </tr>

                    ";
                }
            } else {
                $result = "<tr> <td colspan='11'>".__('campus.timetable.no_have_item')." </td> </tr>";
            }
        }

        return response()->json(array('result' => $result), 200);

    }

    public function ajaxTimetable(Request $request)
    {

        if ($request->input('schedule_id') == 0) {
            return 'Error03';
        }
        //No have schedule
        $timeline = $request->input('timeline');

        foreach ($timeline as $key => $item) {

            if ($item['from'] >= $item['to']) {

                return 'Error01'; //'From must be smaller than To!!!'
            }

            if ($this->validateTimeLine($item['from'], $item['to'], $item['day'], $request->input('schedule_id')) == false) {
                return 'Error02'; //'Time already exists, please choose another time!!!'
            }

        }
        $lecture = Evaluation::where('id', $request->input('lecture_id'))->where('status', 'publish')->first();
        foreach ($timeline as $key => $item) {

            $scheduleTimeLine = new ScheduleTimeLine;
            $scheduleTimeLine->schedule_id = $request->input('schedule_id');
            $scheduleTimeLine->title = $item['title'];
            $scheduleTimeLine->day = $item['day'];
            $scheduleTimeLine->from = $item['from'];
            $scheduleTimeLine->to = $item['to'];
            $scheduleTimeLine->group_color = $request->input('lecture_id');
            if (!is_null($lecture)) {
                $scheduleTimeLine->lecture_room = $lecture->lecture_room;
                $scheduleTimeLine->professor_name = $lecture->professor_name;
            }

            $scheduleTimeLine->save();
        }

        $schedule = Schedule::where('id_login', auth()->guard('member')->user()->id_login)->where('id', $request->input('schedule_id'))->first();

        if (is_null($schedule)) {
            return 'Error03'; //'시간표를 선택해주세요.!!!'
        }
        if (is_null($schedule->total_credit)) {
            $schedule->total_credit = 0;
        }

        $schedule->total_credit = $schedule->total_credit + $lecture->score;
        $schedule->save();

        $scheduleTime = ScheduleTime::select('from', 'to', 'unit')->orderBy('created_at', 'DESC')->first();
        $scheduleDay = ScheduleDay::where('status', 'publish')->get();
        $scheduleAll = Schedule::where('id_login', auth()->guard('member')->user()->id_login)->where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $description = DescriptionCampus::where('code', 'SCHEDULE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();


        $config = ScheduleConfigMember::where('member_id', auth()->guard('member')->user()->id)->where('schedule_id',$schedule->id)->first();
        $listFilter = ScheduleFilter::where('status', 'publish')->orderBy('created_at','ASC')->get();

        if (!empty($request->input('filter_id'))) {
            $filter = ScheduleFilter::where('id', $request->input('filter_id'))->where('status', 'publish')->firstOrFail();
            $scheduleAll->whereBetween('created_at', [date_format(date_create($filter->start), "Y-m-d 00:00:00"), date_format(date_create($filter->end), "Y-m-d 23:59:59")]);
            $schedule->whereBetween('created_at', [date_format(date_create($filter->start), "Y-m-d 00:00:00"), date_format(date_create($filter->end), "Y-m-d 23:59:59")]);

        } else {
            if(!is_null( $listFilter)) {
               $filter =  ScheduleFilter::where('status', 'publish')->where('start','<=', today())->where('end','>=',today())->first();
               $scheduleAll->whereBetween('created_at', [date_format(date_create($filter->start), "Y-m-d 00:00:00"), date_format(date_create($filter->end), "Y-m-d 23:59:59")]);
               $schedule->whereBetween('created_at', [date_format(date_create($filter->start), "Y-m-d 00:00:00"), date_format(date_create($filter->end), "Y-m-d 23:59:59")]);

            } else {
                $filter = checkQuaterYear();
                $scheduleAll->whereBetween('created_at', [$filter['start'], $filter['end']]);
                $schedule->whereBetween('created_at', [$filter['start'], $filter['end']]);
            }
        }
        $temp = $schedule->timeline()->where('status','publish');

        $timeline1 = (clone $temp)->whereNull('group_color')->get();
        $timeline2 = (clone $temp)->whereNotNull('group_color')->get()->unique('group_color')->values();
        $timeline = (clone $temp)->get();

        return Theme::partial('timetable.top', [
            'schedule' => $schedule,
            'scheduleTime' => $scheduleTime,
            'scheduleDay' => $scheduleDay,
            'scheduleAll' => $scheduleAll,
            'description' => $description,
            'timeline1' =>$timeline1,
            'timeline2' =>$timeline2,
            'timeline' =>$timeline,
            'config' =>  $config,
            'listFilter' =>  $listFilter,
        ]);

    }

    public function copySchedule(Request $request)
    {
        $schedule_copy = Schedule::where('id_login', auth()->guard('member')->user()->id_login)->where('id', $request->input('schedule_id'))->first();
        if (is_null($schedule_copy)) {
            return redirect()->back()->with('err', '시간표를 선택해주세요.');
        }

        $timeline = $schedule_copy->timeline()->where('status', 'publish')->get();
        try {
            DB::beginTransaction();
            $schedule_new = $schedule_copy->replicate();
            $schedule_new->name = $schedule_copy->name . '복사본';
            $schedule_new->total_credit = null;
            $schedule_new->id_login = auth()->guard('member')->user()->id_login;
            $schedule_new->push();

            $activeFilterParam = $request->input('curSem');
            $scheduleIdParam = $schedule_new->id;
            $redirectUrl = 'campus/timetable/schedule/v2?schedule_id=' . $scheduleIdParam . '&active_filter=' . $activeFilterParam;
            foreach ($timeline as $key => $item) {
                $itemCopy = $item->replicate();
                $itemCopy->schedule_id = $schedule_new->id;
                $itemCopy->push();
            }

            DB::commit();
            return redirect($redirectUrl)->with('success', __('campus.timetable.schedule_has_copied'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect($redirectUrl)->with('error', __('controller.save_failed'));
        }
    }

    public function showLecture($id)
    {
        $evaluation = Evaluation::where('id', $id)->where('status', '!=', 'draf')->orderBy('created_at', 'DESC')->first();
        $evaluation->lookup = $evaluation->lookup + 1;
        $evaluation->save();

        return Theme::layout('onlyContent')->scope('campus.timetable.showLecture', ['evaluation' => $evaluation, 'votes' => $evaluation->comments->avg('votes')])->render();
    }

    public function ajaxSaveColor(Request $request) {
        $id  = $request->id;
        $color  = $request->color;
        $scheduleTimeLine = ScheduleTimeLine::find($id);
        $scheduleTimeLine->color = $color;
        $scheduleTimeLine->save();
        if($scheduleTimeLine->group_color > 0) {
            ScheduleTimeLine::where('group_color', $scheduleTimeLine->group_color)
            ->update(['color' => $color]);
        }

        return response()->json(['data' => true], 200);

    }

    public function updateSetting(Request $request)
    {
        $time = $request->config['time'];
        $day = $request->config['day'];
        $show_lecture = $request->show_lecture ?? null;

        if($time['from']  >= $time['to'] ) {
            return redirect()->back()->with('err', '시간표 시작 시간은 시간표 종료 시간 이후로 설정할 수 없습니다.');
        }
        if($day['from']  > $day['to'] ) {
            return redirect()->back()->with('err', '시간표 시작 요일은 시간표 종료 요일과 같아햐합니다. 또는 종료일 이후로 설정할 수 없습니다.');
        }

        $schedule_id = $request->schedule_id;
        Schedule::where('id',$schedule_id)->update([
            'name' => $request->schedule_name,
        ]);
        $config = ScheduleConfigMember::where('schedule_id', $schedule_id)
                    ->where('member_id',auth()->guard('member')->user()->id)->first();
        if(!is_null($config)){
            $config->update([
                'time' =>  $time,
                'day' =>  $day,
                'show_lecture' =>  $show_lecture,
            ]);
        }else {
            ScheduleConfigMember:: create([
                'schedule_id' =>  $request->schedule_id,
                'member_id' =>  auth()->guard('member')->user()->id,
                'time' =>  $time,
                'day' =>  $day,
                'show_lecture' =>  $show_lecture,
            ]);
        }

        return redirect()->back()->with('success', '시간표 설정을 저장하였습니다.');

    }

    public function getTimeLine(Request $request)
    {
        $config = ScheduleConfigMember::where('member_id', auth()->guard('member')->user()->id)->where('schedule_id',$request->schedule_id)->first();
        $timeline = ScheduleTimeLine::where('id', $request->id)->first();

        $template = Theme::partial('timetable.infoSchedulePopup',['timeline' => $timeline, 'config' => $config]);

        return response()->json(['template' => $template], 200);
    }

    public function deleteTimeLine($id) {
        $timeline = ScheduleTimeLine::where('id', $id)->firstOrFail();

        if(!is_null($timeline->group_color)) {
            ScheduleTimeLine::where('group_color', $timeline->group_color)->delete();
        }
        $timeline->delete();

        return redirect()->back()->with('success', '시간표의 일정을 삭제하였습니다.');

    }

}
