<?php

Route::group(['namespace' => 'Botble\Slides\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'slides'], function () {

            Route::get('/', [
                'as' => 'slides.list',
                'uses' => 'SlidesController@getList',
            ]);

            Route::get('/create', [
                'as' => 'slides.create',
                'uses' => 'SlidesController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'slides.create',
                'uses' => 'SlidesController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'slides.edit',
                'uses' => 'SlidesController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'slides.edit',
                'uses' => 'SlidesController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'slides.delete',
                'uses' => 'SlidesController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'slides.delete.many',
                'uses' => 'SlidesController@postDeleteMany',
                'permission' => 'slides.delete',
            ]);
        });
    });

});
