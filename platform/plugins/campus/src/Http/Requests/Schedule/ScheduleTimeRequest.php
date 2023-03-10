<?php

namespace Botble\Campus\Http\Requests\Schedule;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ScheduleTimeRequest extends Request
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
            'from'   => 'required',
            'to'=> 'required',
            'unit' => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
