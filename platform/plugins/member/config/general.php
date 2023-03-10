<?php

use Botble\Member\Notifications\ConfirmEmail;

return [

    /*
    |--------------------------------------------------------------------------
    | Notification
    |--------------------------------------------------------------------------
    |
    | This is the notification class that will be sent to users when they receive
    | a confirmation code.
    |
    */
    'notification' => ConfirmEmail::class,

    'verify_email' => env('CMS_MEMBER_VERIFY_EMAIL', true),

    'avatar' => [
        'folder' => [
            'upload'        => public_path('uploads'),
            'container_dir' => 'members/avatars',
        ],
    ],
    'freshman1' => [
        'folder' => [
            'upload'        => public_path('uploads'),
            'container_dir' => 'members/freshman1',
        ],
    ],
    'freshman2' => [
        'folder' => [
            'upload'        => public_path('uploads'),
            'container_dir' => 'members/freshman2',
        ],
    ],
    'bookmark_limit' => [
        5 => 5,
        8 => 7,
        14 => 10,
        19 => 12,
        24 => 17,
        39 => 20,
        49 => 25,
        59 => 30,
        69 => 40,
        79 => 50,
        89 => 60,
        99 => 70,
        109 => 80,
        110 => 90,
    ]
];
