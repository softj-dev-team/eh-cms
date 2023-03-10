<?php

namespace Botble\NewContents\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class NewContentsDateTimePicker extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.new-contents::elements.field.datetime-picker';
    }
}