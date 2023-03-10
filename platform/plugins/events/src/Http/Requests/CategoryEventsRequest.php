<?php

namespace Botble\Events\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CategoryEventsRequest extends Request
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
            'name'   => 'required||unique:category_events,name,'.$this->id,
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
