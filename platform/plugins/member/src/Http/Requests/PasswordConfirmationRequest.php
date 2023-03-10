<?php

namespace Botble\Member\Http\Requests;

use Botble\Member\Http\Requests\CommonRules\PasswordConfirmationRules;
use Botble\Support\Http\Requests\Request;

class PasswordConfirmationRequest extends Request
{
    use PasswordConfirmationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Sang Nguyen
     */
    public function rules()
    {
        return $this->passwordConfirmationRules();
    }

    public function messages()
    {
        return $this->passwordConfirmationMessages();
    }
}
