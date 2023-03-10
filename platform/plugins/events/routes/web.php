<?php

Route::group(['namespace' => 'Botble\Events\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'events'], function () {

            Route::get('/', [
                'as' => 'events.list',
                'uses' => 'EventsController@getList',
            ]);

            Route::get('/create', [
                'as' => 'events.create',
                'uses' => 'EventsController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'events.create',
                'uses' => 'EventsController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'events.edit',
                'uses' => 'EventsController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'events.edit',
                'uses' => 'EventsController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'events.delete',
                'uses' => 'EventsController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'events.delete.many',
                'uses' => 'EventsController@postDeleteMany',
                'permission' => 'events.delete',
            ]);

            // comments
            Route::group(['prefix' => 'comments'], function () {

                Route::get('/{id}', [
                    'as' => 'events.comments.list',
                    'uses' => 'CommentsController@getList',
                ]);
    
                Route::get('/create/{id}', [
                    'as' => 'events.comments.create',
                    'uses' => 'CommentsController@getCreate',
                ]);
    
                Route::post('/create/{id}', [
                    'as' => 'events.comments.create',
                    'uses' => 'CommentsController@postCreate',
                ]);
    
                Route::get('/delete/{id}', [
                    'as' => 'events.comments.delete',
                    'uses' => 'CommentsController@getDelete',
                ]);
    
                Route::post('/delete-many', [
                    'as' => 'events.comments.delete.many',
                    'uses' => 'CommentsController@postDeleteMany',
                    'permission' => 'events.delete',
                ]);
            });

            // Category
            Route::group(['prefix' => 'category'], function () {

                Route::get('/', [
                    'as' => 'events.category.list',
                    'uses' => 'CategoryEventsController@getList',
                ]);
    
                Route::get('/create/', [
                    'as' => 'events.category.create',
                    'uses' => 'CategoryEventsController@getCreate',
                ]);
    
                Route::post('/create/', [
                    'as' => 'events.category.create',
                    'uses' => 'CategoryEventsController@postCreate',
                ]);
    
                Route::get('/edit/{id}', [
                    'as' => 'events.category.edit',
                    'uses' => 'CategoryEventsController@getEdit',
                ]);
    
                Route::post('/edit/{id}', [
                    'as' => 'events.category.edit',
                    'uses' => 'CategoryEventsController@postEdit',
                ]);
    
                Route::get('/delete/{id}', [
                    'as' => 'events.category.delete',
                    'uses' => 'CategoryEventsController@getDelete',
                ]);
    
                Route::post('/delete-many', [
                    'as' => 'events.category.delete.many',
                    'uses' => 'CategoryEventsController@postDeleteMany',
                    'permission' => 'events.delete',
                ]);
            });

            // EventsCmt
            Route::group(['prefix' => 'events-cmt'], function () {

                Route::get('/', [
                    'as' => 'events.cmt.list',
                    'uses' => 'EventsCmtController@getList',
                ]);
    
                Route::get('/create/', [
                    'as' => 'events.cmt.create',
                    'uses' => 'EventsCmtController@getCreate',
                ]);
    
                Route::post('/create/', [
                    'as' => 'events.cmt.create',
                    'uses' => 'EventsCmtController@postCreate',
                ]);
    
                Route::get('/edit/{id}', [
                    'as' => 'events.cmt.edit',
                    'uses' => 'EventsCmtController@getEdit',
                ]);
    
                Route::post('/edit/{id}', [
                    'as' => 'events.cmt.edit',
                    'uses' => 'EventsCmtController@postEdit',
                ]);
    
                Route::get('/delete/{id}', [
                    'as' => 'events.cmt.delete',
                    'uses' => 'EventsCmtController@getDelete',
                ]);
    
                Route::post('/delete-many', [
                    'as' => 'events.cmt.delete.many',
                    'uses' => 'EventsCmtController@postDeleteMany',
                    'permission' => 'events.cmt.delete',
                ]);

                 // comments
                Route::group(['prefix' => 'comments'], function () {

                    Route::get('/{id}', [
                        'as' => 'events.cmt.comments.list',
                        'uses' => 'CommentsEventsCmtController@getList',
                    ]);
        
                    Route::get('/create/{id}', [
                        'as' => 'events.cmt.comments.create',
                        'uses' => 'CommentsEventsCmtController@getCreate',
                    ]);
        
                    Route::post('/create/{id}', [
                        'as' => 'events.cmt.comments.create',
                        'uses' => 'CommentsEventsCmtController@postCreate',
                    ]);
        
                    Route::get('/delete/{id}', [
                        'as' => 'events.cmt.comments.delete',
                        'uses' => 'CommentsEventsCmtController@getDelete',
                    ]);
        
                    Route::post('/delete-many', [
                        'as' => 'events.cmt.comments.delete.many',
                        'uses' => 'CommentsEventsCmtController@postDeleteMany',
                        'permission' => 'events.cmt.delete',
                    ]);
                });
            });
        });
        
    });
    
});