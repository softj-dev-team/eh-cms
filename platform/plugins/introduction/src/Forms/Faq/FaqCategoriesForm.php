<?php

namespace Botble\Introduction\Forms\Faq;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Introduction\Http\Requests\Faq\FaqCategoriesRequest;

class FaqCategoriesForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(FAQ_CATEGORIES_MODULE_SCREEN_NAME)
            ->setValidatorClass(FaqCategoriesRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => __('contents.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('contents.title'),
                    'data-counter' => 120,
                ],
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
