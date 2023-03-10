<?php

namespace Botble\Member\Http\Requests\CommonRules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PasswordMatch implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $password = auth()->guard('member')->user()->password;
        return Hash::check($value, $password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('plugins/member::dashboard.incorrect_password');
    }
}
