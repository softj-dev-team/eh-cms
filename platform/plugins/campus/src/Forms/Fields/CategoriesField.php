<?php

namespace Botble\Campus\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class CategoriesField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.campus::elements.field.categories';
    }
}