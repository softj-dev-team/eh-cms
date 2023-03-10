<?php

namespace Botble\Garden\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Garden\Http\Requests\CategoriesGardenRequest;
use Botble\Garden\Models\CategoriesGarden;

class CategoriesGardenForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $special_garden = CategoriesGarden::getSpecial();
        $this
            ->setModuleName(CATEGORIES_GARDEN_MODULE_SCREEN_NAME)
            ->setValidatorClass(CategoriesGardenRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('level_access', 'number', [
                'label' => "액세스 레벨",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => "액세스 레벨",
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
            ->add('special_garden', 'select', [
                'label'      => "권한",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => $special_garden,
            ])
            ->setBreakFieldPoint('level_access');
    }
}
