<?php

namespace Botble\Slides\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class SlidesDateTimePicker extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.slides::elements.field.datetime-picker';
    }
}
