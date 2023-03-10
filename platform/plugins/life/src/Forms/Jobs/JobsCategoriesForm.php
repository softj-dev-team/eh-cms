<?php

namespace Botble\Life\Forms\Jobs;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Life\Http\Requests\FlareCategoriesRequest;
use Botble\Life\Http\Requests\Jobs\JobsCategoriesRequest;
use Botble\Life\Repositories\Interfaces\Jobs\JobsCategoriesInterface;

class JobsCategoriesForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $listParent = ['1'=>'Type 1','2'=>'Type 2'];
        $listAccess  = ['0'=>'No conditions','1'=>'Is Certification'];
        $this
            ->setModuleName(JOBS_CATEGORIES_MODULE_SCREEN_NAME)
            ->setValidatorClass(JobsCategoriesRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])

            ->add('background', 'text', [
                'label' => "Background",
                'label_attr' => ['class' => 'control-label required '],
                'attr' => [
                    'data-counter' => 120,
                    'class' =>'form-control jscolor ',

                ],
            ])
            ->add('color', 'text', [
                'label' => "Color text",
                'label_attr' => ['class' => 'control-label required '],
                'attr' => [
                    'data-counter' => 120,
                    'class' =>'form-control jscolor '
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
            ->add('parent_id', 'select', [
                'label'      => "Parent",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => $listParent,
            ])
            ->add('type', 'select', [
                'label'      => "Type",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => $listAccess,
            ])
            ->setBreakFieldPoint('status');
    }
}
