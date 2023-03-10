<?php

namespace Botble\Life\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class ExchangeField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return 'plugins.life::elements.field.exchange';
    }
}