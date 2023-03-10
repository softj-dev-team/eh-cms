<?php

namespace Botble\Campus\Http\Requests\Genealogy;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class GenealogyRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Sang Nguyen
     */
    public function rules()
    {
        $rules =  [
            'semester_year'   => 'required',
            'semester_session'   => 'required',
            'class_name'   => 'required',
            'professor_name'   => 'required',
            'exam_name'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];

        if ($this->input('semester_session') == 3) {
            $rules['semester_other_textbox'] = 'required|max:32|';
        }
        return $rules;
      
    }
}
