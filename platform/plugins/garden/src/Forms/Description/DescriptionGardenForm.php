<?php

namespace Botble\Garden\Forms\Description;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Garden\Http\Requests\Description\DescriptionGardenRequest;
use Botble\Garden\Http\Requests\GardenRequest;
use Botble\Garden\Models\CategoriesGarden;
use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;

class   DescriptionGardenForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $selected_categories = [];
        if ($this->getModel()) {

            $selected_categories = $this->getModel()->categories;
            $categories = CategoriesGarden::where('status', 'publish')->get();

        }

        if (empty($selected_categories)) {
            $categories = app(CategoriesGardenInterface::class)->getModel()->all();

        }

        $this
            ->setModuleName(DESCRIPTION_GARDEN_MODULE_SCREEN_NAME)
            ->setValidatorClass(DescriptionGardenRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::tables.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => 'Title',
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => __('egarden.room.description'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => 'Description',
                ],
            ])
            ->add('categories_gardens_id', 'select', [
                'label' => trans('core/base::tables.category'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => getCategoryEventsName($categories),
                'value' => old('categories_gardens_id', $selected_categories),
            ])
            ->add('status', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('categories_gardens_id');
    }
}
