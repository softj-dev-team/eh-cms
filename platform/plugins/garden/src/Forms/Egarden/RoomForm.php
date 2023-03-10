<?php

namespace Botble\Garden\Forms\Egarden;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Garden\Forms\Fields\AuthorRoomField;
use Botble\Garden\Forms\Fields\CategoryRoomField;
use Botble\Garden\Forms\Fields\GardenMultiSelectField;
use Botble\Garden\Forms\Fields\MemberInRoomField;
use Botble\Garden\Http\Requests\Egarden\RoomRequest;
use Botble\Garden\Models\Egarden\RoomMemberRequest;
use Botble\Member\Models\Member;

class RoomForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm() {
        if (!$this->formHelper->hasCustomField('multiSelect')) {
            $this->formHelper->addCustomField('multiSelect', GardenMultiSelectField::class);
        }

        if (!$this->formHelper->hasCustomField('memberInRoom')) {
            $this->formHelper->addCustomField('memberInRoom', MemberInRoomField::class);
        }

        if (!$this->formHelper->hasCustomField('authorRoom')) {
            $this->formHelper->addCustomField('authorRoom', AuthorRoomField::class);
        }

        if (!$this->formHelper->hasCustomField('categoryRoom')) {
            $this->formHelper->addCustomField('categoryRoom', CategoryRoomField::class);
        }

        $selected_member = [];
        $selected_categories = [];
        $owner_applications = [];

        if ($this->getModel()) {
            $selected_member = $this->getModel()->member()->paginate(10);
            $selected_categories = $this->getModel()->categoreis()->paginate(10);
            $owner_applications = RoomMemberRequest::where('status', 'publish')
                ->where('room_id', $this->getModel()->id)->get();
        }

        $form = $this
            ->setModuleName(ROOM_MODULE_SCREEN_NAME)
            ->setValidatorClass(RoomRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => trans('core/base::tables.description'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => 'Description',
                ],
            ])
            ->add('member', 'memberInRoom', [
                'label' => '회원',
                'data-value' => $selected_member,
                'owner_applications' => $owner_applications,
                'idRoom' => $this->getModel()->id ?? 0,
            ])
            ->add('images', 'mediaImage', [
                'label' => trans('core/base::tables.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('cover', 'mediaImage', [
                'label' => "Cover",
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('categoreis', 'categoryRoom', [
                'label' => trans('core/base::tables.category'),
                'label_attr' => ['class' => 'control-label categoryRoom'],
                'data-value' => $selected_categories ?? []
            ])
            ->add('status', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('images');

        if ($this->getModel()) {
            $form->add('member_id', 'authorRoom', [
                'label' => 'Author',
                'data-value' => $this->getModel()->author,
                'idRoom' => $this->getModel()->id ?? 0,
                'data-old-member' => $this->getModel()->member_id
            ]);
        }
        return $form;
    }
}
