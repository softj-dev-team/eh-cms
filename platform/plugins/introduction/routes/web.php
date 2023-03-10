<?php

Route::group(['namespace' => 'Botble\Introduction\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'introductions'], function () {

            Route::get('/', [
                'as' => 'introduction.list',
                'uses' => 'IntroductionController@getList',
            ]);

            Route::get('/create', [
                'as' => 'introduction.create',
                'uses' => 'IntroductionController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'introduction.create',
                'uses' => 'IntroductionController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'introduction.edit',
                'uses' => 'IntroductionController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'introduction.edit',
                'uses' => 'IntroductionController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'introduction.delete',
                'uses' => 'IntroductionController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'introduction.delete.many',
                'uses' => 'IntroductionController@postDeleteMany',
                'permission' => 'introduction.delete',
            ]);
            //Categories
            Route::group([ 'prefix' => 'categories'], function () {
                Route::get('/', [
                    'as' => 'introduction.categories.list',
                    'uses' => 'CategoriesIntroductionController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'introduction.categories.create',
                    'uses' => 'CategoriesIntroductionController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'introduction.categories.create',
                    'uses' => 'CategoriesIntroductionController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'introduction.categories.edit',
                    'uses' => 'CategoriesIntroductionController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'introduction.categories.edit',
                    'uses' => 'CategoriesIntroductionController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'introduction.categories.delete',
                    'uses' => 'CategoriesIntroductionController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'introduction.categories.delete.many',
                    'uses' => 'CategoriesIntroductionController@postDeleteMany',
                    'permission' => 'introduction.categories.delete',
                ]);

            });

            //Notices
            Route::group(['namespace' => 'Notices', 'prefix' => 'notices'], function () {
                Route::get('/', [
                    'as' => 'introduction.notices.list',
                    'uses' => 'NoticesIntroductionController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'introduction.notices.create',
                    'uses' => 'NoticesIntroductionController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'introduction.notices.create',
                    'uses' => 'NoticesIntroductionController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'introduction.notices.edit',
                    'uses' => 'NoticesIntroductionController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'introduction.notices.edit',
                    'uses' => 'NoticesIntroductionController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'introduction.notices.delete',
                    'uses' => 'NoticesIntroductionController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'introduction.notices.delete.many',
                    'uses' => 'NoticesIntroductionController@postDeleteMany',
                    'permission' => 'introduction.notices.delete',
                ]);

            });

            //Faq
            Route::group(['namespace' => 'Faq', 'prefix' => 'faq'], function () {
                Route::get('/', [
                    'as' => 'introduction.faq.list',
                    'uses' => 'FaqIntroductionController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'introduction.faq.create',
                    'uses' => 'FaqIntroductionController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'introduction.faq.create',
                    'uses' => 'FaqIntroductionController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'introduction.faq.edit',
                    'uses' => 'FaqIntroductionController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'introduction.faq.edit',
                    'uses' => 'FaqIntroductionController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'introduction.faq.delete',
                    'uses' => 'FaqIntroductionController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'introduction.faq.delete.many',
                    'uses' => 'FaqIntroductionController@postDeleteMany',
                    'permission' => 'introduction.faq.delete',
                ]);

                //Faq Categories
                Route::group(['prefix' => 'categories'], function () {
                    Route::get('/', [
                        'as' => 'introduction.faq.categories.list',
                        'uses' => 'FaqCategoriesController@getList',
                    ]);

                    Route::get('/create', [
                        'as' => 'introduction.faq.categories.create',
                        'uses' => 'FaqCategoriesController@getCreate',
                    ]);

                    Route::post('/create', [
                        'as' => 'introduction.faq.categories.create',
                        'uses' => 'FaqCategoriesController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'introduction.faq.categories.edit',
                        'uses' => 'FaqCategoriesController@getEdit',
                    ]);

                    Route::post('/edit/{id}', [
                        'as' => 'introduction.faq.categories.edit',
                        'uses' => 'FaqCategoriesController@postEdit',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'introduction.faq.categories.delete',
                        'uses' => 'FaqCategoriesController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'introduction.faq.categories.delete.many',
                        'uses' => 'FaqCategoriesController@postDeleteMany',
                        'permission' => 'introduction.faq.categories.delete',
                    ]);

                });

            });

        });
    });

});
