<?php

namespace Botble\Life\Http\Requests\Jobs;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class JobsPartTimeRequest extends Request
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
            'contact'   => 'required',
            'detail'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
