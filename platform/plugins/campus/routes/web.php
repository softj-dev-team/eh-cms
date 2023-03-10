<?php

Route::group(['namespace' => 'Botble\Campus\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'campus'], function () {
            Route::group(['namespace' => 'StudyRoom','prefix' => 'study-room'], function () {
                Route::get('/', [
                    'as' => 'campus.study_room.list',
                    'uses' => 'StudyRoomController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'campus.study_room.create',
                    'uses' => 'StudyRoomController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'campus.study_room.create',
                    'uses' => 'StudyRoomController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'campus.study_room.edit',
                    'uses' => 'StudyRoomController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'campus.study_room.edit',
                    'uses' => 'StudyRoomController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'campus.study_room.delete',
                    'uses' => 'StudyRoomController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'campus.study_room.delete.many',
                    'uses' => 'StudyRoomController@postDeleteMany',
                    'permission' => 'campus.study_room.delete',
                ]);

                // Study Room Categories
                Route::group(['prefix' => 'categories'], function () {
                    Route::get('/', [
                        'as' => 'campus.study_room.categories.list',
                        'uses' => 'StudyRoomCategoriesController@getList',
                    ]);

                    Route::get('/create', [
                        'as' => 'campus.study_room.categories.create',
                        'uses' => 'StudyRoomCategoriesController@getCreate',
                    ]);

                    Route::post('/create', [
                        'as' => 'campus.study_room.categories.create',
                        'uses' => 'StudyRoomCategoriesController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'campus.study_room.categories.edit',
                        'uses' => 'StudyRoomCategoriesController@getEdit',
                    ]);

                    Route::post('/edit/{id}', [
                        'as' => 'campus.study_room.categories.edit',
                        'uses' => 'StudyRoomCategoriesController@postEdit',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'campus.study_room.categories.delete',
                        'uses' => 'StudyRoomCategoriesController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'campus.study_room.categories.delete.many',
                        'uses' => 'StudyRoomCategoriesController@postDeleteMany',
                        'permission' => 'campus.study_room.categories.delete',
                    ]);
                });
                // Comments
                Route::group(['prefix' => 'comments'], function () {

                    Route::get('/{id}', [
                        'as' => 'campus.study_room.comments.list',
                        'uses' => 'StudyRoomCommentsController@getList',
                    ]);

                    Route::get('/create/{id}', [
                        'as' => 'campus.study_room.comments.create',
                        'uses' => 'StudyRoomCommentsController@getCreate',
                    ]);

                    Route::post('/create/{id}', [
                        'as' => 'campus.study_room.comments.create',
                        'uses' => 'StudyRoomCommentsController@postCreate',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'campus.study_room.comments.delete',
                        'uses' => 'StudyRoomCommentsController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'campus.study_room.comments.delete.many',
                        'uses' => 'StudyRoomCommentsController@postDeleteMany',
                        'permission' => 'campus.study_room.comments.delete',
                    ]);
                });

            });

            Route::group(['namespace' => 'Genealogy','prefix' => 'genealogy'], function () {
                Route::get('/', [
                    'as' => 'campus.genealogy.list',
                    'uses' => 'GenealogyController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'campus.genealogy.create',
                    'uses' => 'GenealogyController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'campus.genealogy.create',
                    'uses' => 'GenealogyController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'campus.genealogy.edit',
                    'uses' => 'GenealogyController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'campus.genealogy.edit',
                    'uses' => 'GenealogyController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'campus.genealogy.delete',
                    'uses' => 'GenealogyController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'campus.genealogy.delete.many',
                    'uses' => 'GenealogyController@postDeleteMany',
                    'permission' => 'campus.genealogy.delete',
                ]);

                // Comments
                Route::group(['prefix' => 'comments'], function () {

                    Route::get('/{id}', [
                        'as' => 'campus.genealogy.comments.list',
                        'uses' => 'GenealogyCommentsController@getList',
                    ]);

                    Route::get('/create/{id}', [
                        'as' => 'campus.genealogy.comments.create',
                        'uses' => 'GenealogyCommentsController@getCreate',
                    ]);

                    Route::post('/create/{id}', [
                        'as' => 'campus.genealogy.comments.create',
                        'uses' => 'GenealogyCommentsController@postCreate',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'campus.genealogy.comments.delete',
                        'uses' => 'GenealogyCommentsController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'campus.genealogy.comments.delete.many',
                        'uses' => 'GenealogyCommentsController@postDeleteMany',
                        'permission' => 'campus.genealogy.comments.delete',
                    ]);
                });

            });
            Route::group(['namespace' => 'OldGenealogy','prefix' => 'old-genealogy'], function () {
                Route::get('/', [
                    'as' => 'campus.old.genealogy.list',
                    'uses' => 'OldGenealogyController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'campus.old.genealogy.create',
                    'uses' => 'OldGenealogyController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'campus.old.genealogy.create',
                    'uses' => 'OldGenealogyController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'campus.old.genealogy.edit',
                    'uses' => 'OldGenealogyController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'campus.old.genealogy.edit',
                    'uses' => 'OldGenealogyController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'campus.old.genealogy.delete',
                    'uses' => 'OldGenealogyController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'campus.old.genealogy.delete.many',
                    'uses' => 'OldGenealogyController@postDeleteMany',
                    'permission' => 'campus.old.genealogy.delete',
                ]);

                // Comments
                Route::group(['prefix' => 'comments'], function () {

                    Route::get('/{id}', [
                        'as' => 'campus.old.genealogy.comments.list',
                        'uses' => 'OldGenealogyCommentsController@getList',
                    ]);

                    Route::get('/create/{id}', [
                        'as' => 'campus.old.genealogy.comments.create',
                        'uses' => 'OldGenealogyCommentsController@getCreate',
                    ]);

                    Route::post('/create/{id}', [
                        'as' => 'campus.old.genealogy.comments.create',
                        'uses' => 'OldGenealogyCommentsController@postCreate',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'campus.old.genealogy.comments.delete',
                        'uses' => 'OldGenealogyCommentsController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'campus.old.genealogy.comments.delete.many',
                        'uses' => 'OldGenealogyCommentsController@postDeleteMany',
                        'permission' => 'campus.old.genealogy.comments.delete',
                    ]);
                });

            });
            //Evaluation
            Route::group(['namespace' => 'Evaluation','prefix' => 'evaluation'], function () {
                Route::get('/', [
                    'as' => 'campus.evaluation.list',
                    'uses' => 'EvaluationController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'campus.evaluation.create',
                    'uses' => 'EvaluationController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'campus.evaluation.create',
                    'uses' => 'EvaluationController@postCreate',
                ]);

                Route::get('/import', [
                    'as' => 'campus.evaluation.import',
                    'uses' => 'EvaluationController@getImport',
                ]);

                Route::post('/import', [
                    'as' => 'campus.evaluation.import',
                    'uses' => 'EvaluationController@postImport',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'campus.evaluation.edit',
                    'uses' => 'EvaluationController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'campus.evaluation.edit',
                    'uses' => 'EvaluationController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'campus.evaluation.delete',
                    'uses' => 'EvaluationController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'campus.evaluation.delete.many',
                    'uses' => 'EvaluationController@postDeleteMany',
                    'permission' => 'campus.evaluation.delete',
                ]);
                // Major
                Route::group(['prefix' => 'major'], function () {
                    Route::get('/', [
                        'as' => 'campus.evaluation.major.list',
                        'uses' => 'MajorController@getList',
                    ]);

                    Route::get('/create', [
                        'as' => 'campus.evaluation.major.create',
                        'uses' => 'MajorController@getCreate',
                    ]);

                    Route::post('/create', [
                        'as' => 'campus.evaluation.major.create',
                        'uses' => 'MajorController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'campus.evaluation.major.edit',
                        'uses' => 'MajorController@getEdit',
                    ]);

                    Route::post('/edit/{id}', [
                        'as' => 'campus.evaluation.major.edit',
                        'uses' => 'MajorController@postEdit',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'campus.evaluation.major.delete',
                        'uses' => 'MajorController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'campus.evaluation.major.delete.many',
                        'uses' => 'MajorController@postDeleteMany',
                        'permission' => 'campus.evaluation.major.delete',
                    ]);
                });


                // Comments
                Route::group(['prefix' => 'comments'], function () {

                    Route::get('/{id}', [
                        'as' => 'campus.evaluation.comments.list',
                        'uses' => 'CommentsEvaluationController@getList',
                    ]);

                    Route::get('/create/{id}', [
                        'as' => 'campus.evaluation.comments.create',
                        'uses' => 'CommentsEvaluationController@getCreate',
                    ]);

                    Route::post('/create/{id}', [
                        'as' => 'campus.evaluation.comments.create',
                        'uses' => 'CommentsEvaluationController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'campus.evaluation.comments.edit',
                        'uses' => 'CommentsEvaluationController@getEdit',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'campus.evaluation.comments.delete',
                        'uses' => 'CommentsEvaluationController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'campus.evaluation.comments.delete.many',
                        'uses' => 'CommentsEvaluationController@postDeleteMany',
                        'permission' => 'campus.evaluation.comments.delete',
                    ]);
                });

            });

            Route::group(['namespace' => 'Schedule','prefix' => 'schedule'], function () {
                Route::get('/', [
                    'as' => 'campus.schedule.list',
                    'uses' => 'ScheduleController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'campus.schedule.create',
                    'uses' => 'ScheduleController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'campus.schedule.create',
                    'uses' => 'ScheduleController@postCreate',
                ]);

                Route::get('/import', [
                    'as' => 'campus.schedule.import',
                    'uses' => 'ScheduleController@getImport',
                ]);

                Route::post('/import', [
                    'as' => 'campus.schedule.import',
                    'uses' => 'ScheduleController@postImport',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'campus.schedule.edit',
                    'uses' => 'ScheduleController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'campus.schedule.edit',
                    'uses' => 'ScheduleController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'campus.schedule.delete',
                    'uses' => 'ScheduleController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'campus.schedule.delete.many',
                    'uses' => 'ScheduleController@postDeleteMany',
                    'permission' => 'campus.schedule.delete',
                ]);

                 // Schedule Time
                 Route::group(['prefix' => 'time'], function () {
                    Route::get('/', [
                        'as' => 'campus.schedule.time.list',
                        'uses' => 'ScheduleTimeController@getList',
                    ]);

                    Route::get('/create', [
                        'as' => 'campus.schedule.time.create',
                        'uses' => 'ScheduleTimeController@getCreate',
                    ]);

                    Route::post('/create', [
                        'as' => 'campus.schedule.time.create',
                        'uses' => 'ScheduleTimeController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'campus.schedule.time.edit',
                        'uses' => 'ScheduleTimeController@getEdit',
                    ]);

                    Route::post('/edit/{id}', [
                        'as' => 'campus.schedule.time.edit',
                        'uses' => 'ScheduleTimeController@postEdit',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'campus.schedule.time.delete',
                        'uses' => 'ScheduleTimeController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'campus.schedule.time.delete.many',
                        'uses' => 'ScheduleTimeController@postDeleteMany',
                        'permission' => 'campus.schedule.time.delete',
                    ]);
                });

                 // Schedule Day
                 Route::group(['prefix' => 'day'], function () {
                    Route::get('/', [
                        'as' => 'campus.schedule.day.list',
                        'uses' => 'ScheduleDayController@getList',
                    ]);

                    Route::get('/create', [
                        'as' => 'campus.schedule.day.create',
                        'uses' => 'ScheduleDayController@getCreate',
                    ]);

                    Route::post('/create', [
                        'as' => 'campus.schedule.day.create',
                        'uses' => 'ScheduleDayController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'campus.schedule.day.edit',
                        'uses' => 'ScheduleDayController@getEdit',
                    ]);

                    Route::post('/edit/{id}', [
                        'as' => 'campus.schedule.day.edit',
                        'uses' => 'ScheduleDayController@postEdit',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'campus.schedule.day.delete',
                        'uses' => 'ScheduleDayController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'campus.schedule.day.delete.many',
                        'uses' => 'ScheduleDayController@postDeleteMany',
                        'permission' => 'campus.schedule.day.delete',
                    ]);
                });

                 // Schedule TimeLine
                 Route::group(['prefix' => 'timeline'], function () {
                    Route::get('{id}', [
                        'as' => 'campus.schedule.timeline.list',
                        'uses' => 'ScheduleTimeLineController@getList',
                    ]);

                    Route::get('/create/{id}', [
                        'as' => 'campus.schedule.timeline.create',
                        'uses' => 'ScheduleTimeLineController@getCreate',
                    ]);

                    Route::post('/create/{id}', [
                        'as' => 'campus.schedule.timeline.create',
                        'uses' => 'ScheduleTimeLineController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'campus.schedule.timeline.edit',
                        'uses' => 'ScheduleTimeLineController@getEdit',
                    ]);

                    Route::post('/edit/{id}', [
                        'as' => 'campus.schedule.timeline.edit',
                        'uses' => 'ScheduleTimeLineController@postEdit',
                    ]);

                    Route::get('/delete/{id}', [
                        'as' => 'campus.schedule.timeline.delete',
                        'uses' => 'ScheduleTimeLineController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'campus.schedule.timeline.delete.many',
                        'uses' => 'ScheduleTimeLineController@postDeleteMany',
                        'permission' => 'campus.schedule.timeline.delete',
                    ]);
                });
                // Schedule Filter
                Route::group(['prefix' => 'filter'], function () {
                    Route::get('/', [
                        'as' => 'campus.schedule.filter.list',
                        'uses' => 'ScheduleFilterController@getList',
                    ]);

                    Route::get('/create', [
                        'as' => 'campus.schedule.filter.create',
                        'uses' => 'ScheduleFilterController@getCreate',
                    ]);

                    Route::post('/create', [
                        'as' => 'campus.schedule.filter.create',
                        'uses' => 'ScheduleFilterController@postCreate',
                    ]);

                    Route::get('/edit/{id}', [
                        'as' => 'campus.schedule.filter.edit',
                        'uses' => 'ScheduleFilterController@getEdit',
                    ]);

                    Route::post('/edit/{id}', [
                        'as' => 'campus.schedule.filter.edit',
                        'uses' => 'ScheduleFilterController@postEdit',
                    ]);

                    Route::get('/delete', [
                        'as' => 'campus.schedule.filter.delete',
                        'uses' => 'ScheduleFilterController@getDelete',
                    ]);

                    Route::post('/delete-many', [
                        'as' => 'campus.schedule.filter.delete.many',
                        'uses' => 'ScheduleFilterController@postDeleteMany',
                        'permission' => 'campus.schedule.filter.delete',
                    ]);
                });

            });

            //Notices
            Route::group(['namespace' => 'Notices','prefix' => 'notices'], function () {
                Route::get('/', [
                    'as' => 'campus.notices.list',
                    'uses' => 'NoticesCampusController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'campus.notices.create',
                    'uses' => 'NoticesCampusController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'campus.notices.create',
                    'uses' => 'NoticesCampusController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'campus.notices.edit',
                    'uses' => 'NoticesCampusController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'campus.notices.edit',
                    'uses' => 'NoticesCampusController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'campus.notices.delete',
                    'uses' => 'NoticesCampusController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'campus.notices.delete.many',
                    'uses' => 'NoticesCampusController@postDeleteMany',
                    'permission' => 'campus.notices.delete',
                ]);

            });

             //Description
             Route::group(['namespace' => 'Description','prefix' => 'description'], function () {
                Route::get('/', [
                    'as' => 'campus.description.list',
                    'uses' => 'DescriptionCampusController@getList',
                ]);

                Route::get('/create', [
                    'as' => 'campus.description.create',
                    'uses' => 'DescriptionCampusController@getCreate',
                ]);

                Route::post('/create', [
                    'as' => 'campus.description.create',
                    'uses' => 'DescriptionCampusController@postCreate',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'campus.description.edit',
                    'uses' => 'DescriptionCampusController@getEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'campus.description.edit',
                    'uses' => 'DescriptionCampusController@postEdit',
                ]);

                Route::get('/delete/{id}', [
                    'as' => 'campus.description.delete',
                    'uses' => 'DescriptionCampusController@getDelete',
                ]);

                Route::post('/delete-many', [
                    'as' => 'campus.description.delete.many',
                    'uses' => 'DescriptionCampusController@postDeleteMany',
                    'permission' => 'campus.description.delete',
                ]);

            });


        });
    });

});
