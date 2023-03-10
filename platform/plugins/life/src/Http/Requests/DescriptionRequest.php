<?php

namespace Botble\Life\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class DescriptionRequest extends Request
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
            'name'   => 'required',
            'description'   => 'required',
            'code'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}