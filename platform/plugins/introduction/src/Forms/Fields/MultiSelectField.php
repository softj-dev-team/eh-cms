<?php

namespace Botble\Introduction\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class MultiSelectField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return 'plugins.introduction::elements.field.multiSelect';
    }
}
