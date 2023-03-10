<?php

namespace Botble\Life\Forms\Jobs;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Forms\Fields\CategoriesField;
use Botble\Life\Forms\Fields\AttachiamentaField;
use Botble\Life\Forms\Fields\CategoriesNoParentsField;
use Botble\Life\Http\Requests\Jobs\JobsPartTimeRequest;
use Botble\Life\Models\Jobs\JobsCategories;

class JobsPartTimeForm extends FormAbstract
{


    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        if ($this->getModel()) {
             $selected_categories = $this->getModel()->categories ;
             $selected_file_upload =  $this->getModel()->file_upload  ;
             $selected_link =  $this->getModel()->link ;
        }
        if (!$this->formHelper->hasCustomField('categories')) {
            $this->formHelper->addCustomField('categories', CategoriesField::class);
        }
        if (!$this->formHelper->hasCustomField('attachiamenta')) {
            $this->formHelper->addCustomField('attachiamenta', AttachiamentaField::class);
        }

        $this
            ->setModuleName(JOBS_PART_TIME_MODULE_SCREEN_NAME)
            ->setValidatorClass(JobsPartTimeRequest::class)
            ->withCustomFields()
            ->add('categories', 'categories', [
                'value'=> old('categories', $selected_categories ?? null),
                'data-value' => JobsCategories::where('status','publish')->get()
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
                'label' => __('campus.study_room.contact'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('campus.study_room.contact'),
                    'data-counter' => 120,
                ],
            ])
            ->add('detail', 'editor', [
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
//                'choices'    => ["publish" => "모집중", "pending" => "보류", "Draft"=>"초안"]
                'choices' => ["publish" => trans('core/base::tables.publish'), "pending" => trans('core/base::tables.pending'), "approve" => trans('core/base::tables.completed'),'draft' => trans('core/base::tables.draft')],

            ])
            ->setBreakFieldPoint('images');
    }
}
