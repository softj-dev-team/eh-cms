<?php

namespace Botble\Campus\Forms\Schedule;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Forms\Fields\CampusDateTimePicker;
use Botble\Campus\Forms\Fields\MultiSelectField;
use Botble\Campus\Http\Requests\Schedule\ScheduleFilterRequest;
use Botble\Campus\Http\Requests\Schedule\ScheduleRequest;
use Botble\Member\Models\Member;

class ScheduleFilterForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        if ($this->getModel()) {
            $schedule_share = $this->getModel()->member;
       }

        if (!$this->formHelper->hasCustomField('campusDatetimePicker')) {
            $this->formHelper->addCustomField('campusDatetimePicker', CampusDateTimePicker::class);
        }
        $member = Member::all();
        $this
            ->setModuleName(SCHEDULE_FILTER_MODULE_SCREEN_NAME)
            ->setValidatorClass(ScheduleFilterRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('start', 'campusDatetimePicker', [
                'label' => '시작일',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.start'),
                ],
                'id' => 'datetimepicker1',
            ])
            ->add('end', 'campusDatetimePicker', [
                'label' => '종료일',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.end'),
                ],
                'id' => 'datetimepicker2',
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
