<?php

namespace Botble\Campus\Forms\OldGenealogy;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Campus\Forms\Fields\AttachiamentaField;
use Botble\Campus\Forms\Fields\MultipleUploadField;
use Botble\Campus\Http\Requests\OldGenealogy\OldGenealogyRequest;

class OldGenealogyForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {

        if ($this->getModel()) {
            $selected_images = $this->getModel()->images;
            $selected_file_upload =  $this->getModel()->file_upload  ;
            $selected_link =  $this->getModel()->link ;
        }
        if (!$this->formHelper->hasCustomField('multiple_upload')) {
            $this->formHelper->addCustomField('multiple_upload', MultipleUploadField::class);
        }
        if (!$this->formHelper->hasCustomField('attachiamenta')) {
            $this->formHelper->addCustomField('attachiamenta', AttachiamentaField::class);
        }
        $this
            ->setModuleName(OLD_GENEALOGY_MODULE_SCREEN_NAME)
            ->setValidatorClass(OldGenealogyRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => trans('core/base::tables.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::tables.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('detail', 'editor', [
                'label' => trans('core/base::tables.detail'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::tables.detail'),
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
