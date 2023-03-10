<?php

namespace Botble\Contents\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class ContentsDateTimePicker extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.contents::elements.field.datetime-picker';
    }
}