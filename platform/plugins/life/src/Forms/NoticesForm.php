<?php

namespace Botble\Life\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Life\Http\Requests\NoticesRequest;

class NoticesForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $code = ['FLARE_MODULE_SCREEN_NAME'=>'벼룩시장',
                'JOBS_PART_TIME_MODULE_SCREEN_NAME'=>'알바하자',
                'ADS_MODULE_SCREEN_NAME'=>'광고홍보',
                'SHELTER_MODULE_SCREEN_NAME'=>'주거정보',
                'OPEN_SPACE_MODULE_SCREEN_NAME' => '열린광장',
        ];
        $this
            ->setModuleName(NOTICES_MODULE_SCREEN_NAME)
            ->setValidatorClass(NoticesRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('notices', 'textarea', [
                'label' => __('life.flea_market.notice'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('life.flea_market.notice'),
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
