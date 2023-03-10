<?php

Route::group(['namespace' => 'Botble\MasterRoom\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'master-rooms'], function () {

            Route::get('/', [
                'as' => 'master_room.list',
                'uses' => 'MasterRoomController@getList',
            ]);

            Route::get('/create', [
                'as' => 'master_room.create',
                'uses' => 'MasterRoomController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'master_room.create',
                'uses' => 'MasterRoomController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'master_room.edit',
                'uses' => 'MasterRoomController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'master_room.edit',
                'uses' => 'MasterRoomController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'master_room.delete',
                'uses' => 'MasterRoomController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'master_room.delete.many',
                'uses' => 'MasterRoomController@postDeleteMany',
                'permission' => 'master_room.delete',
            ]);

            //categories
            Route::group(['prefix' => 'categories'], function () {

                Route::get('/', [
                    'as' => 'master_room.categories.list',
                    'uses' => 'CategoriesMasterRoomController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'master_room.categories.create',
                    'uses' => 'CategoriesMasterRoomController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'master_room.categories.create',
                    'uses' => 'CategoriesMasterRoomController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'master_room.categories.edit',
                    'uses' => 'CategoriesMasterRoomController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'master_room.categories.edit',
                    'uses' => 'CategoriesMasterRoomController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'master_room.categories.delete',
                    'uses' => 'CategoriesMasterRoomController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'master_room.categories.delete.many',
                    'uses' => 'CategoriesMasterRoomController@postDeleteMany',
                    'permission' => 'master_room.categories.delete',
                ]);
            });

            //Comments
            Route::group(['prefix' => '{id}/comments'], function () {

                Route::get('/', [
                    'as' => 'master_room.comments.list',
                    'uses' => 'CommentsMasterRoomController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'master_room.comments.create',
                    'uses' => 'CommentsMasterRoomController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'master_room.comments.create',
                    'uses' => 'CommentsMasterRoomController@postCreate',
                ]);

                Route::get('/delete', [
                    'as' => 'master_room.comments.delete',
                    'uses' => 'CommentsMasterRoomController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'master_room.comments.delete.many',
                    'uses' => 'CommentsMasterRoomController@postDeleteMany',
                    'permission' => 'master_room.comments.delete',
                ]);
            });

             //Address
             Route::group(['prefix' => 'address'], function () {

                Route::get('/', [
                    'as' => 'master_room.address.list',
                    'uses' => 'AddressMasterRoomController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'master_room.address.create',
                    'uses' => 'AddressMasterRoomController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'master_room.address.create',
                    'uses' => 'AddressMasterRoomController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'master_room.address.edit',
                    'uses' => 'AddressMasterRoomController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'master_room.address.edit',
                    'uses' => 'AddressMasterRoomController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'master_room.address.delete',
                    'uses' => 'AddressMasterRoomController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'master_room.address.delete.many',
                    'uses' => 'AddressMasterRoomController@postDeleteMany',
                    'permission' => 'master_room.address.delete',
                ]);
            });

        });

    });

});
