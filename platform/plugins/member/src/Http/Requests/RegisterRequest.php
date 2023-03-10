<?php

namespace Botble\Member\Http\Requests;

use Botble\Support\Http\Requests\Request;

class RegisterRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'id_login' => 'required|max:20|min:4|unique:members',
            'password' => 'required|min:6|max:16|confirmed',
            'nickname' => 'required|max:20|min:2',
            'fullname' => 'required',
            'namemail' => 'required',
            'domainmail' => 'required',
            'email' => 'required|email|unique:members',
            'term_use' => 'required',
            'privacy_policy' => 'required',
            'phone' => [
                'required',
                // 'regex:/\(?\+[0-9]{1,3}\)? ?-?[0-9]{1,3} ?-?[0-9]{3,5} ?-?[0-9]{4}( ?-?[0-9]{3})? ?(\w{1,10}\s?\d{1,6})?/u',
                'regex:/^(010|011|016|017|018|019)-[^0][0-9]{3,4}-[0-9]{4}/u',
            ],
            // 'verify_code' => 'required',
        ];
    }

    public function messages() {
        return [
            'id_login.unique' => '이미 사용중인 아이디 입니다',
            'id_login.min' => '아이디는 최소 4자리여야 합니다',
            'password.confirmed' => '비밀번호가 일치하지 않습니다',
            'password.min' => '비밀번호는 최소 8자리여야 합니다',
            'nickname.min' => '닉네임은 최소 2자리여야합니다',
            'term_use.required' => '이용약관 필요합니다',
            'privacy_policy.required' => '개인정보 보호 정책 필요합니다',

        ];
    }
}
