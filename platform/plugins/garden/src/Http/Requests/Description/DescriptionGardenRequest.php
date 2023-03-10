<?php

namespace Botble\Garden\Http\Requests\Description;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class DescriptionGardenRequest extends Request
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
            'categories_gardens_id'   => 'required',
            'description'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
