<?php

namespace Botble\Member\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class FreshmanField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.member::elements.field.freshman';
    }
}
