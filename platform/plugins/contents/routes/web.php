<?php

Route::group(['namespace' => 'Botble\Contents\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'contents'], function () {

            Route::get('/', [
                'as' => 'contents.list',
                'uses' => 'ContentsController@getList',
            ]);

            Route::get('/create', [
                'as' => 'contents.create',
                'uses' => 'ContentsController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'contents.create',
                'uses' => 'ContentsController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'contents.edit',
                'uses' => 'ContentsController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'contents.edit',
                'uses' => 'ContentsController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'contents.delete',
                'uses' => 'ContentsController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'contents.delete.many',
                'uses' => 'ContentsController@postDeleteMany',
                'permission' => 'contents.delete',
            ]);

            Route::post('/register-main-content', [
                'as' => 'contents.register.main.content',
                'uses' => 'ContentsController@postRegisterMainContent',
            ]);

            // Categories
            Route::group(['prefix' => 'categories'], function () {

                Route::get('/', [
                    'as' => 'contents.categories.list',
                    'uses' => 'CategoriesContentsController@getList',
                ]);

                Route::get('/create/', [
                    'as' => 'contents.categories.create',
                    'uses' => 'CategoriesContentsController@getCreate',
                ]);

                Route::post('/create/', [
                    'as' => 'contents.categories.create',
                    'uses' => 'CategoriesContentsController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'contents.categories.edit',
                    'uses' => 'CategoriesContentsController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'contents.categories.edit',
                    'uses' => 'CategoriesContentsController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'contents.categories.delete',
                    'uses' => 'CategoriesContentsController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'contents.categories.delete.many',
                    'uses' => 'CategoriesContentsController@postDeleteMany',
                    'permission' => 'contents.delete',
                ]);
            });

            // Comments
            Route::group(['prefix' => 'comments'], function () {

                Route::get('/{id}', [
                    'as' => 'contents.comments.list',
                    'uses' => 'CommentsContentsController@getList',
                ]);

                Route::get('/create/{id}', [
                    'as' => 'contents.comments.create',
                    'uses' => 'CommentsContentsController@getCreate',
                ]);

                Route::post('/create/{id}', [
                    'as' => 'contents.comments.create',
                    'uses' => 'CommentsContentsController@postCreate',
                ]);

                // Route::get('/edit/{id}', [
                //     'as' => 'contents.comments.edit',
                //     'uses' => 'CommentsContentsController@getEdit',
                // ]);

                // Route::post('/edit/{id}', [
                //     'as' => 'contents.comments.edit',
                //     'uses' => 'CommentsContentsController@postEdit',
                // ]);

                Route::get('/delete/{id}', [
                    'as' => 'contents.comments.delete',
                    'uses' => 'CommentsContentsController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'contents.comments.delete.many',
                    'uses' => 'CommentsContentsController@postDeleteMany',
                    'permission' => 'contents.delete',
                ]);
            });
        });

        Route::group(['prefix' => 'sl-contents'], function () {

            Route::get('', [
                'as' => 'contents.slides.list',
                'uses' => 'SlidesContentsController@getList',
            ]);

            Route::get('/create/', [
                'as' => 'contents.slides.create',
                'uses' => 'SlidesContentsController@getCreate',
            ]);

            Route::post('/create/', [
                'as' => 'contents.slides.create',
                'uses' => 'SlidesContentsController@postCreate',
            ]);
            
            Route::get('/delete/{id}', [
                'as' => 'contents.slides.delete',
                'uses' => 'SlidesContentsController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'contents.slides.delete.many',
                'uses' => 'SlidesContentsController@postDeleteMany',
                'permission' => 'contents.slides.delete',
            ]);
        });

    });
});
