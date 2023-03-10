<?php

namespace Botble\Garden\Forms\Egarden;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Garden\Http\Requests\Egarden\EgardenRequest;
use Botble\Garden\Models\Egarden\Room;
use Botble\Garden\Repositories\Interfaces\Egarden\RoomInterface;

class EgardenForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $selected_room = [];
        if ($this->getModel()) {

            $selected_room = $this->getModel()->room;
            $room = Room::where('status','publish')->get();

        }


        if (empty($selected_categories)) {
            $room = app(RoomInterface::class)->getModel()->all();

        }
        $this
            ->setModuleName(EGARDEN_MODULE_SCREEN_NAME)
            ->setValidatorClass(EgardenRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => __('egarden.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => 'Title',
                    'data-counter' => 120,
                ],
            ])
            ->add('detail', 'editor', [
                'label' => trans('core/base::tables.detail'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => 'Title',
                    'data-counter' => 120,
                ],
            ])
            ->add('active_empathy', 'checkbox', [
                'label' => '댓글 공감 기능 활성화',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->active_empathy ?? 1,
            ])
            ->add('right_click', 'checkbox', [
                'label' => '오른쪽 마우스를 클릭하지 마십시오',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->right_click ?? 1,
            ])
            ->add('room_id', 'select', [
                'label' => __('egarden.room'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => getCategoryEventsName($room),
                'value'      => old('room_id',$selected_room),
            ])
            ->add('status', 'select', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('room_id');
    }
}
