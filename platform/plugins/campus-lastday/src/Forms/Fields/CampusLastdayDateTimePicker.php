<?php

namespace Botble\CampusLastday\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class CampusLastdayDateTimePicker extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.campus-lastday::elements.field.campusLastdayDatetimePicker';
    }
}
