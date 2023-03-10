<?php

namespace Botble\Life\Http\Requests\Jobs;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class JobsCategoriesRequest extends Request
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
            'background'   => 'required|max:120',
            'color'   => 'required|max:120',

            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
