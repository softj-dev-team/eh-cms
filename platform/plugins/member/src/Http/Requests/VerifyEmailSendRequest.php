<?php

namespace Botble\Member\Http\Requests;

use Botble\Support\Http\Requests\Request;

class VerifyEmailSendRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'id_login' => 'required|max:20|min:4|exists:members',
            'namemail' => 'required',
            'domainmail' => 'required',
            'email' => 'required|email|unique:members',
        ];
    }

    public function messages() {
        return [
            'id_login.unique' => '이미 사용중인 아이디 입니다',
            'id_login.min' => '아이디는 최소 4자리여야 합니다',
            'id_login.exists' => '존재 하지 않는 아이디 입니다.'
        ];
    }
}
