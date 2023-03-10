<?php

namespace Botble\Campus\Forms\Evaluation;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Campus\Forms\Fields\MutilDateTimePicker;
use Botble\Campus\Forms\Fields\SelectPickerField;
use Botble\Campus\Forms\Fields\TimeEvaluationField;
use Botble\Campus\Http\Requests\Evaluation\EvaluationRequest;
use Botble\Campus\Models\Evaluation\Major;
use Botble\Campus\Models\Schedule\ScheduleDay;
use Botble\Campus\Models\Schedule\ScheduleTime;

class EvaluationImportForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {

        $this
            ->setModuleName(EVALUATION_MODULE_SCREEN_NAME)
            ->withCustomFields()
            ->add('import_file', 'file', [
                'label' => '시간표 업로드',
                'label_attr' => ['class' => 'control-label required'],
            ]);
    }
}
