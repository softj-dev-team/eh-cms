<?php

namespace Botble\Campus\Http\Requests\Schedule;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ScheduleTimeLineRequest extends Request
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
            // 'day'=> 'required',
            // 'from' => 'required',
            // 'to' => 'required',
            'lecture_room' => 'required',
            'professor_name' => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
            'datetime' => 'required|array|min:1',
            'datetime.*' => 'required',
        ];
    }
}
