<?php

namespace Botble\Introduction\Http\Requests\Notices;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class NoticesIntroductionRequest extends Request
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
            'name'          => 'required',
            'notices'       => 'required',
            'status'        => Rule::in(BaseStatusEnum::values()),
            'code_id'       => 'array',
            'allow_comment' => 'nullable'
        ];
    }
}
