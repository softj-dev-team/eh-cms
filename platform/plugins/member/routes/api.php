<?php

use Botble\Setting\Models\Setting;

Route::group([
    'prefix'     => 'api/v1/members',
    'namespace'  => 'Botble\Member\Http\Controllers',
    'middleware' => ['api'],
], function () {

    Route::post('register', 'ApiController@register')->name('members.api.register');
    Route::post('login', 'ApiController@login')->name('members.api.login');
    Route::post('login', 'ApiController@login')->name('members.api.login');

    Route::group(['middleware' => ['auth.jwt']], function () {
        Route::post('token-device', 'ApiController@tokenDevice')->name('members.api.token-device');
    });

    Route::group(['middleware' => ['auth:member-api']], function () {
        Route::get('logout', 'ApiController@logout')->name('members.api.logout');
        Route::post('update-profile', 'ApiController@updateProfile')->name('members.api.update-profile');
        Route::post('change-password', 'ApiController@updatePassword')->name('members.api.change-password');
    });

    Route::post('swear-word', function (){
        return \Botble\Member\Models\ForbiddenKeywords::where('type', 'swear_word')->where('status', 'publish')->pluck('title')->toArray();
    });
});
