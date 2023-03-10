<?php

namespace Botble\Life\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Life\Http\Requests\FlareCategoriesRequest;
use Botble\Life\Http\Requests\LifeRequest;
use Botble\Life\Repositories\Interfaces\FlareCategoriesInterface;

class FlareCategoriesForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $selected_categories = [];
        if ($this->getModel()) {
            $temp = $this->getModel();

            $selected_categories = $temp->name;

            $categories = $this->getModel()->where('id','!=',$temp->id)->where('status','publish')->get();
        }

        if (empty($selected_categories)) {
            $categories = app(FlareCategoriesInterface::class)->getModel()->where('status','publish')->get();
        }

        $listParent = ['1'=>'유형 1','2'=>'유형 1'];

        $this
            ->setModuleName(FLARE_CATEGORIES_MODULE_SCREEN_NAME)
            ->setValidatorClass(FlareCategoriesRequest::class)
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
                'label' => __('campus.study_room.background'),
                'label_attr' => ['class' => 'control-label required '],
                'attr' => [
                    'data-counter' => 120,
                    'class' =>'form-control jscolor ',
                ],
            ])
            ->add('color', 'text', [
                'label' => "컬러 텍스트",
                'label_attr' => ['class' => 'control-label required '],
                'attr' => [
                    'data-counter' => 120,
                    'class' =>'form-control jscolor '
                ],
            ])
            ->add('parent_id', 'select', [
                'label'      => __('campus.evaluation.type'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => $listParent,
            ])
            ->add('status', 'select', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('parent_id');
    }
}
