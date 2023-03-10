<?php

namespace Botble\Introduction\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Introduction\Http\Requests\IntroductionRequest;
use Botble\Introduction\Models\CategoriesIntroduction;

class IntroductionForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $categories = CategoriesIntroduction::where('status','publish')->orderBy('created_at','DESC')->get();
        $this
            ->setModuleName(INTRODUCTION_MODULE_SCREEN_NAME)
            ->setValidatorClass(IntroductionRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => __('eh-introduction.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('eh-introduction.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('link', 'text', [
                'label' => __('eh-introduction.link'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder'  => "URL (ex.https://www.youtube.com/watch?v=YDExWgTAWLM)",
                    'data-counter' => 120,
                ],
            ])
            ->add('detail', 'editor', [
                'label' => __('eh-introduction.notices.detail'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => __('eh-introduction.notices.detail'),
                    'data-counter' => 120,
                ],
            ])
            ->add('categories_introductions_id', 'select', [
                'label' => __('event.event_comments.category'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => getCategoryEventsName($categories),
            ])
            ->add('status', 'select', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('categories_introductions_id');
    }
}
