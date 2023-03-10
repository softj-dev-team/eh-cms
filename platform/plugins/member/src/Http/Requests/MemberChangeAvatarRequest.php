<?php

namespace Botble\Member\Http\Requests;

use Botble\Support\Http\Requests\Request;

class MemberChangeAvatarRequest extends Request
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
            'avatar' => 'required|mimes:jpeg,png,gif,bmp,svg,webp,jpg',
        ];
    }
}
