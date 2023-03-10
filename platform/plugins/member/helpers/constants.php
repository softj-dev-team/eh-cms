<?php

if (!defined('MEMBER_MODULE_SCREEN_NAME')) {
    define('MEMBER_MODULE_SCREEN_NAME', 'member');
}

if (!defined('MEMBER_TOP_MENU_FILTER')) {
    define('MEMBER_TOP_MENU_FILTER', 'member-top-menu');
}

if (!defined('MEMBER_TOP_STATISTIC_FILTER')) {
    define('MEMBER_TOP_STATISTIC_FILTER', 'member-top-statistic');
}

if (!defined('MEMBER_NOTES_MODULE_SCREEN_NAME')) {
    define('MEMBER_NOTES_MODULE_SCREEN_NAME', 'member_notes');
}

if (!defined('FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME')) {
    define('FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME', 'forbidden_keywords');
}
if (!defined('NOTIFY_MODULE_SCREEN_NAME')) {
    define('NOTIFY_MODULE_SCREEN_NAME', 'notify');
}
if (!defined('MEMBER_NOTIFY_MODULE_SCREEN_NAME')) {
    define('MEMBER_NOTIFY_MODULE_SCREEN_NAME', 'member_notify');
}

if (!defined('ERROR_MODULE_SCREEN_NAME')) {
    define('ERROR_MODULE_SCREEN_NAME', 'error');
}

define('STATUS_BLOCK_USER', [
    "0" => "선택안함",
    "1" => "비밀화원 금지", // block_secret_garden
    "2" => "전체금지", // block_all_service
    "3"=>"영구정지", // block_permanent
    "4"=>"강제탈퇴"
]);
