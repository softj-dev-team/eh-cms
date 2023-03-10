<?php

namespace Botble\Report\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Report\Forms\Fields\SolveReportField;
use Botble\Report\Http\Requests\ReportRequest;

class ReportForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        if (!$this->formHelper->hasCustomField('solve_report')) {
            $this->formHelper->addCustomField('solve_report', SolveReportField::class);
        }

        /* @var \Botble\Report\Models\Report $item */
        $item = $this->getModel();

        $this
            ->setModuleName(REPORT_MODULE_SCREEN_NAME)
            ->setValidatorClass(ReportRequest::class)
            ->withCustomFields()
            ->add('solve_report', 'solve_report', [
                'value' =>  $item
            ])
            ->setBreakFieldPoint('status');
    }
}
