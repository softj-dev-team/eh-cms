<?php

namespace Botble\Member\Http\Requests;
use Botble\Support\Http\Requests\Request;

class SendMailRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Pippo
     */
    public function rules()
    {
        return [
            'title'   => 'required',
            'content'   => 'required',
            'mailTo'   => 'required',
        ];
    }
}
