<?php

namespace Botble\NewContents\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\NewContents\Forms\Fields\AttachiamentaNCField;
use Botble\NewContents\Forms\Fields\NewContentsDateTimePicker;
use Botble\NewContents\Http\Requests\NewContentsRequest;
use Botble\NewContents\Repositories\Interfaces\CategoriesNewContentsInterface;

class NewContentsForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $selected_categories = [];
        if ($this->getModel()) {
            $selected_categories = $this->model->categories->name;
            $categories = $this->getModel()->categories->get();
            $selected_file_upload =  $this->getModel()->file_upload  ;
            $selected_link =  $this->getModel()->link ;
        }

        if (empty($selected_categories)) {
            $categories = app(CategoriesNewContentsInterface::class)->getModel()->all();

        }

        if (!$this->formHelper->hasCustomField('datetime-picker')) {
            $this->formHelper->addCustomField('datetime-picker', NewContentsDateTimePicker::class);
        }
        if (!$this->formHelper->hasCustomField('attachiamenta-nc')) {
            $this->formHelper->addCustomField('attachiamenta-nc', AttachiamentaNCField::class);
        }

        $this
            ->setModuleName(NEW_CONTENTS_MODULE_SCREEN_NAME)
            ->setValidatorClass(NewContentsRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => trans('core/base::forms.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.title'),
                     'data-counter' => 120,
                ],
            ])
            ->add('start', 'datetime-picker', [
                'label' => __('start_date'),
                'label_attr' => ['class' => 'control-label required'],
                'id' => 'datetimepicker1',
            ])
            ->add('end', 'datetime-picker', [
                'label' => __('end_date'),
                'label_attr' => ['class' => 'control-label required'],
                'id' => 'datetimepicker2',
            ])
            ->add('enrollment_limit', 'number', [
                'label' => __('enrollment_limit'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('enrollment_limit'),
                    'data-counter' => 11,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => __('new_contents.description'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('new_contents.description'),
                ],
            ])
            ->add('notice', 'textarea', [
                'label' => __('new_contents.notice'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('new_contents.notice'),
                ],
            ])
            ->add('content', 'editor', [
                'label' => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.content'),
                ],
            ])
            ->add('attachiamenta', 'attachiamenta-nc', [
                'file_upload'=> $selected_file_upload ?? null,
                'link' =>  $selected_link ?? null,
            ])
            ->add('banner', 'mediaImage', [
                'label' => __('new_contents.banner'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder'  =>  __('new_contents.banner'),
                ],
            ])
            ->add('categories_new_contents_id', 'select', [
                'label' => __('new_contents.categories'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => getCategoryEventsName($categories),
                'value'      => old('categories_new_contents_id',$selected_categories),
            ])
            ->add('status', 'select', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('banner');
    }
}
