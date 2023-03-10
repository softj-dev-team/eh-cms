<?php

namespace Botble\Garden\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class CategoryRoomField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.garden::elements.field.categoryRoom';
    }
}
