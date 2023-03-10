<?php

namespace Botble\Life\Forms\Shelter;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Life\Forms\Fields\AttachiamentaField;
use Botble\Life\Forms\Fields\CategoriesNoParentsField;
use Botble\Life\Forms\Fields\MultipleUploadField;
use Botble\Life\Http\Requests\Shelter\ShelterRequest;
use Botble\Life\Models\Shelter\ShelterCategories;

class ShelterForm extends FormAbstract
{


    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        if ($this->getModel()) {
             $selected_categories = $this->getModel()->categories ;
             $selected_images = $this->getModel()->images;
             $selected_file_upload =  $this->getModel()->file_upload  ;
             $selected_link =  $this->getModel()->link ;
        }
        if (!$this->formHelper->hasCustomField('categoriesNoParent')) {
            $this->formHelper->addCustomField('categoriesNoParent', CategoriesNoParentsField::class);
        }
        if (!$this->formHelper->hasCustomField('multiple_upload')) {
            $this->formHelper->addCustomField('multiple_upload', MultipleUploadField::class);
        }
        if (!$this->formHelper->hasCustomField('attachiamenta')) {
            $this->formHelper->addCustomField('attachiamenta', AttachiamentaField::class);
        }
        $this
            ->setModuleName(SHELTER_MODULE_SCREEN_NAME)
            ->setValidatorClass(ShelterRequest::class)
            ->withCustomFields()
            ->add('categories', 'categoriesNoParent', [
                'value'=> old('categories', $selected_categories ?? null),
                'data-value' => ShelterCategories::where('status','publish')->get()
            ])
            ->add('title', 'text', [
                'label' => trans('core/base::tables.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::tables.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('contact', 'text', [
                'label' => __('life.flea_market.contact'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('life.flea_market.contact'),
                    'data-counter' => 120,
                ],
            ])
            ->add('detail', 'editor', [
                'label' => __('life.flea_market.detail'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder'  => __('life.flea_market.detail'),
                ],
            ])
            ->add('images[]', 'multiple_upload', [
                'value' => old('images', $selected_images ?? null),
            ])
            ->add('attachiamenta', 'attachiamenta', [
                'file_upload'=> $selected_file_upload ?? null,
                'link' =>  $selected_link ?? null,
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
