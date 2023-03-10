<?php

namespace Botble\NewContents\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class NewContentsRequest extends Request
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
            'title'   => 'required|max:120',
            'description'   => 'required',
            'content'   => 'required',
            'notice'   => 'required',
            'start'   => 'required|date',
            'end'   => 'required|date',
            'enrollment_limit'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
