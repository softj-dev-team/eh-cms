<?php

namespace Botble\Member\Http\Requests;

use Botble\Support\Http\Requests\Request;

class LoginRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_login'    => 'required|string|max:20|min:4',
            'password' => 'required|string',
        ];
    }
}
