<?php

namespace Botble\Life\Http\Requests\Ads;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AdsRequest extends Request
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
            'details'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
