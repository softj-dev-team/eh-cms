<?php
if (!defined("GOOGLE_FIREBASE_SEND_VERIFICATION_CODE")) {
    define(
        "GOOGLE_FIREBASE_SEND_VERIFICATION_CODE",
        "https://www.googleapis.com/identitytoolkit/v3/relyingparty/sendVerificationCode?key="
    );
}
if (!defined("GOOGLE_FIREBASE_VERIFY_PHONE_NUMBER_CODE")) {
    define(
        "GOOGLE_FIREBASE_VERIFY_PHONE_NUMBER_CODE",
        "https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPhoneNumber?key="
    );
}

if (!function_exists('table_actions_auth')) {

    function table_actions_auth($type, $route_aprroval, $route_deny, $item)
    {
        //return view('core.base::elements.tables.actions', compact('edit', 'delete', 'item', 'extra'))->render();
        return view('plugins.member::elements.field.actions_auth', compact('type','route_aprroval', 'route_deny','item'))->render();
    }
}
if (!function_exists('show_image')) {

    function show_image($link,$id)
    {
        return view('plugins.member::elements.field.show_image', compact('link','id'))->render();
    }
}
