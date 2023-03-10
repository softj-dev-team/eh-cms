<?php

namespace Botble\Campus\Forms\Evaluation;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Campus\Forms\Fields\MutilDateTimePicker;
use Botble\Campus\Forms\Fields\SelectPickerField;
use Botble\Campus\Forms\Fields\TimeEvaluationField;
use Botble\Campus\Http\Requests\Evaluation\EvaluationRequest;
use Botble\Campus\Models\Evaluation\Major;
use Botble\Campus\Models\Schedule\ScheduleDay;
use Botble\Campus\Models\Schedule\ScheduleTime;
use Botble\CampusLastday\Forms\Fields\CampusLastdayDateTimePicker;

class EvaluationForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        if (!$this->formHelper->hasCustomField('campusLastdayDateTimePicker')) {
            $this->formHelper->addCustomField('campusLastdayDateTimePicker', CampusLastdayDateTimePicker::class);
        }
        if (!$this->formHelper->hasCustomField('mutilDateTimePicker')) {
            $this->formHelper->addCustomField('mutilDateTimePicker', MutilDateTimePicker::class);
        }

        if (!$this->formHelper->hasCustomField('timeEvaluation')) {
            $this->formHelper->addCustomField('timeEvaluation', TimeEvaluationField::class);
        }

        if (!$this->formHelper->hasCustomField('selectPicker')) {
            $this->formHelper->addCustomField('selectPicker', SelectPickerField::class);
        }

        $time = ScheduleTime::where('status', 'publish')->first();
        $day = ScheduleDay::where('status', 'publish')->get();

        if ($this->getModel()) {
            $selected_datetime = json_decode($this->getModel()->datetime, true);
            $id = $this->getModel()->id;
        }

        $this
            ->setModuleName(EVALUATION_MODULE_SCREEN_NAME)
            ->setValidatorClass(EvaluationRequest::class)
            ->withCustomFields()
            ->add('course_code', 'text', [
                'label' => __('campus.timetable.course_code'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' =>__('campus.timetable.course_code'),
                    'data-counter' => 120,
                ],
            ])
            ->add('title', 'text', [
                'label' => '교과목명',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('division', 'text', [
                'label' => __('campus.timetable.division'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => __('campus.timetable.division'),
                ],
            ])
            ->add('class_type', 'text', [
                'label' => '교과목 구분',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => '교과목 구분',
                ],
            ])
//            ->add('department', 'text', [
//                'label' => '개설학과/전공',
//                'label_attr' => ['class' => 'control-label required'],
//                'attr' => [
//                    'placeholder' => '개설학과/전공',
//                ],
//            ])
            ->add('is_major', 'checkbox', [
                'label' => '전공여부',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->is_major ?? 1,
            ])
            ->add('major', 'selectPicker', [
                'major' => Major::where('parents_id','0')->where('status', 'publish')->get(),
                'evaluation_id' => $id ?? null,
            ])
            ->add('major_type', 'text', [
                'label' => '교과 영역',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => '교과 영역',
                ],
            ])
            ->add('professor_name', 'text', [
                'label' => '교수명',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '교수명',
                ],
            ])
            ->add('year', 'number', [
                'label' => '연도',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '연도',
                ],
            ])
            ->add('semester', 'select', [
                'label' => '학기',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '학기',
                ],
                'choices' => ['연도-1학기', '연도-2학기', '여름계절', '겨울계절' ]
            ])
            ->add('score', 'text', [
                'label' => '학점',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '학점',
                    'data-counter' => 120,
                ],
            ])
            ->add('grade', 'text', [
                'label' => '학년',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '학년',
                    'data-counter' => 120,
                ],
            ])
//            ->add('class_hours', 'text', [
//                'label' => '시간',
//                'label_attr' => ['class' => 'control-label required'],
//                'attr' => [
//                    'placeholder' => '시간'
//                ],
//            ])
            ->add('english_class', 'checkbox', [
                'label' => '영어 수업',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->english_class ?? 1,
            ])
            ->add('humanities_class', 'checkbox', [
                'label' => '인문학관련 교양과목',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->humanities_class ?? 1,
            ])
            ->add('sw_class', 'checkbox', [
                'label' => '원격 수업',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->sw_class ?? 1,
            ])
            ->add('quota', 'number', [
                'label' => '정원',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => '정원'
                ],
            ])
            ->add('online_class', 'checkbox', [
                'label' => '온라인 수업',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => '온라인 수업'
                ],
            ])
            ->add('lecture_room', 'text', [
                'label' => '강의실',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => '강의실'
                ],
            ])
            ->add('remark', 'text', [
                'label' => '비고',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('datetime', 'timeEvaluation', [
                'label' => [
                    '1' => '요일',
                    '2' => '시작시간',
                    '3' => '종료시간',
                ],
                'label_attr' => ['class' => 'control-label required'],
                'data' => [
                    'day' => $day,
                    'from' => $time->from, //data for dropdowlist
                    'to' => $time->to,
                ],
                'data-selected' => $selected_datetime ?? null, // data for selected value
            ])
            ->add('status', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => ['publish'=>trans('core/base::tables.publish'),'draft'=>trans('core/base::tables.draft'),'pending'=>trans('core/base::tables.end')],
            ])
            ->setBreakFieldPoint('status');
    }
}
