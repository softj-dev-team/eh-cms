<?php

namespace Botble\Slides\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class ShowCountField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.slides::elements.field.showCount';
    }
}
