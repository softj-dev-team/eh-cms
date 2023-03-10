<?php

namespace Botble\Member\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class MemberEditRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'fullname' => 'required|max:120|min:2',
            'email'      => 'required|max:60|min:6|email',
            'id_login' => [
                'required',
                Rule::unique('members','id_login')->ignore($this->route('id')),
            ],
            'nickname'   =>  'required|min:2|max:20|unique:members,nickname,' . $this->route('id'),
        ];

        if ($this->input('is_change_password') == 1) {
            $rules['password'] = 'required|min:6|max:16|confirmed|regex:/^.*[^A-Z].$/';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'password.min' => '비밀 번호는 최소 8 자 이상이어야합니다.',
            'password.regex' => '비밀번호는 대문자가 아니어야합니다!'
        ];
    }
}
