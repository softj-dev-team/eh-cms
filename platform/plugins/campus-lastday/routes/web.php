<?php

Route::group(['namespace' => 'Botble\CampusLastday\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'campus-lastdays'], function () {

            Route::get('/', [
                'as' => 'campus_lastday.list',
                'uses' => 'CampusLastdayController@getList',
            ]);

            Route::get('/create', [
                'as' => 'campus_lastday.create',
                'uses' => 'CampusLastdayController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'campus_lastday.create',
                'uses' => 'CampusLastdayController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'campus_lastday.edit',
                'uses' => 'CampusLastdayController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'campus_lastday.edit',
                'uses' => 'CampusLastdayController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'campus_lastday.delete',
                'uses' => 'CampusLastdayController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'campus_lastday.delete.many',
                'uses' => 'CampusLastdayController@postDeleteMany',
                'permission' => 'campus_lastday.delete',
            ]);
        });
    });
    
});