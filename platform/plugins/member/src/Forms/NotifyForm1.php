<?php

namespace Botble\Member\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Member\Http\Requests\NotifyRequest;
use Botble\Member\Models\Member;

class NotifyForm1 extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $form = $this
            ->setModuleName(FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME)
            ->setValidatorClass(NotifyRequest::class)
            ->withCustomFields()
            ->addMetaBoxes([
                'information' => [
                    'title'      => '푸시알람 보내기',
                    'content'    => view('plugins.member::forms.fields.member-notify1')->render(),
                    'attributes' => [
                        'style' => 'margin-top: 0',
                    ],
                ]
            ])

            ->setBreakFieldPoint('status');

            return $form;


    }

    public function getActionButtons(): string
    {
        if ($this->actionButtons === '') {
            return view('plugins.member::forms.form-action-notify')->render();
        }
        return $this->actionButtons;
    }

}
