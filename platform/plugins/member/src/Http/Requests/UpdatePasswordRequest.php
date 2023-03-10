<?php

namespace Botble\Member\Http\Requests;

use Botble\Support\Http\Requests\Request;

class UpdatePasswordRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Sang Nguyen
     */
    public function rules()
    {
        return [
            'current_password' => 'required|min:6|max:16|regex:/^.*[^A-Z].$/',
            'password'         => 'required|min:6|max:16|confirmed|regex:/^.*[^A-Z].$/',
        ];
    }

    public function messages()
    {
        return [
            'current_password.min' => '비밀 번호는 최소 8 자 이상이어야합니다.',
            'password.min' => '비밀 번호는 최소 8 자 이상이어야합니다.',
            'current_password.regex' => '비밀번호는 대문자가 아니어야합니다!',
            'password.regex' => '비밀번호는 대문자가 아니어야합니다!'
        ];
    }
}
