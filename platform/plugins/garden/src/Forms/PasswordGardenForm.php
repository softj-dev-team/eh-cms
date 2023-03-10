<?php

namespace Botble\Garden\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Garden\Http\Requests\GardenRequest;
use Botble\Garden\Http\Requests\PasswordGardenRequest;
use Botble\Garden\Models\CategoriesGarden;
use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;
use Botble\Setting\Models\Setting;

class PasswordGardenForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $passwd = Setting::where('key', 'password_garden')->first();

        $this
        ->setModuleName(PASSWORD_GARDEN_MODULE_SCREEN_NAME)
            ->setValidatorClass(PasswordGardenRequest::class)
            ->withCustomFields()
            ->add('password', 'password', [
                'label'      => trans('plugins/member::member.form.password'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 60,
                    'placeholder' => $passwd ? $passwd->value_1 : 'admin@123'
                ],

            ])
            ->add('password_confirmation', 'password', [
                'label'      => trans('plugins/member::member.form.password_confirmation'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 60,
                ],

            ]);
    }
}
