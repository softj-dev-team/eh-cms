<?php

namespace Botble\Campus\Forms\OldGenealogy;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Campus\Http\Requests\OldGenealogy\OldGenealogyCommentsRequest;

class OldGenealogyCommentsForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(OLD_GENEALOGY_COMMENTS_MODULE_SCREEN_NAME)
            ->setValidatorClass(OldGenealogyCommentsRequest::class)
            ->withCustomFields()
            ->add('content', 'text', [
                'label' => __('master_room.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('master_room.content'),
                    'data-counter' => 120,
                ],
            ])
            ->add('anonymous', 'onOff', [
                'label' => __('comments.anonymous'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'value' => 1,
            ])
            ->add('status', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('status');
    }
}
