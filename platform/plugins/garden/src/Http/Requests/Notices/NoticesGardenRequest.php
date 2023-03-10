<?php

namespace Botble\Garden\Http\Requests\Notices;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class NoticesGardenRequest extends Request
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
            'notices'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
