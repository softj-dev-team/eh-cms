<?php

namespace Botble\Campus\Forms\Description;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Campus\Http\Requests\Description\DescriptionCampusRequest;

class DescriptionCampusForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $code = ['STUDY_ROOM_MODULE_SCREEN_NAME' => '스터디룸',
            'GENEALOGY_MODULE_SCREEN_NAME' => '이화계보',
            'OLD_GENEALOGY_MODULE_SCREEN_NAME' => '지난계보',
            'EVALUATION_MODULE_SCREEN_NAME' => '평점계산기',
            'SCHEDULE_MODULE_SCREEN_NAME' => '시간표',
        ];

        $this
            ->setModuleName(DESCRIPTION_CAMPUS_MODULE_SCREEN_NAME)
            ->setValidatorClass(DescriptionCampusRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => __('egarden.room.description'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('egarden.room.description'),
                ],
            ])
            ->add('code', 'select', [
                'label' => __('life.advertisements.belong'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => $code,
            ])
            ->add('status', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('code');
    }
}
