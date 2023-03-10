<?php

namespace Botble\NewContents\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\NewContents\Http\Requests\CategoriesNewContentsRequest;
use Botble\NewContents\Http\Requests\CommentsNewContentsRequest;

class CommentsNewContentsForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(COMMENTS_NEW_CONTENTS_MODULE_SCREEN_NAME)
            ->setValidatorClass(CommentsNewContentsRequest::class)
            ->withCustomFields()
            ->add('content', 'text', [
                'label' => 'Contents',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => "Contents",
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
