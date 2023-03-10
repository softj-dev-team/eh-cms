<?php

namespace Botble\Campus\Http\Requests\Description;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class DescriptionCampusRequest extends Request
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
            'name' => 'required',
            'code' => 'required',
            'description' => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
