<?php

namespace Botble\Contents\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Contents\Http\Requests\CategoriesContentsRequest;
use Botble\Contents\Models\CategoriesContents;

class CategoriesContentsForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(CATEGORIES_CONTENTS_MODULE_SCREEN_NAME)
            ->setValidatorClass(CategoriesContentsRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                     'data-counter' => 120,
                ],
            ])
            ->add('notice', 'textarea', [
                'label' => __('eh-introduction.notices'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder'  => __('eh-introduction.notices'),
                     'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => __('egarden.room.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder'  => __('egarden.room.description'),
                     'data-counter' => 120,
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
            ->add('permisions', 'select', [
                'label'      => __('permission'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => CategoriesContents::getPermissions(),
            ])
            ->setBreakFieldPoint('status');
    }
}
