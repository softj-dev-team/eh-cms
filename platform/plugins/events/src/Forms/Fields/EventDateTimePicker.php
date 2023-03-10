<?php

namespace Botble\Events\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class EventDateTimePicker extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.events::elements.field.datetime-picker';
    }
}