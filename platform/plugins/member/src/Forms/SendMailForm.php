<?php

namespace Botble\Member\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Member\Http\Requests\SendMailRequest;

class SendMailForm extends FormAbstract
{
    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setValidatorClass(SendMailRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => __('egarden.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('egarden.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('mailTo', 'text', [
                'label' => __('campus.timetable.to'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('campus.timetable.to'),
                    'data-counter' => 120,
                ],
            ])
            ->add('content', 'editor', [
                'label' => __('master_room.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('master_room.content'),
                ],
            ])
            ->setActionButtons(view('plugins.member::emails.actions')->render());
    }
}
