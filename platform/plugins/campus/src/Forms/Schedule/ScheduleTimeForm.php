<?php

namespace Botble\Campus\Forms\Schedule;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Http\Requests\CampusRequest;
use Botble\Campus\Http\Requests\Schedule\ScheduleRequest;
use Botble\Campus\Http\Requests\Schedule\ScheduleTimeRequest;

class ScheduleTimeForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(SCHEDULE_TIME_MODULE_SCREEN_NAME)
            ->setValidatorClass(ScheduleTimeRequest::class)
            ->withCustomFields()
            ->add('from', 'number', [
                'label' => __('campus.timetable.from'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  =>__('campus.timetable.from'),
                    'data-counter' => 120,
                ],
            ])
            ->add('to', 'number', [
                'label' => __('campus.timetable.to'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  =>__('campus.timetable.to'),
                    'data-counter' => 120,
                ],
            ])
            ->add('unit', 'number', [
                'label' => "단위",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  =>"Default: 1",
                    'data-counter' => 120,
                ],
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
