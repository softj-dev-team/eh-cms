<?php

namespace Botble\Campus\Http\Requests\Evaluation;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CommentsEvaluationRequest extends Request
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
            'votes'   => 'required',
            'comments'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
