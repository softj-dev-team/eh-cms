<?php

namespace Botble\Life\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class FlareRequest extends Request
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
            'purchasing_price'   => 'required',
            'reason_selling'   => 'required',
            'sale_price'   => 'required',
            'contact'   => 'required',
            'detail'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
        if($this->input('exchange.4')){
            $rules['exchange.5'] = 'required';
        }

        return $rules;
    }
}
