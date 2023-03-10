<?php

Route::group(['namespace' => 'Botble\Garden\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'gardens'], function () {

            Route::get('/', [
                'as' => 'garden.list',
                'uses' => 'GardenController@getList',
            ]);

            Route::get('/create', [
                'as' => 'garden.create',
                'uses' => 'GardenController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'garden.create',
                'uses' => 'GardenController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'garden.edit',
                'uses' => 'GardenController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'garden.edit',
                'uses' => 'GardenController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'garden.delete',
                'uses' => 'GardenController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'garden.delete.many',
                'uses' => 'GardenController@postDeleteMany',
                'permission' => 'garden.delete',
            ]);

            Route::group(['prefix' => 'categories'], function () {

                Route::get('/', [
                    'as' => 'garden.categories.list',
                    'uses' => 'CategoriesGardenController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'garden.categories.create',
                    'uses' => 'CategoriesGardenController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'garden.categories.create',
                    'uses' => 'CategoriesGardenController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'garden.categories.edit',
                    'uses' => 'CategoriesGardenController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'garden.categories.edit',
                    'uses' => 'CategoriesGardenController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'garden.categories.delete',
                    'uses' => 'CategoriesGardenController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'garden.categories.delete.many',
                    'uses' => 'CategoriesGardenController@postDeleteMany',
                    'permission' => 'garden.categories.delete',
                ]);
            });

            Route::group(['prefix' => 'password'], function () {

                Route::get('/reset', [
                    'as' => 'garden.password.reset',
                    'uses' => 'PasswordGardenController@getReset',
                ]);

                Route::get('/edit', [
                    'as' => 'garden.password.edit',
                    'uses' => 'PasswordGardenController@getEdit',
                ]);

                Route::post('/edit', [
                    'as' => 'garden.password.edit',
                    'uses' => 'PasswordGardenController@postEdit',
                ]);
            });

            Route::group(['prefix' => 'comments'], function () {

                Route::get('/{id}', [
                    'as' => 'garden.comments.list',
                    'uses' => 'CommentsGardenController@getList',
                ]);

                Route::get('/create/{id}', [
                    'as' => 'garden.comments.create',
                    'uses' => 'CommentsGardenController@getCreate',
                ]);

                Route::post('/create/{id}', [
                    'as' => 'garden.comments.create',
                    'uses' => 'CommentsGardenController@postCreate',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'garden.comments.delete',
                    'uses' => 'CommentsGardenController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'garden.comments.delete.many',
                    'uses' => 'CommentsGardenController@postDeleteMany',
                    'permission' => 'garden.comments.delete',
                ]);
            });
        });

        // pw garden
        Route::group(['prefix' => 'pw-gardens'], function () {
            Route::get('/', [
                'as' => 'garden.manage_pw.list',
                'uses' => 'PasswordGardenController@getManagePW',
            ]);
        });

        // Notices
        Route::group(['namespace' => 'Notices', 'prefix' => 'notices'], function () {
            Route::get('/', [
                'as' => 'garden.notices.list',
                'uses' => 'NoticesGardenController@getList',
            ]);

            Route::get('/create', [
                'as' => 'garden.notices.create',
                'uses' => 'NoticesGardenController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'garden.notices.create',
                'uses' => 'NoticesGardenController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'garden.notices.edit',
                'uses' => 'NoticesGardenController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'garden.notices.edit',
                'uses' => 'NoticesGardenController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'garden.notices.delete',
                'uses' => 'NoticesGardenController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'garden.notices.delete.many',
                'uses' => 'NoticesGardenController@postDeleteMany',
                'permission' => 'garden.notices.delete',
            ]);

        });

        // Description
        Route::group(['namespace' => 'Description', 'prefix' => 'description'], function () {
            Route::get('/', [
                'as' => 'garden.description.list',
                'uses' => 'DescriptionGardenController@getList',
            ]);

            Route::get('/create', [
                'as' => 'garden.description.create',
                'uses' => 'DescriptionGardenController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'garden.description.create',
                'uses' => 'DescriptionGardenController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'garden.description.edit',
                'uses' => 'DescriptionGardenController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'garden.description.edit',
                'uses' => 'DescriptionGardenController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'garden.description.delete',
                'uses' => 'DescriptionGardenController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'garden.description.delete.many',
                'uses' => 'DescriptionGardenController@postDeleteMany',
                'permission' => 'garden.description.delete',
            ]);

        });

        // Egarden
        Route::group(['namespace' => 'Egarden', 'prefix' => 'egarden'], function () {

            Route::get('/', [
                'as' => 'garden.egarden.list',
                'uses' => 'EgardenController@getList',
            ]);

            Route::get('/create', [
                'as' => 'garden.egarden.create',
                'uses' => 'EgardenController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'garden.egarden.create',
                'uses' => 'EgardenController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'garden.egarden.edit',
                'uses' => 'EgardenController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'garden.egarden.edit',
                'uses' => 'EgardenController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'garden.egarden.delete',
                'uses' => 'EgardenController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'garden.egarden.delete.many',
                'uses' => 'EgardenController@postDeleteMany',
                'permission' => 'garden.egarden.delete',
            ]);

            //Room
            Route::get('/room/', [
                'as' => 'garden.egarden.room.list',
                'uses' => 'RoomEgardenController@getList',
            ]);

            Route::get('/room/create', [
                'as' => 'garden.egarden.room.create',
                'uses' => 'RoomEgardenController@getCreate',
            ]);

            Route::post('/room/create', [
                'as' => 'garden.egarden.room.create',
                'uses' => 'RoomEgardenController@postCreate',
            ]);

            Route::get('/room/edit/{id}', [
                'as' => 'garden.egarden.room.edit',
                'uses' => 'RoomEgardenController@getEdit',
            ]);

            Route::post('/room/edit/{id}', [
                'as' => 'garden.egarden.room.edit',
                'uses' => 'RoomEgardenController@postEdit',
            ]);

            Route::get('/room/delete/{id}', [
                'as' => 'garden.egarden.room.delete',
                'uses' => 'RoomEgardenController@getDelete',
            ]);

            Route::post('/room/delete-many', [
                'as' => 'garden.egarden.room.delete.many',
                'uses' => 'RoomEgardenController@postDeleteMany',
                'permission' => 'garden.egarden.room.delete',
            ]);
            Route::get('/room/member/search', [
                'as' => 'garden.egarden.room.seach.member',
                'uses' => 'RoomEgardenController@getSeachMember',
                'permission' => 'garden.egarden.room.list'
            ]);
            Route::get('/room/author/search', [
                'as' => 'garden.egarden.room.seach.auhor',
                'uses' => 'RoomEgardenController@getSeachAuthor',
                'permission' => 'garden.egarden.room.list'
            ]);
            Route::post('/room/member/add', [
                'as' => 'garden.egarden.room.member.add',
                'uses' => 'RoomEgardenController@postAddMember',
                'permission' => 'garden.egarden.room.list'
            ]);
            Route::post('/room/author/add', [
                'as' => 'garden.egarden.room.author.add',
                'uses' => 'RoomEgardenController@postAddAuthor',
                'permission' => 'garden.egarden.room.list'
            ]);
            Route::post('/room/member/remove', [
                'as' => 'garden.egarden.room.member.remove',
                'uses' => 'RoomEgardenController@postRemoveMember',
                'permission' => 'garden.egarden.room.list'
            ]);
            Route::post('/room/categories/add', [
                'as' => 'garden.egarden.categories.member.add',
                'uses' => 'RoomEgardenController@postAddCategories',
                'permission' => 'garden.egarden.room.list'
            ]);
            Route::post('/room/categories/remove', [
                'as' => 'garden.egarden.categories.member.remove',
                'uses' => 'RoomEgardenController@postRemoveCategories',
                'permission' => 'garden.egarden.room.list'
            ]);

            //Comments
            Route::get('/comments/{id}', [
                'as' => 'garden.egarden.comments.list',
                'uses' => 'CommentsEgardenController@getList',
            ]);

            Route::get('/comments/create/{id}', [
                'as' => 'garden.egarden.comments.create',
                'uses' => 'CommentsEgardenController@getCreate',
            ]);

            Route::post('/comments/create/{id}', [
                'as' => 'garden.egarden.comments.create',
                'uses' => 'CommentsEgardenController@postCreate',
            ]);

            Route::get('/comments/delete/{id}', [
                'as' => 'garden.egarden.comments.delete',
                'uses' => 'CommentsEgardenController@getDelete',
            ]);

            Route::post('/comments/delete-many', [
                'as' => 'garden.egarden.comments.delete.many',
                'uses' => 'CommentsEgardenController@postDeleteMany',
                'permission' => 'garden.egarden.comments.delete',
            ]);
        });

        // Ewha
        Route::group(['prefix' => 'ewha'], function () {

            Route::get('/', [
                'as' => 'ewha.list',
                'uses' => 'EwhaController@getList',
            ]);

            Route::get('/create', [
                'as' => 'ewha.create',
                'uses' => 'EwhaController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'ewha.create',
                'uses' => 'EwhaController@postCreate',
            ]);

            Route::get('/detail/{id}', [
                'as' => 'ewha.detail',
                'uses' => 'EwhaController@getDetail',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'ewha.edit',
                'uses' => 'EwhaController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'ewha.delete',
                'uses' => 'EwhaController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'ewha.delete.many',
                'uses' => 'EwhaController@postDeleteMany',
                'permission' => 'garden.delete',
            ]);

            Route::group(['prefix' => 'comments'], function () {

                Route::get('/{id}', [
                    'as' => 'ewha.comments.list',
                    'uses' => 'CommentsEwhaController@getList',
                ]);

                Route::get('/create/{id}', [
                    'as' => 'ewha.comments.create',
                    'uses' => 'CommentsEwhaController@getCreate',
                ]);

                Route::post('/create/{id}', [
                    'as' => 'ewha.comments.create',
                    'uses' => 'CommentsEwhaController@postCreate',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'ewha.comments.delete',
                    'uses' => 'CommentsEwhaController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'ewha.comments.delete.many',
                    'uses' => 'CommentsEwhaController@postDeleteMany',
                    'permission' => 'garden.comments.delete',
                ]);
            });
        });

    });

});
