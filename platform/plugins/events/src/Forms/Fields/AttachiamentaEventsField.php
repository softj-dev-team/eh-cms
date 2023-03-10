<?php

namespace Botble\Events\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class AttachiamentaEventsField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.life::elements.field.attachiamenta';
    }
}
