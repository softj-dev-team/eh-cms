<?php

namespace Botble\Member\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class FreshmenRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'freshman1'  => 'nullable|mimes:jpeg,png,gif,bmp,svg,webp,jpg',
            'freshman2'  => 'nullable|mimes:jpeg,png,gif,bmp,svg,webp,jpg',
            'note_freshman1'   => 'required_with:freshman1|max:120',
            'note_freshman2'   => 'required_with:freshman2|max:120',
            'auth_studentid'   => 'required_with:freshman2|max:120',
        ];
    }
}
