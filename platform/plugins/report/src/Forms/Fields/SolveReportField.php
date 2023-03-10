<?php

namespace Botble\Report\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class SolveReportField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        return  'plugins.report::elements.field.solveReport';
    }
}
