<?php

namespace Botble\Campus\Http\Requests\Evaluation;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class EvaluationRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Sang Nguyen
     */
    public function rules()
    {

        $rules = [
            'title'   => 'required',
            'professor_name'   => 'required',
            'score'   => 'required',
//            'major'   => 'required',
            'grade'   => 'required',
            'remark'   => 'required',
//            'department'   => 'required',
//            'is_major'   => 'required',
        ];

        if($this->input('is_change_semester')){
            $rules['semester'] = 'required';
        }

        return $rules;
    }
}
