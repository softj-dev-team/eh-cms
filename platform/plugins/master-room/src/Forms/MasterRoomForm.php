<?php

namespace Botble\MasterRoom\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\MasterRoom\Forms\Fields\AttachiamentaMRField;
use Botble\MasterRoom\Forms\Fields\MasterRoomDateTimePicker;
use Botble\MasterRoom\Http\Requests\CategoriesMasterRoomRequest;
use Botble\MasterRoom\Http\Requests\MasterRoomRequest;
use Botble\MasterRoom\Repositories\Interfaces\CategoriesMasterRoomInterface;

class MasterRoomForm extends FormAbstract
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
            $categories = app(CategoriesMasterRoomInterface::class)->getModel()->all();
        }

        if (!$this->formHelper->hasCustomField('datetime-picker')) {
            $this->formHelper->addCustomField('datetime-picker', MasterRoomDateTimePicker::class);
        }
        if (!$this->formHelper->hasCustomField('attachiamenta-mr')) {
            $this->formHelper->addCustomField('attachiamenta-mr', AttachiamentaMRField::class);
        }

        $this
            ->setModuleName(MASTER_ROOM_MODULE_SCREEN_NAME)
            ->setValidatorClass(MasterRoomRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => trans('core/base::forms.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.title'),
                     'data-counter' => 120,
                ],
            ])
//            ->add('start', 'datetime-picker', [
//                'label' =>__('start_date'),
//                'label_attr' => ['class' => 'control-label required'],
//                'id' => 'datetimepicker1',
//            ])
//            ->add('end', 'datetime-picker', [
//                'label' => __('end_date'),
//                'label_attr' => ['class' => 'control-label required'],
//                'id' => 'datetimepicker2',
//            ])
//            ->add('enrollment_limit', 'number', [
//                'label' => __('enrollment_limit'),
//                'label_attr' => ['class' => 'control-label'],
//                'attr' => [
//                    'placeholder'  => __('enrollment_limit'),
//                    'data-counter' => 11,
//                ],
//            ])
//
//            ->add('description', 'textarea', [
//                'label' => trans('core/base::forms.description'),
//                'label_attr' => ['class' => 'control-label'],
//                'attr' => [
//                    'placeholder'  => trans('core/base::forms.description'),
//                    // 'data-counter' => 120,
//                ],
//            ])
//            ->add('notice', 'textarea', [
//                'label' => __('life.flea_market.notice'),
//                'label_attr' => ['class' => 'control-label'],
//                'attr' => [
//                    'placeholder'  => __('life.flea_market.notice'),
//                    // 'data-counter' => 120,
//                ],
//            ])
            ->add('content', 'editor', [
                'label' => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.content'),
                    // 'data-counter' => 120,
                ],
            ])
            ->add('attachiamenta', 'attachiamenta-mr', [
                'label' => 'Test',
                'file_upload'=> $selected_file_upload ?? null,
                'link' =>  $selected_link ?? null,
            ])
            ->add('banner', 'mediaImage', [
                'label' => '배너',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder'  => '배너',
                    // 'data-counter' => 120,
                ],
            ])
            ->add('categories_master_rooms_id', 'select', [
                'label' => __('campus.genealogy.categories'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => getCategoryEventsName($categories),
                'value'      => old('categories_master_rooms_id',$selected_categories),
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
