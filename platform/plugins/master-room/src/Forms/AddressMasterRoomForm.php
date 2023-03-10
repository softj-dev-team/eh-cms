<?php

namespace Botble\MasterRoom\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\MasterRoom\Http\Requests\AddressMasterRoomRequest;

class AddressMasterRoomForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME)
            ->setValidatorClass(AddressMasterRoomRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => '이름',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '이름',
                    'data-counter' => 120,
                ],
            ])
            ->add('classification', 'text', [
                'label' => __('master_room.address.classification'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('master_room.address.classification'),
                    'data-counter' => 120,
                ],
            ])
            ->add('email', 'email', [
                'label' => __('master_room.address.email'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('master_room.address.email'),
                    'data-counter' => 120,
                ],
            ])
            ->add('address', 'text', [
                'label' => __('master_room.address.address'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('master_room.address.address'),
                    'data-counter' => 120,
                ],
            ])
            ->add('mobile_phone', 'number', [
                'label' => __('master_room.address.mobile_phone'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('master_room.address.mobile_phone'),
                    'data-counter' => 120,
                ],
            ])
            ->add('memo', 'text', [
                'label' => __('master_room.address.memo'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('master_room.address.memo'),
                    'data-counter' => 120,
                ],
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
