<?php

namespace Botble\Member\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Member\Http\Requests\ForbiddenKeywordsRequest;
use Botble\Member\Models\Member;

class ForbiddenKeywordsForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME)
            ->setValidatorClass(ForbiddenKeywordsRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => __('event.event_comments.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('event.event_comments.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('type', 'select', [
                'label' => __('type'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => [
                    'forbidden' => '금지 단어',
                    'swear_word' => '욕설',
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
