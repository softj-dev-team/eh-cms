<?php

namespace Botble\Campus\Forms\Evaluation;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Campus\Forms\Fields\CategoriesNoParentsField;
use Botble\Campus\Http\Requests\CampusRequest;
use Botble\Campus\Http\Requests\Evaluation\MajorRequest;
use Botble\Campus\Models\Evaluation\Major;
use Botble\MasterRoom\Models\CategoriesMasterRoom;

class MajorForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $categories = [];
        if ($this->getModel()) {
                $categories = Major::where('id','!=',$this->model->id)->where('status','publish')->where('parents_id',0)->pluck('name','id')->toArray();
                $categories[0] = __('campus.major.no_parent');

       }else{
            $categories = Major::where('status','publish')->where('parents_id',0)->pluck('name','id')->toArray();
            $categories[0] = __('campus.major.no_parent');

       }


        $this
            ->setModuleName(MAJOR_MODULE_SCREEN_NAME)
            ->setValidatorClass(MajorRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => __('campus.major.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => __('campus.major.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('parents_id', 'select', [
                'label' => __('campus.major.parent'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => $categories,
            ])
            ->add('status', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('parents_id');
    }
}
