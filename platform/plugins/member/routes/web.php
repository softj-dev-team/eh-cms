<?php

Route::group([
    'namespace'  => 'Botble\Member\Http\Controllers',
    'prefix'     => config('core.base.general.admin_dir'),
    'middleware' => ['web', 'auth'],
], function () {
    Route::group(['prefix' => 'members'], function () {
        Route::get('', [
            'as'   => 'member.list',
            'uses' => 'MemberController@getList',
        ]);

        Route::get('create', [
            'as'   => 'member.create',
            'uses' => 'MemberController@getCreate',
        ]);

        Route::post('create', [
            'as'   => 'member.create',
            'uses' => 'MemberController@postCreate',
        ]);

        Route::get('edit/{id}', [
            'as'   => 'member.edit',
            'uses' => 'MemberController@getEdit',
        ]);

        Route::post('edit/{id}', [
            'as'   => 'member.edit',
            'uses' => 'MemberController@postEdit',
        ]);

        Route::get('delete/{id}', [
            'as'   => 'member.delete',
            'uses' => 'MemberController@getDelete',
        ]);

        Route::get('blacklist-delete/{id}', [
            'as'   => 'blacklist.delete',
            'uses' => 'MemberController@deleteBlacklist',
        ]);

        Route::post('set-hooligan', [
            'as'   => 'set-hooligan',
            'uses' => 'MemberController@setHooligan',
        ]);

        Route::post('approve-ownership', [
            'as'   => 'approve-ownership',
            'uses' => 'MemberController@approveOwnership',
        ]);

        Route::post('delete-many', [
            'as'         => 'member.delete.many',
            'uses'       => 'MemberController@postDeleteMany',
            'permission' => 'member.delete',
        ]);

        Route::group(['prefix' => 'notify'], function () {
            Route::get('', [
                'as'   => 'member.notify.list',
                'uses' => 'MemberNotifyController@getList',
            ]);

            Route::get('create', [
                'as'   => 'member.notify.create',
                'uses' => 'MemberNotifyController@getCreate',
            ]);

            Route::post('create', [
                'as'   => 'member.notify.create',
                'uses' => 'MemberNotifyController@postCreate',
            ]);

            Route::get('create-1', [
                'as'   => 'member.notify1.create',
                'uses' => 'MemberNotifyController@getCreateNotify1',
            ]);

            Route::post('create-1', [
                'as'   => 'member.notify1.create',
                'uses' => 'MemberNotifyController@postCreateNotify1',
            ]);

            Route::get('create-2', [
                'as'   => 'member.notify2.create',
                'uses' => 'MemberNotifyController@getCreateNotify2',
            ]);

            Route::post('create-2', [
                'as'   => 'member.notify2.create',
                'uses' => 'MemberNotifyController@postCreateNotify1',
            ]);

            Route::get('edit/{id}', [
                'as'   => 'member.notify.edit',
                'uses' => 'MemberNotifyController@getEdit',
            ]);

            Route::post('edit/{id}', [
                'as'   => 'member.notify.edit',
                'uses' => 'MemberNotifyController@postEdit',
            ]);

            Route::get('delete/{id}', [
                'as'   => 'member.notify.delete',
                'uses' => 'MemberNotifyController@getDelete',
            ]);

            Route::post('delete-many', [
                'as'         => 'member.notify.delete.many',
                'uses'       => 'MemberNotifyController@postDeleteMany',
                'permission' => 'member.notify.delete',
            ]);

            Route::get('search/member', [
                'as'   => 'member.notify.search',
                'uses' => 'MemberNotifyController@getSearchMember',
            ]);

            Route::post('search/member1', [
                'as'   => 'member.notify.search1',
                'uses' => 'MemberNotifyController@getSearchMember1',
            ]);

            Route::post('search/notify/add', [
                'as'   => 'member.notify.add',
                'uses' => 'MemberNotifyController@postAddMemberNotify',
            ]);
            Route::post('search/notify/delete', [
                'as'   => 'member.notify.search.delete',
                'uses' => 'MemberNotifyController@postDeleteMemberNotify',
            ]);
        });
        Route::group(['prefix' => 'blacklist'], function () {
            Route::get('', [
                'as'   => 'member.blacklist.list',
                'uses' => 'MemberController@getBlackList',
            ]);
        });
    });

    Route::group(['prefix' => 'authentication'], function () {

        Route::group(['prefix' => 'sprout'], function () {
            Route::get('', [
                'as'   => 'member.authentication.sprout.list',
                'uses' => 'MemberController@getSproutAuthList',
            ]);

            Route::post('{id}/{approval}', [
                'as'   => 'member.authentication.sprout.update',
                'uses' => 'MemberController@getUpdateSproutAuthList',
            ]);
        });

        Route::group(['prefix' => 'ewhaian'], function () {
            Route::get('', [
                'as'   => 'member.authentication.ewhaian.list',
                'uses' => 'MemberController@getEwhaianAuthList',
            ]);
            Route::post('{id}/{approval}', [
                'as'   => 'member.authentication.ewhaian.update',
                'uses' => 'MemberController@getUpdateEwhaianAuthList',
            ]);
        });


    });

    Route::group(['prefix' => 'forbidden'], function () {
        Route::get('', [
            'as'   => 'member.forbidden.list',
            'uses' => 'ForbiddenController@getList',
        ]);

        Route::get('create', [
            'as'   => 'member.forbidden.create',
            'uses' => 'ForbiddenController@getCreate',
        ]);

        Route::post('create', [
            'as'   => 'member.forbidden.create',
            'uses' => 'ForbiddenController@postCreate',
        ]);

        Route::get('edit/{id}', [
            'as'   => 'member.forbidden.edit',
            'uses' => 'ForbiddenController@getEdit',
        ]);

        Route::post('edit/{id}', [
            'as'   => 'member.forbidden.edit',
            'uses' => 'ForbiddenController@postEdit',
        ]);

        Route::get('delete/{id}', [
            'as'   => 'member.forbidden.delete',
            'uses' => 'ForbiddenController@getDelete',
        ]);

        Route::post('delete-many', [
            'as'         => 'member.forbidden.delete.many',
            'uses'       => 'ForbiddenController@postDeleteMany',
            'permission' => 'member.forbidden.delete',
        ]);
    });
    Route::group(['prefix' => 'visits'], function () {
        Route::group(['prefix' => 'daily'], function () {
            Route::get('', [
                'as'   => 'member.daily.visits',
                'uses' => 'VisitsController@getVisitDaily',
                'permission' => 'member.visits.daily',
            ]);
        });

        Route::group(['prefix' => 'weekly'], function () {
            Route::get('', [
                'as'   => 'member.weekly.visits',
                'uses' => 'VisitsController@getVisitWeekly',
                'permission' => 'member.visits.weekly',
            ]);
        });
        Route::group(['prefix' => 'monthly'], function () {
            Route::get('', [
                'as'   => 'member.monthly.visits',
                'uses' => 'VisitsController@getVisitMonthly',
                'permission' => 'member.visits.monthly',
            ]);
        });
        Route::group(['prefix' => 'user-types'], function () {
            Route::get('', [
                'as'   => 'member.user.types.visits',
                'uses' => 'VisitsController@getVisitUserTypes',
                'permission' => 'member.visits.user.types',
            ]);
        });


    });
});

if (defined('THEME_MODULE_SCREEN_NAME')) {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::group([
            'namespace'  => 'Botble\Member\Http\Controllers',
            'middleware' => ['web'],
            'as'         => 'public.member.',
        ], function () {

            Route::group(['middleware' => ['member.guest']], function () {
                Route::get('login', 'LoginController@showLoginForm')->name('login');
                Route::post('login', 'LoginController@login')->name('login.post');

                Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
                Route::post('register', 'RegisterController@register')->name('register.post');

                Route::get('verify', 'RegisterController@getVerify')->name('verify');
                Route::post('send-sms', 'RegisterController@sendSMS')->name('send.sms');
                Route::post('verify-send-sms', 'RegisterController@postVerify')->name('verify.sms');

                Route::get('email/verify', 'RegisterController@emailVerify')->name('verify.email');
                Route::get('email/resend', 'RegisterController@emailResend')->name('verify.email.resend');
                Route::post('email/verify/send', 'RegisterController@sendVerifyMail')->name('verify.email.resend.post');

                Route::get('password/request',
                    'ForgotPasswordController@showLinkRequestForm')->name('password.request');
                Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
                Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
                Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');

                Route::post('password/find-ID', 'FindIDController@findID')->name('findID');
            });

            Route::group([
                'middleware' => [config('plugins.member.general.verify_email') ? 'member.guest' : 'member'],
            ], function () {
                Route::get('register/confirm/resend',
                    'RegisterController@resendConfirmation')->name('resend_confirmation');
                Route::get('register/confirm/{email}', 'RegisterController@confirm')->name('confirm');
            });
        });

        Route::group([
            'namespace'  => 'Botble\Member\Http\Controllers',
            'middleware' => ['web', 'member'],
            'as'         => 'public.member.',
        ], function () {
            Route::group([
                'prefix' => 'account',
            ], function () {

                Route::post('logout', 'LoginController@logout')->name('logout');

                Route::get('dashboard', [
                    'as'   => 'dashboard',
                    'uses' => 'PublicController@getDashboard',
                ]);

                Route::get('settings', [
                    'as'   => 'settings',
                    'uses' => 'PublicController@getSettings',
                ]);

                Route::post('settings', [
                    'as'   => 'post.settings',
                    'uses' => 'PublicController@postSettings',
                ]);

                Route::get('security', [
                    'as'   => 'security',
                    'uses' => 'PublicController@getSecurity',
                ]);

                Route::put('security', [
                    'as'   => 'post.security',
                    'uses' => 'PublicController@postSecurity',
                ]);

                Route::get('notification', [
                    'as'   => 'notification',
                    'uses' => 'PublicController@getNotification',
                ]);

                Route::post('notification', 'PublicController@postNotification')->name('post.notification');

                Route::post('avatar', [
                    'as'   => 'avatar',
                    'uses' => 'PublicController@postAvatar',
                ]);

                Route::post('delete', [
                    'as'   => 'post.account.delete',
                    'uses' => 'PublicController@postAccountDelete',
                ]);

                Route::get('sprout-authentication', [
                    'as'   => 'get.freshman.sprout',
                    'uses' => 'PublicController@getSproutAuthentication',
                ]);

                Route::get('ewhain-authentication', [
                    'as'   => 'get.freshman.ewhain',
                    'uses' => 'PublicController@getEwhainAuthentication',
                ]);

                Route::post('freshman', [
                    'as'   => 'post.freshman',
                    'uses' => 'PublicController@postFreshman',
                ]);

                Route::post('cancel-request/{id}', [
                    'as'   => 'put.cancel-request',
                    'uses' => 'PublicController@cancelRequest',
                ]);

                Route::post('delete-image/{id}', [
                    'as'   => 'del.delete-image',
                    'uses' => 'PublicController@deleteImage',
                ]);

            });

            Route::group(['prefix' => 'ajax/members'], function () {
                Route::get('activity-logs', [
                    'as'   => 'activity-logs',
                    'uses' => 'PublicController@getActivityLogs',
                ]);

                Route::post('upload', [
                    'as'   => 'upload',
                    'uses' => 'PublicController@postUpload',
                ]);
            });
        });

    });
}
