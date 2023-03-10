<?php

namespace Botble\Life\Forms\Ads;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Life\Forms\Fields\AttachiamentaField;
use Botble\Life\Forms\Fields\CategoriesNoParentsField;
use Botble\Life\Forms\Fields\DateTimePicker;
use Botble\Life\Http\Requests\Ads\AdsRequest;
use Botble\Life\Models\Ads\AdsCategories;

class AdsForm extends FormAbstract
{


    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $selected_deadline = null;
        $is_show = 1;
        if ($this->getModel()) {
             $selected_categories = $this->getModel()->categories ;
             $selected_file_upload =  $this->getModel()->file_upload  ;
             $selected_link =  $this->getModel()->link ;
             $selected_deadline = [$this->getModel()->start,$this->getModel()->deadline ];
             $is_show = $this->getModel()->is_deadline ;
        }
        if (!$this->formHelper->hasCustomField('categoriesNoParent')) {
            $this->formHelper->addCustomField('categoriesNoParent', CategoriesNoParentsField::class);
        }
        if (!$this->formHelper->hasCustomField('attachiamenta')) {
            $this->formHelper->addCustomField('attachiamenta', AttachiamentaField::class);
        }
        if (!$this->formHelper->hasCustomField('datetime-picker')) {
            $this->formHelper->addCustomField('datetime-picker', DateTimePicker::class);
        }

        $this
            ->setModuleName(ADS_MODULE_SCREEN_NAME)
            ->setValidatorClass(AdsRequest::class)
            ->withCustomFields()
            ->add('categories', 'categoriesNoParent', [
                'value'=> old('categories', $selected_categories ?? null),
                'data-value' => AdsCategories::where('status','publish')->get()
            ])
            ->add('title', 'text', [
                'label' => trans('core/base::tables.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::tables.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('duration', 'text', [
                'label' => __('life.advertisements.duration_activity'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder'  =>__('life.advertisements.duration_activity'),
                    'data-counter' => 120,
                ],
            ])
            ->add('recruitment', 'text', [
                'label' => __('life.advertisements.recruitment_no'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder'  => __('life.advertisements.recruitment_no'),
                    'data-counter' => 120,
                ],
            ])
            ->add('contact', 'text', [
                'label' => __('life.flea_market.contact'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder'  => __('life.flea_market.contact'),
                    'data-counter' => 120,
                ],
            ])
            ->add('is_deadline', 'checkbox', [
                'label' => __('life.advertisements.is_deadline'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'hrv-checkbox',
                    'checked'=> $is_show == 1 ? true : false
                ],
                'value'      => 1,
            ])
            ->add('set_deadline', 'datetime-picker', [
                'label_attr' => ['class' => 'control-label '],
                'data-value' => $selected_deadline ?? null,
                'is_show'=> $is_show
            ])
            ->add('details', 'editor', [
                'label' => __('life.flea_market.detail'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('life.flea_market.detail'),
                ],
            ])
            ->add('attachiamenta', 'attachiamenta', [
                'file_upload'=> $selected_file_upload ?? null,
                'link' =>  $selected_link ?? null,
            ])
            ->add('images', 'mediaImage', [
                'label' => __('life.flea_market.image'),
                'label_attr' => ['class' => 'control-label required'],
            ])
            ->add('status', 'select', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('images');
    }
}
