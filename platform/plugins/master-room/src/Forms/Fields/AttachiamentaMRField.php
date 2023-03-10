<?php

namespace Botble\MasterRoom\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class AttachiamentaMRField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.master-room::elements.field.attachiamenta';
    }
}
