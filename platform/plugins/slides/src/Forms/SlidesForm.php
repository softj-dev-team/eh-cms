<?php

namespace Botble\Slides\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Slides\Forms\Fields\ShowCountField;
use Botble\Slides\Forms\Fields\SlidesDateTimePicker;
use Botble\Slides\Http\Requests\SlidesRequest;
use Botble\Slides\Models\Slides;

class SlidesForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $list_image = null;
        if ($this->getModel() && ($this->getModel()->code == 'ACCOUNT' || $this->getModel()->code == 'ACCOUNT_GARDEN')) {
            $list_image = $this->getModel()->getImageGallery()->images ?? null;
        }

        if (!$this->formHelper->hasCustomField('showCount')) {
            $this->formHelper->addCustomField('showCount', ShowCountField::class);
        }
        if (!$this->formHelper->hasCustomField('datetime-picker')) {
            $this->formHelper->addCustomField('datetime-picker', SlidesDateTimePicker::class);
        }
        $this
            ->setModuleName(SLIDES_MODULE_SCREEN_NAME)
            ->setValidatorClass(SlidesRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('url', 'text', [
                'label' => "URL",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => 'URL',
                    'data-counter' => 120,
                ],
            ])
            ->add('code', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => [
                    "HOME" => "????????? ??????",
                    "SLIDES" => "?????? ??????",
                    "GARDEN" => "?????? ??????",
                    "EGARDEN" => "e??????",
                    "SLIDES_MOBILE" => "???????????????",
                    "ACCOUNT" => "????????? ?????? ??????",
                    "ACCOUNT_GARDEN" => "?????? ?????? ??????",
                ],
            ])
            ->add('type', 'select', [
                'label' => "??????",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => [
                    "banner" => " ??????",
                    "popup" => "??????",
                    "mobile" => "?????????",
                    "web" => "???"
                ],
            ])
            ->add('is_change_code', 'checkbox', [
                'label' => __('change_code'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'hrv-checkbox',
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel() ? null : ' hidden'),
                ],
                'value' => 1,
            ])
            ->add('start', 'datetime-picker', [
                'label' => '?????????',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => '?????????',
                ],
                'id' => 'datetimepicker1',
            ])
            ->add('end', 'datetime-picker', [
                'label' => '?????????',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.end'),
                ],
                'id' => 'datetimepicker2',
            ])
            ->add('status', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::arrStatus(),
            ])
            ->add('showCount', 'showCount', [
                'label' => '?????????',
                'data-value' => $list_image,
            ])
            ->setBreakFieldPoint('status');
    }
}
