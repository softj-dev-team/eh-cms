<?php

namespace Botble\Introduction\Http\Requests\Faq;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class FaqIntroductionRequest extends Request
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
            'question'   => 'required',
            'answer'   => 'required',
            'faq_categories_id'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
