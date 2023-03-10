<?php

namespace Botble\Introduction\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Introduction\Http\Requests\CategoriesIntroductionRequest;

class CategoriesIntroductionForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(CATEGORIES_INTRODUCTION_MODULE_SCREEN_NAME)
            ->setValidatorClass(CategoriesIntroductionRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => __('eh-introduction.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('eh-introduction.name'),
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
