<?php

namespace Botble\Campus\Forms\StudyRoom;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Forms\Fields\AttachiamentaField;
use Botble\Campus\Forms\Fields\CategoriesNoParentsField;
use Botble\Campus\Forms\Fields\MultipleUploadField;
use Botble\Campus\Http\Requests\StudyRoom\StudyRoomRequest;
use Botble\Campus\Models\StudyRoom\StudyRoomCategories;

class StudyRoomForm extends FormAbstract
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
            ->setModuleName(STUDY_ROOM_MODULE_SCREEN_NAME)
            ->setValidatorClass(StudyRoomRequest::class)
            ->withCustomFields()
            ->add('categories', 'categoriesNoParent', [
                'value'=> old('categories', $selected_categories ?? null),
                'data-value' => StudyRoomCategories::where('status','publish')->get()
            ])

            ->add('title', 'text', [
                'label' =>  __('campus.study_room.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('campus.study_room.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('contact', 'text', [
                'label' =>  __('campus.study_room.contact'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('campus.study_room.contact'),
                    'data-counter' => 120,
                ],
            ])
            ->add('detail', 'editor', [
                'label' => __('campus.study_room.detail'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => "Detail",
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
                'choices'    => BaseStatusEnum::arrStatus1(),
//                'attr' => [
//                    'class' => 'form-control select-full',
//                ],
//                'choices'    =>  ["publish" => trans('core/base::tables.status_publish'), "pending" => trans('core/base::tables.status_pending'), 'draft' => trans('core/base::tables.status_draft')],
            ])
            ->setBreakFieldPoint('status');
    }
}
