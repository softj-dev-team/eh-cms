<?php

return [
    'system_exceptions' => [
        'unknown_error' => [
            'message_code' => "UNKNOWN_ERROR",
            'message' => "Unknown error."
        ],
        'unauthorized' => [
            'message_code' => "UNAUTHORIZED",
            'message' => "Unauthorized"
        ],
        'missing_params' => [
            'message_code' => "MISSING_PARAMS",
            'message' => "There are missing parameters."
        ],
        'resource_not_found' => [
            'message_code' => "RESOURCE_NOT_FOUND",
            'message' => "Resource not found."
        ],
        'invalid_params' => [
            'message_code' => "INVALID_PARAMS",
            'message' => "There are invalid parameters."
        ],
    ],

    'account' => [
        'messages' => [
            'invalid_password' => [
                'message_code' => "INVALID_PASSWORD",
                'message' => "Invalid password"
            ],
            'invalid_otp' => [
                'message_code' => "INVALID_OTP",
                'message' => "Invalid OTP"
            ],
            'not_found' => [
                'message_code' => "ACCOUNT_NOT_FOUND",
                'message' => "Account not found"
            ],
            'token_not_found' => [
                'message_code' => "TOKEN_NOT_FOUND",
                'message' => "Token not found"
            ],
            'otp_not_found' => [
                'message_code' => "OTP_NOT_FOUND",
                'message' => "OTP not found"
            ],
            'user_not_found' => [
                'message_code' => "USER_NOT_FOUND",
                'message' => "User not found"
            ],
            'token_expired' => [
                'message_code' => "TOKEN_EXPIRED",
                'message' => "Token is expired"
            ],
            'email_send_failed' => [
                'message_code' => "EMAIL_SEND_FAILED",
                'message' => "Failed to send email"
            ]
        ]
    ],
];
