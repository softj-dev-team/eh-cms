<?php

namespace Botble\Member\Http\Requests;

use Botble\Support\Http\Requests\Request;

class MemberCreateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'first_name' => 'required|max:120|min:2',
            // 'last_name'  => 'required|max:120|min:2',
            'fullname'   => 'required|max:120|min:2',
            'id_login'   => 'required|unique:members',
            'email'      => 'required|max:60|min:6|unique:members',
            'password'   => 'required|min:6|max:16|confirmed|regex:/^.*[^A-Z].$/',
            'nickname'   =>  'required|min:2|max:20|unique:members,nickname'
        ];
    }
    public function messages()
    {
        return [
            'password.min' => '비밀 번호는 최소 8 자 이상이어야합니다.',
            'password.regex' => '비밀번호는 대문자가 아니어야합니다!'
        ];
    }
}
