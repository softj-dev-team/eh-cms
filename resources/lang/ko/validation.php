<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute 을(를) 반드시 수락해주세요.',
    'active_url'           => ':attribute 은 올바른 URL이 아닙니다.',
    'after'                => ':attribute에는 :date보다 이후의 날짜가 들어가야 합니다.',
    'after_or_equal'       => ':attribute에는 :date보다 이후거나 같은 날짜가 들어가야 합니다.',
    'alpha'                => ':attribute에는  문자만 포함할 수 있습니다.',
    'alpha_dash'           => ':attribute 에는 문자, 숫자 및 대시만 포함할 수 있습니다.',
    'alpha_num'            => ':attribute에는  문자와 숫자만 포함할 수 있습니다.',
    'array'                => ':attribute 은(는) 배열이어야 합니다.',
    'before'               => ':attribute 에는 :date보다 이전 날짜가 들어가야 합니다.',
    'before_or_equal'      => ':attribute에는 :date보다 이전이거나 같은 날짜가 들어가야 합니다.',
    'between'              => [
        'numeric' => ':min과 :max사이의 수치가 들어가야 합니다.',
        'file'    => ' :min과 :max 킬로바이트 사이의 파일만 허용됩니다.',
        'string'  => ' :min에서 :max 글자 사이여야 합니다.',
        'array'   => '항목의 갯수가 :min과 :max사이여야 합니다.',
    ],
    'boolean'              => '값은 true 또는 false 여야 합니다.',
    'confirmed'            => '확인이 일치하지 않습니다.',
    'date'                 => '유효한 날짜가 아닙니다.',
    'date_format'          => '형식이 :format과 일치하지 않습니다 .',
    'different'            => ':attribute과(와) :other은(는) 반드시 서로 달라야합니다.',
    'digits'               => ':digits 자리 숫자 로 작성 해주세요.',
    'digits_between'       => '반드시 :min에서  :max 자릿수 사이여야 합니다.',
    'dimensions'           => '이미지 크기가 잘못되었습니다.',
    'distinct'             => '중복된 값이 있습니다.',
    'email'                => ' 유효한 이메일이 아닙니다.',
    'exists'               => '유효한 값이 아닙니다.',
    'file'                 => '유효한 파일이 아닙니다.',
    'filled'               => '반드시 값이 입력되어야 합니다.',
    'image'                => '유효한 이미지가 아닙니다.',
    'in'                   => '유효한 값이 아닙니다.',
    'in_array'             => ':attribute은(는) 입력란에 존재하지 않는 항목입니다.',
    'integer'              => '정수가 아닌 값입니다.',
    'ip'                   => '유효한 IP 주소가 아닙니다.',
    'ipv4'                 => '유효한 IPv4 주소가 아닙니다.',
    'ipv6'                 => '유효한 IPv6 주소가 아닙니다.',
    'json'                 => '유효한 JSON 문자가 아닙니다.',
    'max'                  => [
        'numeric' => ' :max보다 클 수 없습니다.',
        'file'    => ':max 킬로바이트보다 클 수 없습니다.',
        'string'  => ':max 글자보다 많을 수 없습니다.',
        'array'   => ':max 항목보다 많을 수 없습니다.',
    ],
    'mimes'                => '파일의 형식이 :values여야 합니다.',
    'mimetypes'            =>'파일의 형식이 :values여야 합니다.',
    'min'                  => [
        'numeric' => ':min보다 작아야 합니다..',
        'file'    =>  ':min 킬로바이트보다 작아야 합니다.',
        'string'  => ':min 글자보다 적어야 합니다.',
        'array'   => ':min 항목보다 적어야 합니다.',
    ],
    'not_in'               => '유효한 값이 아닙니다.',
    'numeric'              => '값이 숫자가 아닙니다.',
    'present'              => '필드가 존재하지 않습니다.',
    'regex'                => '형식이 유효하지 않습니다.',
    'required'             => '필드가 필요합니다.',
    'required_if'          => ':other이(가)  :value(이)려면 필드가 필요합니다.',
    'required_unless'      => ':other이(가)  :value(이)지 않는 한 필드가 필요합니다.',
    'required_with'        => ':values가 존재할 경우 필드가 필요합니다.',
    'required_with_all'    => ':values가 존재할 경우 필드가 필요합니다.',
    'required_without'     => ':values가 존재하지 않을 경우 필드가 필요합니다.',
    'required_without_all' => ':values가 존재하지 않을 경우 필드가 필요합니다.',
    'same'                 => ':attribute 와(과) :other이(가) 일치해야합니다.',
    'size'                 => [
        'numeric' => '크기가 반드시 :size와 일치해야 합니다.',
        'file'    => '반드시 :size  킬로바이트여야 합니다.',
        'string'  => '반드시 :size  글자여야 합니다.',
        'array'   => '반드시 :size 항목을 포함해야 합니다.',
    ],
    'string'               => '값은 반드시 문자열이어야 합니다.',
    'timezone'             => '유효한 영역이 아닙니다.',
    'unique'               => '이미 존재하는 이메일입니다.',
    'uploaded'             => '업로드에 실패하였습니다.',
    'url'                  => '형식이 유효하지 않습니다.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [

        'namemail' => [
            'required' => '이메일을 입력해주세요',
        ],

        'id_login' => [
            'required' => '아이디를 입력해주세요',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
