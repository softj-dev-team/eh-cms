<?php

namespace Botble\Contents\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Contents\Forms\Fields\AttachiamentaContentField;
use Botble\Contents\Forms\Fields\ContentsDateTimePicker;
use Botble\Contents\Http\Requests\ContentsRequest;
use Botble\Contents\Repositories\Interfaces\CategoriesContentsInterface;

class SlidesContentsForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(CONTENTS_MODULE_SCREEN_NAME)
            ->setValidatorClass(ContentsRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => trans('core/base::forms.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder'  => trans('core/base::forms.title'),
                     'data-counter' => 120,
                ],
            ]);
    }
}
