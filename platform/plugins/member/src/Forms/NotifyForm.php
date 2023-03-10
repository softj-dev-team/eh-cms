<?php

namespace Botble\Member\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Member\Http\Requests\NotifyRequest;
use Botble\Member\Models\Member;

class NotifyForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $form = $this
            ->setModuleName(FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME)
            ->setValidatorClass(NotifyRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => __('event.event_comments.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('event.event_comments.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('content', 'textarea', [
                'label' => __('contents.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('contents.content'),
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

            if ($this->getModel()) {
                $form->addMetaBoxes([
                    'replies' => [
                        'title'      => __('egarden.members'),
                        'content'    => view('plugins.member::forms.fields.member-notify', [
                            'memberNotify' =>  $this->getModel()->memberNotify ,
                            'notify_id' => $this->getModel()->id,
                        ])->render(),
                    ],
                ]);
            }

            return $form;


    }
}
