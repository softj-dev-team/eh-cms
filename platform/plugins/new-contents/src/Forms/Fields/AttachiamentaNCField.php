<?php

namespace Botble\NewContents\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class AttachiamentaNCField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.new-contents::elements.field.attachiamenta';
    }
}
