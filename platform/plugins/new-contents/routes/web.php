<?php

Route::group(['namespace' => 'Botble\NewContents\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'new-contents'], function () {

            Route::get('/', [
                'as' => 'new_contents.list',
                'uses' => 'NewContentsController@getList',
            ]);

            Route::get('/create', [
                'as' => 'new_contents.create',
                'uses' => 'NewContentsController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'new_contents.create',
                'uses' => 'NewContentsController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'new_contents.edit',
                'uses' => 'NewContentsController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'new_contents.edit',
                'uses' => 'NewContentsController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'new_contents.delete',
                'uses' => 'NewContentsController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'new_contents.delete.many',
                'uses' => 'NewContentsController@postDeleteMany',
                'permission' => 'new_contents.delete',
            ]);

            //categories
            Route::group(['prefix' => 'categories'], function () {

                Route::get('/', [
                    'as' => 'new_contents.categories.list',
                    'uses' => 'CategoriesNewContentsController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'new_contents.categories.create',
                    'uses' => 'CategoriesNewContentsController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'new_contents.categories.create',
                    'uses' => 'CategoriesNewContentsController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'new_contents.categories.edit',
                    'uses' => 'CategoriesNewContentsController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'new_contents.categories.edit',
                    'uses' => 'CategoriesNewContentsController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'new_contents.categories.delete',
                    'uses' => 'CategoriesNewContentsController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'new_contents.categories.delete.many',
                    'uses' => 'CategoriesNewContentsController@postDeleteMany',
                    'permission' => 'new_contents.categories.delete',
                ]);
            });

            //Comments
            Route::group(['prefix' => '{id}/comments'], function () {

                Route::get('/', [
                    'as' => 'new_contents.comments.list',
                    'uses' => 'CommentsNewContentsController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'new_contents.comments.create',
                    'uses' => 'CommentsNewContentsController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'new_contents.comments.create',
                    'uses' => 'CommentsNewContentsController@postCreate',
                ]);

                Route::get('/delete', [
                    'as' => 'new_contents.comments.delete',
                    'uses' => 'CommentsNewContentsController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'new_contents.comments.delete.many',
                    'uses' => 'CommentsNewContentsController@postDeleteMany',
                    'permission' => 'new_contents.comments.delete',
                ]);
            });


        });

        

        

        
    });
    
});