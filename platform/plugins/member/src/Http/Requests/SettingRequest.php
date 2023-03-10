<?php

namespace Botble\Member\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SettingRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullname'   => 'nullable|max:120',
            'nickname'   => 'nullable|max:120',
            'phone'      => 'max:20|sometimes',
            'dob'        => 'max:20|sometimes',
            'email'      => 'required|max:60|min:6|unique:members,email,' .auth()->guard('member')->user()->id,
            'nickname'   => 'required|max:20|min:2|unique:members,nickname,' .auth()->guard('member')->user()->id,
        ];
    }
}
