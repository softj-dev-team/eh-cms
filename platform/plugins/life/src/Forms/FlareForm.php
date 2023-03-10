<?php

namespace Botble\Life\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Life\Forms\Fields\AttachiamentaField;
use Botble\Life\Forms\Fields\CategoriesField;
use Botble\Life\Forms\Fields\ExchangeField;
use Botble\Life\Forms\Fields\MultipleUploadField;
use Botble\Life\Http\Requests\FlareRequest;
use Botble\Life\Models\FlareCategories;

class FlareForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $selected_exchange = [];
        $selected_categories = [];

        if ($this->getModel()) {
            $selected_exchange = $this->getModel()->exchange;
            $selected_images = $this->getModel()->images;
            $selected_categories = $this->getModel()->categories;
            $selected_file_upload =  $this->getModel()->file_upload  ;
            $selected_link =  $this->getModel()->link ;
        }

        if (!$this->formHelper->hasCustomField('categories')) {
            $this->formHelper->addCustomField('categories', CategoriesField::class);
        }
        if (!$this->formHelper->hasCustomField('exchange')) {
            $this->formHelper->addCustomField('exchange', ExchangeField::class);
        }
        if (!$this->formHelper->hasCustomField('multiple_upload')) {
            $this->formHelper->addCustomField('multiple_upload', MultipleUploadField::class);
        }
        if (!$this->formHelper->hasCustomField('attachiamenta')) {
            $this->formHelper->addCustomField('attachiamenta', AttachiamentaField::class);
        }
        $this
            ->setModuleName(FLARE_MODULE_SCREEN_NAME)
            ->setValidatorClass(FlareRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => trans('core/base::tables.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::tables.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('purchasing_price', 'text', [
                'label' => __('life.flea_market.purchasing_price'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('life.flea_market.purchasing_price'),
                    'data-counter' => 120,
                ],
            ])
            ->add('reason_selling', 'text', [
                'label' => __('life.flea_market.reason_selling'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('life.flea_market.reason_selling'),
                    'data-counter' => 120,
                ],
            ])
            ->add('sale_price', 'text', [
                'label' => __('life.flea_market.sale_price'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('life.flea_market.sale_price'),
                    'data-counter' => 120,
                ],
            ])
            ->add('contact', 'text', [
                'label' => __('life.flea_market.contact'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('life.flea_market.contact'),
                    'data-counter' => 120,
                ],
            ])
            ->add('detail', 'editor', [
                'label' => __('life.flea_market.detail'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('life.flea_market.detail'),
                ],
            ])
            ->add('images[]', 'multiple_upload', [
                'value' => old('images', $selected_images ?? null),
            ])
            ->add('exchange[]', 'exchange', [
                'value' => old('exchange', $selected_exchange ?? null),
            ])
            ->add('categories[]', 'categories', [
                'value' => old('categories', $selected_categories ?? null),
                'data-value' => FlareCategories::where('status', 'publish')->get(),
            ])
            ->add('attachiamenta', 'attachiamenta', [
                'file_upload'=> $selected_file_upload ?? null,
                'link' =>  $selected_link ?? null,
            ])
            ->add('status', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => ["publish" => trans('core/base::tables.publish'), "pending" => trans('core/base::tables.pending'), "completed" => trans('core/base::tables.completed'),'draft' => trans('core/base::tables.draft')],
            ])
            ->setBreakFieldPoint('status');
    }
}
