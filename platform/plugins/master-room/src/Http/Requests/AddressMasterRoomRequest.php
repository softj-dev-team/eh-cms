<?php

namespace Botble\MasterRoom\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AddressMasterRoomRequest extends Request
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
            'address'   => 'required',
            'email'   => 'email',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
