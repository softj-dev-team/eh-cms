<?php

namespace Botble\Campus\Forms\Schedule;

use Botble\Base\Forms\FormAbstract;

class ScheduleImportForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {

        $this
            ->setModuleName(SCHEDULE_TIME_MODULE_SCREEN_NAME)
            ->withCustomFields()
            ->add('import_file', 'file', [
                'label' => '업로드',
                'label_attr' => ['class' => 'control-label required'],
            ]);
    }
}
