<?php

namespace Botble\CampusLastday\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\CampusLastday\Forms\Fields\CampusLastdayDateTimePicker;
use Botble\CampusLastday\Http\Requests\CampusLastdayRequest;

class CampusLastdayForm extends FormAbstract
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
        $this
            ->setModuleName(CAMPUS_LASTDAY_MODULE_SCREEN_NAME)
            ->setValidatorClass(CampusLastdayRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
//            ->add('start', 'campusDatetimePicker', [
//                'label' => 'Start date',
//                'label_attr' => ['class' => 'control-label required'],
//                'attr' => [
//                    'placeholder'  => trans('core/base::forms.start'),
//                ],
//                'id' => 'datetimepicker1',
//            ])
            ->add('start', 'text', [
                'label'         => __('시작일'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'class'            => 'form-control datepicker',
                    'data-date-format' => 'yyyy-mm-dd',
                ],
            ])
            ->add('end', 'text', [
                'label'         => __('종료일'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'class'            => 'form-control datepicker',
                    'data-date-format' => 'yyyy-mm-dd',
                ],
            ])
            ->add('year', 'text', [
                'label'         => __('연도'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'class'            => 'form-control',
                    'placeholder'  => trans('숫자4자리'),
                ],
            ])
            ->add('semester', 'text', [
                'label'         => __('학기'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'class'            => 'form-control',
                    'placeholder'  => trans('숫자1자리'),
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
