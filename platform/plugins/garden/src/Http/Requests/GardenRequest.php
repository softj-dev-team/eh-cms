<?php

namespace Botble\Garden\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class GardenRequest extends Request
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
            'title'   => 'required',
            'detail'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
