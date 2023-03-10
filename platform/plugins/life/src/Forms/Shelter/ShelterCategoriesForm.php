<?php

namespace Botble\Life\Forms\Shelter;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Life\Http\Requests\Shelter\ShelterCategoriesRequest;

class ShelterCategoriesForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(SHELTER_CATEGORIES_MODULE_SCREEN_NAME)
            ->setValidatorClass(ShelterCategoriesRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])

            ->add('background', 'text', [
                'label' => "Background",
                'label_attr' => ['class' => 'control-label required '],
                'attr' => [
                    'data-counter' => 120,
                    'class' =>'form-control jscolor ',

                ],
            ])
            ->add('color', 'text', [
                'label' => "Color text",
                'label_attr' => ['class' => 'control-label required '],
                'attr' => [
                    'data-counter' => 120,
                    'class' =>'form-control jscolor '
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
