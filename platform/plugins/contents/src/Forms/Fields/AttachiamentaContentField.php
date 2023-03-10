<?php

namespace Botble\Contents\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class AttachiamentaContentField extends FormField
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
