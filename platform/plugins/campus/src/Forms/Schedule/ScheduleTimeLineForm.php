<?php

namespace Botble\Campus\Forms\Schedule;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;

use Botble\Campus\Forms\Fields\CampusTimeColorField;
use Botble\Campus\Forms\Fields\CampusTimePickerField;
use Botble\Campus\Http\Requests\Schedule\ScheduleTimeLineRequest;
use Botble\Campus\Models\Schedule\ScheduleDay;
use Botble\Campus\Models\Schedule\ScheduleTime;
use Botble\Campus\Forms\Fields\TimeEvaluationField;

class ScheduleTimeLineForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $selected_datetime = [];
        if ($this->getModel()) {
            // $selected_from = $this->getModel()->from ;
            // $selected_to = $this->getModel()->to ;
            // $selected_day = $this->getModel()->day ;
            $selected_datetime = $this->getModel()->datetime;
       }

        if (!$this->formHelper->hasCustomField('campusTimePicker')) {
            $this->formHelper->addCustomField('campusTimePicker', CampusTimePickerField::class);
        }
        if (!$this->formHelper->hasCustomField('CampusTimeColorField')) {
            $this->formHelper->addCustomField('CampusTimeColorField', CampusTimeColorField::class);
        }

        if (!$this->formHelper->hasCustomField('timeEvaluation')) {
            $this->formHelper->addCustomField('timeEvaluation', TimeEvaluationField::class);
        }

        $time = ScheduleTime::where('status', 'publish')->first();
        $day = ScheduleDay::where('status', 'publish')->get();
        $this
            ->setModuleName(SCHEDULE_TIMELINE_MODULE_SCREEN_NAME)
            ->setValidatorClass(ScheduleTimeLineRequest::class)
            ->withCustomFields()
            ->add('schedule_id', 'hidden', [
                'value'=> $this->idSchedule
            ])
            ->add('title', 'text', [
                'label' => __('campus.timetable.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('campus.timetable.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('lecture_room', 'text', [
                'label' => __('campus.timetable.lecture_room'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('campus.timetable.lecture_room'),
                    'data-counter' => 120,
                ],
            ])
            ->add('professor_name', 'text', [
                'label' => __('campus.timetable.professor_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('campus.timetable.professor_name'),
                    'data-counter' => 120,
                ],
            ])
            // ->add('is_change_Time', 'checkbox', [
            //     'label'      => '변경 시간입니다',
            //     'label_attr' => ['class' => 'control-label'],
            //     'attr'       => [
            //         'class' => 'hrv-checkbox',
            //     ],
            //     'wrapper'    => [
            //         'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel() ? null : ' hidden'),
            //     ],
            //     'value'      => 1,
            // ])
            // ->add('day', 'campusTimePicker', [
            //     'label' => '시작일',
            //     'label_attr' => ['class' => 'control-label required'],
            //     'attr' => [
            //         'placeholder'  => trans('core/base::forms.start'),
            //     ],
            //     'data-value' => ScheduleDay::where('status','publish')->get(),
            //     'typeSelect' =>'1',
            //     'value' => $selected_day ?? null,
            //     'wrapper'    => [
            //         'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel() ? ' hidden' : null),
            //     ],
            // ])
        //    ->add('end_day', 'campusTimePicker', [
        //        'label' => '종료일',
        //        'label_attr' => ['class' => 'control-label required'],
        //        'attr' => [
        //            'placeholder'  => trans('core/base::forms.start'),
        //        ],
        //        'data-value' => ScheduleDay::where('status','publish')->get(),
        //        'typeSelect' =>'1',
        //        'value' => $selected_day ?? null,
        //        'wrapper'    => [
        //            'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel() ? ' hidden' : null),
        //        ],
        //    ])
            // ->add('from', 'campusTimePicker', [
            //     'label' => __('message.from'),
            //     'label_attr' => ['class' => 'control-label required'],
            //     'attr' => [
            //         'placeholder'  => trans('core/base::forms.end'),
            //     ],
            //     'data-value' => ScheduleTime::where('status','publish')->orderBy('created_at','DESC')->first(),
            //     'value' => $selected_from ?? null

            // ])
            // ->add('to', 'campusTimePicker', [
            //     'label' => __('campus.timetable.to'),
            //     'label_attr' => ['class' => 'control-label required'],
            //     'attr' => [
            //         'placeholder'  => trans('core/base::forms.end'),
            //     ],
            //     'data-value' => ScheduleTime::where('status','publish')->orderBy('created_at','DESC')->first(),
            //     'value' => $selected_to ?? null
            // ])
            ->add('group_color', 'text', [
                'label' => __('campus.study_room.background'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'data-counter' => 120,
                    'class' =>'form-control jscolor ',

                ],
            ])
            ->add('color', 'text', [
                'label' => __('campus.study_room.color_text'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'data-counter' => 120,
                    'class' =>'form-control jscolor '
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
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('status');
    }
}
