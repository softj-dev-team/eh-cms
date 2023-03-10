<?php

namespace Botble\Contents\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Contents\Forms\Fields\AttachiamentaContentField;
use Botble\Contents\Forms\Fields\ContentsDateTimePicker;
use Botble\Contents\Http\Requests\ContentsRequest;
use Botble\Contents\Repositories\Interfaces\CategoriesContentsInterface;

class ContentsForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $selected_categories = [];
        if ($this->getModel()) {

            $temp = $this->getModel()->get();
            $selected_categories =  $temp[0]->categories_contents ? $temp[0]->categories_contents->name : null;
            $categories = $this->getModel()->categories_contents->get();
            $selected_file_upload =  $this->getModel()->file_upload  ;
            $selected_link =  $this->getModel()->link ;
        }

        if (empty($selected_categories)) {
            $categories = app(CategoriesContentsInterface::class)->getModel()->all();

        }
        if (!$this->formHelper->hasCustomField('attachiamenta-content')) {
            $this->formHelper->addCustomField('attachiamenta-content', AttachiamentaContentField::class);
        }

        if (!$this->formHelper->hasCustomField('datetime-picker')) {
            $this->formHelper->addCustomField('datetime-picker', ContentsDateTimePicker::class);
        }

        $this
            ->setModuleName(CONTENTS_MODULE_SCREEN_NAME)
            ->setValidatorClass(ContentsRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => trans('core/base::forms.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.title'),
                     'data-counter' => 120,
                ],
            ])
            ->add('content', 'editor', [
                'label' => '컨텐츠 선택',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => '컨텐츠 선택',
                    // 'data-counter' => 120,
                ],
            ])
            ->add('attachiamenta', 'attachiamenta-content', [
                'file_upload'=> $selected_file_upload ?? null,
                'link' =>  $selected_link ?? null,
            ])
            ->add('banner', 'mediaImage', [
                'label' => '배너 이미지',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder'  => '배너 이미지',
                    // 'data-counter' => 120,
                ],
            ])
            ->add('active_empathy', 'select', [
                'label'      => __('garden.comment_empathy_function'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => ['1'=> __('garden.activation'), '0'=> __('garden.disabled')],
            ])
            ->add('categories_contents_id', 'select', [
                'label' => __('campus.genealogy.categories'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => getCategoryEventsName($categories),
                'value'      => old('categories_contents_id',$selected_categories),
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
