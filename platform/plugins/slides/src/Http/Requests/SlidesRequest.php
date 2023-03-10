<?php

namespace Botble\Slides\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SlidesRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Sang Nguyen
     */
    public function rules()
    {
        $rules = [
            'name'   => 'required',
            'code'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
            'start'   => 'required',
            'end'   => 'required',
        ];

        return $rules;
    }
}
