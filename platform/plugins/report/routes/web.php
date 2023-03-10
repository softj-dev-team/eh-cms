<?php

Route::group(['namespace' => 'Botble\Report\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'reports'], function () {

            Route::get('/', [
                'as' => 'report.list',
                'uses' => 'ReportController@getList',
            ]);

            Route::get('/create', [
                'as' => 'report.create',
                'uses' => 'ReportController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'report.create',
                'uses' => 'ReportController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'report.edit',
                'uses' => 'ReportController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'report.edit',
                'uses' => 'ReportController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'report.delete',
                'uses' => 'ReportController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'report.delete.many',
                'uses' => 'ReportController@postDeleteMany',
                'permission' => 'report.delete',
            ]);
        });
    });

});
