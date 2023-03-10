<?php

namespace Botble\Campus\Forms\StudyRoom;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Http\Requests\StudyRoom\StudyRoomCategoriesRequest;

class StudyRoomCategoriesForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(STUDY_ROOM_CATEGORIES_MODULE_SCREEN_NAME)
            ->setValidatorClass(StudyRoomCategoriesRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])

            ->add('background', 'text', [
                'label' => __('campus.study_room.background'),
                'label_attr' => ['class' => 'control-label required '],
                'attr' => [
                    'data-counter' => 120,
                    'class' =>'form-control jscolor ',

                ],
            ])
            ->add('color', 'text', [
                'label' => __('campus.study_room.color_text'),
                'label_attr' => ['class' => 'control-label required '],
                'attr' => [
                    'data-counter' => 120,
                    'class' =>'form-control jscolor '
                ],
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
