<?php

namespace Botble\Events\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class EventsRequest extends Request
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
            'title'   => 'required|max:120',
            'start'   => 'required|date',
            'end'   => 'required|date',
            'enrollment_limit'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
