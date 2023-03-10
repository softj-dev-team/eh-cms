<?php

namespace Botble\Campus\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class MultipleUploadField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return 'plugins.campus::elements.field.multipleUpload';
    }
}