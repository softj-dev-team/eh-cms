<?php

namespace Botble\Garden\Forms\Notices;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Garden\Http\Requests\Notices\NoticesGardenRequest;
use Botble\Garden\Models\CategoriesGarden;
use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;

class NoticesGardenForm extends FormAbstract
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
            ->setModuleName(NOTICES_GARDEN_MODULE_SCREEN_NAME)
            ->setValidatorClass(NoticesGardenRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::tables.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::tables.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('link', 'text', [
                'label' => '링크',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '링크',
                ],
            ])
            ->add('notices', 'textarea', [
                'label' => __('eh-introduction.notices'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => 'Notices',
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
