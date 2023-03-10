<?php

namespace Botble\MasterRoom\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class MasterRoomDateTimePicker extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.master-room::elements.field.datetime-picker';
    }
}