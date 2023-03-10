<?php

namespace Botble\Events\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Events\Http\Requests\CategoryEventsRequest;
use Botble\Events\Models\CategoryEvents;

class CategoryEventsForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(CATEGORY_EVENTS_MODULE_SCREEN_NAME)
            ->setValidatorClass(CategoryEventsRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => 'Name',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => 'Name',
                     'data-counter' => 120,
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
            ->add('permisions', 'select', [
                'label'      => 'Permisions',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => CategoryEvents::getPermisions(),
            ])
            ->setBreakFieldPoint('status');
    }
}
