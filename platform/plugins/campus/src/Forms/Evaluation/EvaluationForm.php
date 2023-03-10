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
                'label' => '????????????',
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
                'label' => '????????? ??????',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => '????????? ??????',
                ],
            ])
//            ->add('department', 'text', [
//                'label' => '????????????/??????',
//                'label_attr' => ['class' => 'control-label required'],
//                'attr' => [
//                    'placeholder' => '????????????/??????',
//                ],
//            ])
            ->add('is_major', 'checkbox', [
                'label' => '????????????',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->is_major ?? 1,
            ])
            ->add('major', 'selectPicker', [
                'major' => Major::where('parents_id','0')->where('status', 'publish')->get(),
                'evaluation_id' => $id ?? null,
            ])
            ->add('major_type', 'text', [
                'label' => '?????? ??????',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => '?????? ??????',
                ],
            ])
            ->add('professor_name', 'text', [
                'label' => '?????????',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '?????????',
                ],
            ])
            ->add('year', 'number', [
                'label' => '??????',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '??????',
                ],
            ])
            ->add('semester', 'select', [
                'label' => '??????',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '??????',
                ],
                'choices' => ['??????-1??????', '??????-2??????', '????????????', '????????????' ]
            ])
            ->add('score', 'text', [
                'label' => '??????',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '??????',
                    'data-counter' => 120,
                ],
            ])
            ->add('grade', 'text', [
                'label' => '??????',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '??????',
                    'data-counter' => 120,
                ],
            ])
//            ->add('class_hours', 'text', [
//                'label' => '??????',
//                'label_attr' => ['class' => 'control-label required'],
//                'attr' => [
//                    'placeholder' => '??????'
//                ],
//            ])
            ->add('english_class', 'checkbox', [
                'label' => '?????? ??????',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->english_class ?? 1,
            ])
            ->add('humanities_class', 'checkbox', [
                'label' => '??????????????? ????????????',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->humanities_class ?? 1,
            ])
            ->add('sw_class', 'checkbox', [
                'label' => '?????? ??????',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->sw_class ?? 1,
            ])
            ->add('quota', 'number', [
                'label' => '??????',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => '??????'
                ],
            ])
            ->add('online_class', 'checkbox', [
                'label' => '????????? ??????',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => '????????? ??????'
                ],
            ])
            ->add('lecture_room', 'text', [
                'label' => '?????????',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => '?????????'
                ],
            ])
            ->add('remark', 'text', [
                'label' => '??????',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('datetime', 'timeEvaluation', [
                'label' => [
                    '1' => '??????',
                    '2' => '????????????',
                    '3' => '????????????',
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
