<?php

namespace Botble\Member\Http\Requests\CommonRules;

trait PasswordConfirmationRules
{
    public function passwordConfirmationRules() {
        return [
            'password' => ['required', 'min:6', 'same:password_confirmation', new PasswordMatch()],
        ];
    }

    public function passwordConfirmationMessages() {
        return [
            'password.min' => trans('plugins/member::dashboard.password_must_be_at_least_6_characters_long'),
            'password.required' => trans('plugins/member::dashboard.please_enter_the_password'),
            'password.same' => trans('plugins/member::dashboard.password_and_password_confirmation_must_be_the_same')
        ];
    }
}
