<?php

namespace Botble\MasterRoom\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Garden\Http\Requests\CommentsGardenRequest;
use Botble\MasterRoom\Http\Requests\CommentsMasterRoomRequest;

class CommentsMasterRoomForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {

        $this
            ->setModuleName(COMMENTS_MASTER_ROOM_MODULE_SCREEN_NAME)
            ->setValidatorClass(CommentsMasterRoomRequest::class)
            ->withCustomFields()
            ->add('content', 'text', [
                'label' => __('new_contents.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('new_contents.content'),
                     'data-counter' => 120,
                ],
            ])
            ->add('anonymous', 'onOff', [
                'label'      => __('comments.anonymous'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'value'    => 1,
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
