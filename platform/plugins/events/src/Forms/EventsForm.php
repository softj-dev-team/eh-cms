<?php

namespace Botble\Events\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Events\Forms\Fields\AttachiamentaEventsField;
use Botble\Events\Forms\Fields\EventDateTimePicker;
use Botble\Events\Http\Requests\EventsRequest;
use Botble\Events\Repositories\Interfaces\CategoryEventsInterface;

class EventsForm extends FormAbstract
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
            $selected_categories =  $temp[0]->category_events ? $temp[0]->category_events->name : null;
            $categories = $this->getModel()->category_events->get();
            $selected_file_upload =  $this->getModel()->file_upload  ;
            $selected_link =  $this->getModel()->link ;
        }

        if (empty($selected_categories)) {
            $categories = app(CategoryEventsInterface::class)->getModel()->all();

        }

        if (!$this->formHelper->hasCustomField('datetime-picker')) {
            $this->formHelper->addCustomField('datetime-picker', EventDateTimePicker::class);
        }
        if (!$this->formHelper->hasCustomField('attachiamenta-event')) {
            $this->formHelper->addCustomField('attachiamenta-event', AttachiamentaEventsField::class);
        }
        $this
            ->setModuleName(EVENTS_MODULE_SCREEN_NAME)
            ->setValidatorClass(EventsRequest::class)
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
                'label' => '시작일',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.start'),
                ],
                'id' => 'datetimepicker1',
            ])
            ->add('end', 'datetime-picker', [
                'label' => '종료일',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.end'),
                ],
                'id' => 'datetimepicker2',
            ])
            ->add('published', 'datetime-picker', [
                'label' => trans('core/base::tables.published'),
                'label_attr' => ['class' => 'control-label required'],
                'id' => 'datetimepicker2',
            ])
            ->add('enrollment_limit', 'number', [
                'label' => __('event.enrollment_limit'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('event.enrollment_limit'),
                    'data-counter' => 11,
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
            ->add('attachiamenta', 'attachiamenta-event', [
                'file_upload'=> $selected_file_upload ?? null,
                'link' =>  $selected_link ?? null,
            ])
            ->add('banner', 'mediaImage', [
                'label' => '배너 이미지',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.banner'),
                    // 'data-counter' => 120,
                ],
            ])
            ->add('category_events_id', 'select', [
                'label' => trans('core/base::tables.category'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => getCategoryEventsName($categories),
                'value'      => old('category_events_id',$selected_categories),
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
