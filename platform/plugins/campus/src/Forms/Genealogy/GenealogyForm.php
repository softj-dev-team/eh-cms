<?php

namespace Botble\Campus\Forms\Genealogy;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Forms\Fields\AttachiamentaField;
use Botble\Campus\Forms\Fields\CategoriesNoParentsField;
use Botble\Campus\Forms\Fields\MultipleUploadField;
use Botble\Campus\Forms\Fields\SelectPickerField;
use Botble\Campus\Forms\Fields\SemesterField;
use Botble\Campus\Http\Requests\Genealogy\GenealogyRequest;
use Botble\Campus\Http\Requests\StudyRoom\StudyRoomRequest;
use Botble\Campus\Models\Evaluation\Major;
use Botble\Campus\Models\StudyRoom\StudyRoomCategories;

class GenealogyForm extends FormAbstract
{


    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {


        if ($this->getModel()) {
            $selected_images = $this->getModel()->images;
            $id = $this->getModel()->id;
            $semester_year = $this->getModel()->semester_year;
            $semester_session = $this->getModel()->semester_session;
            $selected_file_upload =  $this->getModel()->file_upload && $this->getModel()->file_upload != 'null' ? $this->getModel()->file_upload : null  ;
            $selected_link =  $this->getModel()->link && $this->getModel()->link != 'null' ? $this->getModel()->link : null  ;

       }
        if (!$this->formHelper->hasCustomField('multiple_upload')) {
            $this->formHelper->addCustomField('multiple_upload', MultipleUploadField::class);
        }
        if (!$this->formHelper->hasCustomField('selectPicker')) {
            $this->formHelper->addCustomField('selectPicker', SelectPickerField::class);
        }
        if (!$this->formHelper->hasCustomField('semesterField')) {
            $this->formHelper->addCustomField('semesterField', SemesterField::class);
        }
        if (!$this->formHelper->hasCustomField('attachiamenta')) {
            $this->formHelper->addCustomField('attachiamenta', AttachiamentaField::class);
        }
        $this
            ->setModuleName(GENEALOGY_MODULE_SCREEN_NAME)
            ->setValidatorClass(GenealogyRequest::class)
            ->withCustomFields()
            ->add('class_name', 'text', [
                'label' => "수업명",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => "수업명",
                    'data-counter' => 120,
                ],
            ])
            ->add('professor_name', 'text', [
                'label' => "교수명",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => "교수명",
                    'data-counter' => 120,
                ],
            ])
            ->add('exam_name', 'text', [
                'label' => "시험",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => "시험",
                    'data-counter' => 120,
                ],
            ])
            ->add('major', 'selectPicker', [
                'label' => trans('core/base::tables.category'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.start'),
                ],
                'major' => Major::where('parents_id','0')->where('status', 'publish')->get(),
                'evaluation_id' => $id ?? null,
                'type' => 1,
            ])
            ->add('detail', 'editor', [
                'label' => "내용",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => "내용",
                ],
            ])
            ->add('attachiamenta', 'attachiamenta', [
                'file_upload'=> $selected_file_upload ?? null,
                'link' =>  $selected_link ?? null,
            ])
            ->add('images[]', 'multiple_upload', [
                'value' => old('images', $selected_images ?? null),
            ])
            ->add('semester', 'semesterField', [
                'label' => trans('core/base::tables.semester'),
                'label_attr' => ['class' => 'control-label required'],
                'semester_year' => $semester_year ?? null,
                'semester_session' => $semester_session ?? null,
            ])
            ->add('status', 'select', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
//                'choices'    => BaseStatusEnum::arrStatus(),
                'choices'    => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('semester');
    }
}
