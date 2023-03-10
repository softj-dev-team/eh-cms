<?php

namespace Botble\Campus\Http\Requests\Schedule;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ScheduleFilterRequest extends Request
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
            'start'=> 'required',
            'end' => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
