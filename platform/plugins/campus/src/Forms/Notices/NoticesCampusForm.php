<?php

namespace Botble\Campus\Forms\Notices;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Campus\Http\Requests\Notices\NoticesCampusRequest;

class NoticesCampusForm extends FormAbstract
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
            'EVALUATION_MODULE_SCREEN_NAME' => '강의평가',
        ];
        $this
            ->setModuleName(NOTICES_CAMPUS_MODULE_SCREEN_NAME)
            ->setValidatorClass(NoticesCampusRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('notices', 'textarea', [
                'label' => __('life.shelter_info.notice'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('life.shelter_info.notice'),
                ],
            ])
            ->add('code', 'select', [
                'label' => '대상 게시판',
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
