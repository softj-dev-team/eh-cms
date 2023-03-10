<?php

Route::group(['namespace' => 'Botble\Life\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'life'], function () {
            //life
            Route::get('/', [
                'as' => 'lives.list',
                'uses' => 'FlareController@getList',
            ]);
            //flare market
            Route::group(['prefix' => 'flea'], function () {
                Route::get('/', [
                    'as' => 'life.flare.list',
                    'uses' => 'FlareController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'life.flare.create',
                    'uses' => 'FlareController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'life.flare.create',
                    'uses' => 'FlareController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'life.flare.edit',
                    'uses' => 'FlareController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'life.flare.edit',
                    'uses' => 'FlareController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'life.flare.delete',
                    'uses' => 'FlareController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'life.flare.delete.many',
                    'uses' => 'FlareController@postDeleteMany',
                    'permission' => 'life.flare.delete',
                ]);

                // categories Flare
                Route::group(['prefix' => 'categories'], function () {
                    Route::get('/', [
                        'as' => 'life.flare.categories.list',
                        'uses' => 'FlareCategoriesController@getList',
                    ]);

                    Route::get('/create', [
                        'as' => 'life.flare.categories.create',
                        'uses' => 'FlareCategoriesController@getCreate',
                    ]);

                    Route::post('/create', [
                        'as' => 'life.flare.categories.create',
                        'uses' => 'FlareCategoriesController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'life.flare.categories.edit',
                        'uses' => 'FlareCategoriesController@getEdit',
                    ]);

                    Route::post('/edit/{id}', [
                        'as' => 'life.flare.categories.edit',
                        'uses' => 'FlareCategoriesController@postEdit',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'life.flare.categories.delete',
                        'uses' => 'FlareCategoriesController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'life.flare.categories.delete.many',
                        'uses' => 'FlareCategoriesController@postDeleteMany',
                        'permission' => 'life.flare.categories.delete',
                    ]);
                });
                // Comments
                Route::group(['prefix' => 'comments'], function () {

                    Route::get('/{id}', [
                        'as' => 'life.flare.comments.list',
                        'uses' => 'FlareCommentsController@getList',
                    ]);

                    Route::get('/create/{id}', [
                        'as' => 'life.flare.comments.create',
                        'uses' => 'FlareCommentsController@getCreate',
                    ]);

                    Route::post('/create/{id}', [
                        'as' => 'life.flare.comments.create',
                        'uses' => 'FlareCommentsController@postCreate',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'life.flare.comments.delete',
                        'uses' => 'FlareCommentsController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'life.flare.comments.delete.many',
                        'uses' => 'FlareCommentsController@postDeleteMany',
                        'permission' => 'life.flare.categories.delete',
                    ]);
                });
            });
            //Notices
            Route::group(['prefix' => 'notices'], function () {
                Route::get('/', [
                    'as' => 'life.notices.list',
                    'uses' => 'NoticesController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'life.notices.create',
                    'uses' => 'NoticesController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'life.notices.create',
                    'uses' => 'NoticesController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'life.notices.edit',
                    'uses' => 'NoticesController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'life.notices.edit',
                    'uses' => 'NoticesController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'life.notices.delete',
                    'uses' => 'NoticesController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'life.notices.delete.many',
                    'uses' => 'NoticesController@postDeleteMany',
                    'permission' => 'life.notices.delete',
                ]);
            });

             //Description
             Route::group(['prefix' => 'description'], function () {
                Route::get('/', [
                    'as' => 'life.description.list',
                    'uses' => 'DescriptionController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'life.description.create',
                    'uses' => 'DescriptionController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'life.description.create',
                    'uses' => 'DescriptionController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'life.description.edit',
                    'uses' => 'DescriptionController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'life.description.edit',
                    'uses' => 'DescriptionController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'life.description.delete',
                    'uses' => 'DescriptionController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'life.description.delete.many',
                    'uses' => 'DescriptionController@postDeleteMany',
                    'permission' => 'life.description.delete',
                ]);
            });


            //JobPartTime
            Route::group(['prefix' => 'jobs-part-time'], function () {
                Route::get('/', [
                    'as' => 'life.jobs_part_time.list',
                    'uses' => 'JobsPartTimeController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'life.jobs_part_time.create',
                    'uses' => 'JobsPartTimeController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'life.jobs_part_time.create',
                    'uses' => 'JobsPartTimeController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'life.jobs_part_time.edit',
                    'uses' => 'JobsPartTimeController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'life.jobs_part_time.edit',
                    'uses' => 'JobsPartTimeController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'life.jobs_part_time.delete',
                    'uses' => 'JobsPartTimeController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'life.jobs_part_time.delete.many',
                    'uses' => 'JobsPartTimeController@postDeleteMany',
                    'permission' => 'life.jobs-part-time.delete',
                ]);

                // Jobs categories
                Route::group(['prefix' => 'categories'], function () {
                    Route::get('/', [
                        'as' => 'life.jobs_part_time.categories.list',
                        'uses' => 'JobsCategoriesController@getList',
                    ]);

                    Route::get('/create', [
                        'as' => 'life.jobs_part_time.categories.create',
                        'uses' => 'JobsCategoriesController@getCreate',
                    ]);

                    Route::post('/create', [
                        'as' => 'life.jobs_part_time.categories.create',
                        'uses' => 'JobsCategoriesController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'life.jobs_part_time.categories.edit',
                        'uses' => 'JobsCategoriesController@getEdit',
                    ]);

                    Route::post('/edit/{id}', [
                        'as' => 'life.jobs_part_time.categories.edit',
                        'uses' => 'JobsCategoriesController@postEdit',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'life.jobs_part_time.categories.delete',
                        'uses' => 'JobsCategoriesController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'life.jobs_part_time.categories.delete.many',
                        'uses' => 'JobsCategoriesController@postDeleteMany',
                        'permission' => 'jobs_part_time.delete',
                    ]);
                });
                // Comments
                Route::group(['prefix' => 'comments'], function () {

                    Route::get('/{id}', [
                        'as' => 'life.jobs_part_time.comments.list',
                        'uses' => 'JobsCommentsController@getList',
                    ]);

                    Route::get('/create/{id}', [
                        'as' => 'life.jobs_part_time.comments.create',
                        'uses' => 'JobsCommentsController@getCreate',
                    ]);

                    Route::post('/create/{id}', [
                        'as' => 'life.jobs_part_time.comments.create',
                        'uses' => 'JobsCommentsController@postCreate',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'life.jobs_part_time.comments.delete',
                        'uses' => 'JobsCommentsController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'life.jobs_part_time.comments.delete.many',
                        'uses' => 'JobsCommentsController@postDeleteMany',
                        'permission' => 'life.jobs_part_time.comments.delete',
                    ]);
                });
            });

             //Advertisements
            Route::group(['prefix' => 'advertisements'], function () {
                Route::get('/', [
                    'as' => 'life.advertisements.list',
                    'uses' => 'AdsController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'life.advertisements.create',
                    'uses' => 'AdsController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'life.advertisements.create',
                    'uses' => 'AdsController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'life.advertisements.edit',
                    'uses' => 'AdsController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'life.advertisements.edit',
                    'uses' => 'AdsController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'life.advertisements.delete',
                    'uses' => 'AdsController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'life.advertisements.delete.many',
                    'uses' => 'AdsController@postDeleteMany',
                    'permission' => 'life.advertisements.delete',
                ]);

                // Advertisements categories
                Route::group(['prefix' => 'categories'], function () {
                    Route::get('/', [
                        'as' => 'life.advertisements.categories.list',
                        'uses' => 'AdsCategoriesController@getList',
                    ]);

                    Route::get('/create', [
                        'as' => 'life.advertisements.categories.create',
                        'uses' => 'AdsCategoriesController@getCreate',
                    ]);

                    Route::post('/create', [
                        'as' => 'life.advertisements.categories.create',
                        'uses' => 'AdsCategoriesController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'life.advertisements.categories.edit',
                        'uses' => 'AdsCategoriesController@getEdit',
                    ]);

                    Route::post('/edit/{id}', [
                        'as' => 'life.advertisements.categories.edit',
                        'uses' => 'AdsCategoriesController@postEdit',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'life.advertisements.categories.delete',
                        'uses' => 'AdsCategoriesController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'life.advertisements.categories.delete.many',
                        'uses' => 'AdsCategoriesController@postDeleteMany',
                        'permission' => 'life.advertisements.categories.delete',
                    ]);
                });
                // Advertisements Comments
                Route::group(['prefix' => 'comments'], function () {

                    Route::get('/{id}', [
                        'as' => 'life.advertisements.comments.list',
                        'uses' => 'AdsCommentsController@getList',
                    ]);

                    Route::get('/create/{id}', [
                        'as' => 'life.advertisements.comments.create',
                        'uses' => 'AdsCommentsController@getCreate',
                    ]);

                    Route::post('/create/{id}', [
                        'as' => 'life.advertisements.comments.create',
                        'uses' => 'AdsCommentsController@postCreate',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'life.advertisements.comments.delete',
                        'uses' => 'AdsCommentsController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'life.advertisements.comments.delete.many',
                        'uses' => 'AdsCommentsController@postDeleteMany',
                        'permission' => 'life.advertisements.comments.delete',
                    ]);
                });
            });

             //Shelter
            Route::group(['prefix' => 'shelter'], function () {
                Route::get('/', [
                    'as' => 'life.shelter.list',
                    'uses' => 'ShelterController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'life.shelter.create',
                    'uses' => 'ShelterController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'life.shelter.create',
                    'uses' => 'ShelterController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'life.shelter.edit',
                    'uses' => 'ShelterController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'life.shelter.edit',
                    'uses' => 'ShelterController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'life.shelter.delete',
                    'uses' => 'ShelterController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'life.shelter.delete.many',
                    'uses' => 'ShelterController@postDeleteMany',
                    'permission' => 'life.shelter.delete',
                ]);

                // Shelter categories
                Route::group(['prefix' => 'categories'], function () {
                    Route::get('/', [
                        'as' => 'life.shelter.categories.list',
                        'uses' => 'ShelterCategoriesController@getList',
                    ]);

                    Route::get('/create', [
                        'as' => 'life.shelter.categories.create',
                        'uses' => 'ShelterCategoriesController@getCreate',
                    ]);

                    Route::post('/create', [
                        'as' => 'life.shelter.categories.create',
                        'uses' => 'ShelterCategoriesController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'life.shelter.categories.edit',
                        'uses' => 'ShelterCategoriesController@getEdit',
                    ]);

                    Route::post('/edit/{id}', [
                        'as' => 'life.shelter.categories.edit',
                        'uses' => 'ShelterCategoriesController@postEdit',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'life.shelter.categories.delete',
                        'uses' => 'ShelterCategoriesController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'life.shelter.categories.delete.many',
                        'uses' => 'ShelterCategoriesController@postDeleteMany',
                        'permission' => 'life.shelter.categories.delete',
                    ]);
                });
                // Shelter Comments
                Route::group(['prefix' => 'comments'], function () {

                    Route::get('/{id}', [
                        'as' => 'life.shelter.comments.list',
                        'uses' => 'ShelterCommentsController@getList',
                    ]);

                    Route::get('/create/{id}', [
                        'as' => 'life.shelter.comments.create',
                        'uses' => 'ShelterCommentsController@getCreate',
                    ]);

                    Route::post('/create/{id}', [
                        'as' => 'life.shelter.comments.create',
                        'uses' => 'ShelterCommentsController@postCreate',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'life.shelter.comments.delete',
                        'uses' => 'ShelterCommentsController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'life.shelter.comments.delete.many',
                        'uses' => 'ShelterCommentsController@postDeleteMany',
                        'permission' => 'life.shelter.comments.delete',
                    ]);
                });
            });

              //Open Space
            Route::group(['prefix' => 'open-space'], function () {
                Route::get('/', [
                    'as' => 'life.open.space.list',
                    'uses' => 'OpenSpaceController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'life.open.space.create',
                    'uses' => 'OpenSpaceController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'life.open.space.create',
                    'uses' => 'OpenSpaceController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'life.open.space.edit',
                    'uses' => 'OpenSpaceController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'life.open.space.edit',
                    'uses' => 'OpenSpaceController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'life.open.space.delete',
                    'uses' => 'OpenSpaceController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'life.open.space.delete.many',
                    'uses' => 'OpenSpaceController@postDeleteMany',
                    'permission' => 'life.open.space.delete',
                ]);

                //Open Space Comments
                Route::group(['prefix' => 'comments'], function () {

                    Route::get('/{id}', [
                        'as' => 'life.open.space.comments.list',
                        'uses' => 'OpenSpaceCommentsController@getList',
                    ]);

                    Route::get('/create/{id}', [
                        'as' => 'life.open.space.comments.create',
                        'uses' => 'OpenSpaceCommentsController@getCreate',
                    ]);

                    Route::post('/create/{id}', [
                        'as' => 'life.open.space.comments.create',
                        'uses' => 'OpenSpaceCommentsController@postCreate',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'life.open.space.comments.delete',
                        'uses' => 'OpenSpaceCommentsController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'life.open.space.comments.delete.many',
                        'uses' => 'OpenSpaceCommentsController@postDeleteMany',
                        'permission' => 'life.open.space.comments.delete',
                    ]);
                });
            });

        });
    });
});
