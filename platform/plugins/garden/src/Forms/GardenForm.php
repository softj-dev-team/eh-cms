<?php

namespace Botble\Garden\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Garden\Forms\Fields\AttachiamentaGardenField;
use Botble\Garden\Http\Requests\GardenRequest;
use Botble\Garden\Models\CategoriesGarden;
use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;

class GardenForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $selected_categories = [];
        if ($this->getModel()) {

            $selected_categories = $this->getModel()->categories;
            $categories = CategoriesGarden::where('status','publish')->where('special_garden','!=',CategoriesGarden::E_GARDEN)->get();
            $selected_file_upload =  $this->getModel()->file_upload  ;
            $selected_link =  $this->getModel()->link ;

        }


        if (empty($selected_categories)) {
            $categories = app(CategoriesGardenInterface::class)->getModel()->where('special_garden','!=',CategoriesGarden::E_GARDEN)->get();
        }
        if (!$this->formHelper->hasCustomField('attachiamenta-garden')) {
            $this->formHelper->addCustomField('attachiamenta-garden', AttachiamentaGardenField::class);
        }


        $this
            ->setModuleName(GARDEN_MODULE_SCREEN_NAME)
            ->setValidatorClass(GardenRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => trans('core/base::tables.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::tables.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('detail', 'editor', [
                'label' => trans('core/base::tables.detail'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::tables.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('active_empathy', 'checkbox', [
                'label' => '댓글 공감 기능 활성화',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->active_empathy ?? 1,
            ])
            ->add('right_click', 'checkbox', [
                'label' => '오른쪽 마우스를 클릭하지 마십시오',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel()->right_click ?? 1,
            ])
            ->add('attachiamenta', 'attachiamenta-garden', [
                'file_upload'=> $selected_file_upload ?? null,
                'link' =>  $selected_link ?? null,
            ])
            ->add('categories_gardens_id', 'select', [
                'label' => trans('core/base::tables.category'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => getCategoryEventsName($categories),
                'value'      => old('categories_gardens_id',$selected_categories),
            ])
            ->add('status', 'select', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('categories_gardens_id');
    }
}
