<?php

namespace Botble\Events\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Events\Forms\Fields\AttachiamentaEventsField;
use Botble\Events\Http\Requests\EventsCmtRequest;
use Botble\Events\Repositories\Interfaces\CategoryEventsInterface;

class EventsCmtForm extends FormAbstract
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
            $selected_categories = ($temp[0]->category_events) ? $temp[0]->category_events->name : null;
            $categories = ($this->getModel()->category_events) ? $this->getModel()->category_events->where('name','!=','Event Sketch')->get() : null;
            $selected_file_upload =  $this->getModel()->file_upload  ;
            $selected_link =  $this->getModel()->link ;
        }

        if (empty($selected_categories)) {
            $categories = app(CategoryEventsInterface::class)->getModel()->where('name','!=','Event Sketch')->get();

        }
        if (!$this->formHelper->hasCustomField('attachiamenta-event')) {
            $this->formHelper->addCustomField('attachiamenta-event', AttachiamentaEventsField::class);
        }
        $this
            ->setModuleName(EVENTS_CMT_MODULE_SCREEN_NAME)
            ->setValidatorClass(EventsCmtRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => trans('core/base::tables.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::tables.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('detail', 'editor', [
                'label' => __('event.event_comments.detail'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' =>__('event.event_comments.detail'),
                    // 'data-counter' => 120,
                ],
            ])
            ->add('attachiamenta', 'attachiamenta-event', [
                'file_upload'=> $selected_file_upload ?? null,
                'link' =>  $selected_link ?? null,
            ])
            ->add('category_events_id', 'select', [
                'label' => trans('core/base::tables.category'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => getCategoryEventsName($categories),
                'value' => old('category_events_id', $selected_categories),
            ])
            ->add('status', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('category_events_id');
    }
}
