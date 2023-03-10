<?php

namespace Botble\Events\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CommentsRequest extends Request
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
            'content'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
