<?php

namespace Botble\Introduction\Forms\Faq;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Introduction\Http\Requests\Faq\FaqIntroductionRequest;
use Botble\Introduction\Http\Requests\IntroductionRequest;
use Botble\Introduction\Models\Faq\FaqCategories;

class FaqIntroductionForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $categories = FaqCategories::where('status','publish')->orderBy('created_at','DESC')->get();
        $this
            ->setModuleName(FAQ_INTRODUCTION_MODULE_SCREEN_NAME)
            ->setValidatorClass(FaqIntroductionRequest::class)
            ->withCustomFields()
            ->add('question', 'text', [
                'label' => __('eh-introduction.faqs.question'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('eh-introduction.faqs.question'),
                    'data-counter' => 120,
                ],
            ])
            ->add('answer', 'editor', [
                'label' => __('eh-introduction.faqs.anwser'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('eh-introduction.faqs.anwser'),
                ],
            ])
            ->add('faq_categories_id', 'select', [
                'label' => __('eh-introduction.faqs.category'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => getCategoryEventsName($categories),
            ])
            ->add('status', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('faq_categories_id');
    }
}
