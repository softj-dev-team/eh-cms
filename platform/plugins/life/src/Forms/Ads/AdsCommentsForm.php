<?php

namespace Botble\Life\Forms\Ads;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Life\Http\Requests\Ads\AdsCommentsRequest;

class AdsCommentsForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(ADS_COMMENTS_MODULE_SCREEN_NAME)
            ->setValidatorClass(AdsCommentsRequest::class)
            ->withCustomFields()
            ->add('content', 'text', [
                'label' => 'Content',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => 'Contents',
                     'data-counter' => 120,
                ],
            ])
            ->add('anonymous', 'onOff', [
                'label'      => 'Anonymous',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'value'    => 1,
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
