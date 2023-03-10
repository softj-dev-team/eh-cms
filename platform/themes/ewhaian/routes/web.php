<?php

Route::group(['namespace' => 'Theme\Ewhaian\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get('/', 'EwhaianController@index')->name('home.index');

        Route::post('update/clicker-left-banner', 'EwhaianController@clicker')->name('home.clicker');
        Route::post('download-attachiamenta', 'EwhaianController@getDownload')->name('download.attachiamenta');

        Route::get('contents/contents-list/{idCategory}/search', 'SearchController@contents')->name('contents.search');
        Route::get('master-room/list/{idCategory}/search', 'SearchController@masterRoom')->name('masterRoom.search');
        Route::get('new-contents/list/{idCategory}/search', 'SearchController@newContents')->name('newContents.search');
        Route::get('master-room/address/search', 'SearchController@address')->name('masterRoom.address.search');
        Route::get('event/comments/list/search', 'SearchController@events')->name('events.search');

        Route::get('life/flea-market-list/search', 'SearchController@flare')->name('flare.search');
        Route::get('life/part-time-jobs-list/search', 'SearchController@jobs')->name('jobs.search');
        Route::get('life/advertisements-list/search', 'SearchController@ads')->name('ads.search');
        Route::get('life/shelter-info/search', 'SearchController@shelter')->name('shelter.search');
        Route::get('life/open-space/search', 'SearchController@openSpace')->name('openSpace.search');

        Route::get('campus/study-room/search', 'SearchController@study')->name('study.search');
        Route::get('campus/eh-genealogy/search', 'SearchController@genealogy')->name('genealogy.search');

        Route::get('campus/lecture-evaluation/search', 'SearchController@evaluation')->name('evaluation.search');
        Route::get('campus/lecture-evaluation/major/search', 'SearchController@major')->name('evaluation.major.search');

        Route::get('garden/{idCategory}/search', 'SearchController@garden')->name('garden.search');
        Route::get('garden/{idCategory}/filter', 'SearchController@filterGarden')->name('garden.filter');
        Route::get('egarden/room/search', 'SearchController@room')->name('room.search');
        Route::get('egarden/room/{id}/search', 'SearchController@egarden')->name('egarden.search');
        // Route::get('egarden/search', 'SearchController@egarden')->name('egarden.search');
        Route::get('egarden/ajax/room/search', 'SearchController@roomAjax')->name('room.ajax.search');

        Route::get('eh-introduction/notices/search', 'SearchController@noticesIntro')->name('eh_introduction.notices.search');
        Route::get('eh-introduction/faq/search', 'SearchController@faq')->name('eh_introduction.faq.search');

        Route::get('event/event-list/{idCategory}', 'EventsController@index')->name('event.event_list');
        Route::get('event/event-details/{idCategory}/{id}', 'EventsController@show')->name('event.details');
        Route::post('event/comments/create', 'EventsController@createComment')->name('event.comment.create');
        Route::post('event/comments/delete/{id}', 'EventsController@deleteComment')->name('event.comment.delete');

        Route::get('event/comments/list', 'EventsController@listComment')->name('event.cmt.list');
        Route::get('event/comments/detail/{id}', 'EventsController@detailComments')->name('event.cmt.detail');
        Route::post('event/comments/createComments', 'EventsController@createEventsCmtComment')->name('event.cmt.comment.create');
        Route::post('event/comments/deleteComments/{id}', 'EventsController@deleteEventsCmtComment')->name('event.cmt.comment.delete');

        Route::get('contents/contents-list/{idCategory}', 'ContentsController@index')->name('contents.contents_list');
        Route::get('contents/contents-notices/{idCategory}/{id}', 'ContentsController@detailNotice')->name('contents.contents.notices.detail');
        Route::get('contents/contents-list-sub/{idCategory}', 'ContentsController@listToDetail')->name('contents.contents_list.to.detail');
        Route::get('contents/contents-details/{idCategory}/{id}', 'ContentsController@show')->name('contents.details');
        Route::post('contents/comments/create', 'ContentsController@createComment')->name('contents.comment.create');
        Route::post('contents/comments/delete/{id}', 'ContentsController@deleteComment')->name('contents.comment.delete');

        Route::get('life/flea-market-list', 'FlareMarketController@index')->name('life.flare_market_list');
        Route::get('life/part-time-jobs-list/notices/{id}', 'JobsPartTimeController@detailNotice')->name('life.part_time_jobs.notices.detail');
        Route::get('life/flea-market-list/notices/{id}', 'FlareMarketController@detailNotice')->name('life.flare_market.notices.detail');
        Route::get('life/flea-market-details/{id}', 'FlareMarketController@show')->name('life.flare_market_details');
        Route::post('life/flea-market-details-comments/create', 'FlareMarketController@createComment')->name('life.flare_market_details_comments.create');
        Route::post('life/flea-market-details-comments/delete/{id}', 'FlareMarketController@deleteComment')->name('life.flare_market_details_comments.delete');
        Route::post('life/flea-market-details-dislike', 'FlareMarketController@dislike')->name('life.flea-market-details.dislike');
        Route::post('life/flea-market-details-like', 'FlareMarketController@like')->name('life.flea-market-details.like');

        Route::get('life/part-time-jobs-list', 'JobsPartTimeController@index')->name('life.part_time_jobs_list');
        Route::get('life/part-time-jobs-list/notices/{id}', 'JobsPartTimeController@detailNotice')->name('life.part_time_jobs.notices.detail');
        Route::get('life/part-time-jobs-details/{id}', 'JobsPartTimeController@show')->name('life.part_time_jobs_details');
        Route::post('life/part-time-jobs-details-comments/create', 'JobsPartTimeController@createComment')->name('life.part_time_jobs_details_comments.create');
        Route::post('life/part-time-jobs-details-comments/delete{id}', 'JobsPartTimeController@deleteComment')->name('life.part_time_jobs_details_comments.delete');
        Route::post('life/part-time-jobs-details-dislike', 'JobsPartTimeController@dislike')->name('life.part-time-jobs-details.dislike');
        Route::post('life/part-time-jobs-details-like', 'JobsPartTimeController@like')->name('life.part-time-jobs-details.like');

        Route::get('life/advertisements-list', 'AdsController@index')->name('life.advertisements_list');
        Route::get('life/advertisements-list/notices/{id}', 'AdsController@detailNotice')->name('life.advertisements.notices.detail');
        Route::get('life/advertisements-details/{id}', 'AdsController@show')->name('life.advertisements_details');
        Route::post('life/advertisements-details-comments/create', 'AdsController@createComment')->name('life.advertisements_details_comments.create');
        Route::post('life/advertisements-details-comments/delete/{id}', 'AdsController@deleteComment')->name('life.advertisements_details_comments.delete');
        Route::post('life/advertisements-details-download', 'AdsController@getDownload')->name('life.advertisements_details_download');
        Route::post('life/advertisements-details-dislike', 'AdsController@dislike')->name('life.advertisements_details.dislike');
        Route::post('life/advertisements-details-like', 'AdsController@like')->name('life.advertisements_details.like');

        Route::get('life/shelter-info', 'ShelterController@index')->name('life.shelter_list');
        Route::get('life/shelter-info/notices/{id}', 'ShelterController@detailNotice')->name('life.shelter.notice.detail');
        Route::get('life/shelter-details/{id}', 'ShelterController@show')->name('life.shelter_list_details');
        Route::post('life/shelter-details-comments/create', 'ShelterController@createComment')->name('life.shelter_details_comments.create');
        Route::post('life/shelter-details-comments/delete/{id}', 'ShelterController@deleteComment')->name('life.shelter_details_comments.delete');
        Route::post('life/shelter-details-dislike', 'ShelterController@dislike')->name('life.shelter-details.dislike');
        Route::post('life/shelter-details-like', 'ShelterController@like')->name('life.shelter-details.like');

        Route::get('life/open-space', 'OpenSpaceController@index')->name('life.open_space_list');
        Route::get('life/open-space/notices/{id}', 'OpenSpaceController@detailNotice')->name('life.open_space.notices.detail');
        Route::get('life/open-space-details/{id}', 'OpenSpaceController@show')->name('life.open_space_details');
        Route::post('life/open-space-details-comments/create', 'OpenSpaceController@createComment')->name('life.open_space_comments.create');
        Route::post('life/open-space-details-comments/delete/{id}', 'OpenSpaceController@deleteComment')->name('life.open_space_comments.delete');
        Route::post('life/open-space-details-dislike', 'OpenSpaceController@dislike')->name('life.open_space_details.dislike');
        Route::post('life/open-space-details-like', 'OpenSpaceController@like')->name('life.open_space_details.like');

        Route::get('campus/study-room', 'StudyRoomController@index')->name('campus.study_room_list');
        Route::get('campus/study-room/notices/{id}', 'StudyRoomController@detailNotice')->name('campus.study_room.notices.detail');
        Route::get('campus/study-room-details/{id}', 'StudyRoomController@show')->name('campus.study_room_details');
        Route::post('campus/study-room-details-comments/create', 'StudyRoomController@createComment')->name('campus.study_room_details_comments.create');
        Route::post('campus/study-room-details-comments/delete/{id}', 'StudyRoomController@deleteComment')->name('campus.study_room_details_comments.delete');

        Route::get('campus/eh-genealogy', 'GenealogyController@index')->name('campus.genealogy_list');
        Route::get('campus/eh-genealogy/notices/{id}', 'GenealogyController@detailNotice')->name('campus.genealogy.notices.detail');
        Route::get('campus/eh-genealogy-details/{id}', 'GenealogyController@show')->name('campus.genealogy_details');
        Route::post('campus/eh-genealogy-details-comments/create', 'GenealogyController@createComment')->name('campus.genealogy_details_comments.create');
        Route::post('campus/eh-genealogy-details-comments/delete/{id}', 'GenealogyController@deleteComment')->name('campus.genealogy_details_comments.delete');
        Route::post('life/eh-genealogy-details-download', 'GenealogyController@getDownload')->name('life.genealogy_details_download');

        Route::get('campus/lecture-evaluation', 'EvaluationController@index')->name('campus.evaluation_list');
        Route::get('campus/lecture-evaluation/notices/{id}', 'EvaluationController@detailNotice')->name('campus.evaluation.notices.detail');
        Route::get('campus/lecture-evaluation/{id}', 'EvaluationController@show')->name('campus.evaluation_details');
        Route::post('campus/lecture-evaluation/comments/{id}', 'EvaluationController@createComment')->name('campus.evaluation_comments');
        Route::get('campus/lecture-evaluation/comments/lastest/{id?}', 'EvaluationController@lastest')->name('campus.evaluation_comments_lastest');
        Route::get('campus/lecture-evaluation/comments/major', 'EvaluationController@major')->name('campus.evaluation_comments_major');


        Route::get('garden/passwd', 'GardenController@getPasswd')->name('gardenFE.passwd');
        Route::post('garden/clicker-left-banner', 'GardenController@clicker')->name('gardenFE.clicker');

        Route::get('eh-introduction/notices', 'NoticesIntroController@index')->name('eh_introduction.notices.list');
        Route::get('eh-introduction/notices/{id}', 'NoticesIntroController@detail')->name('eh_introduction.notices.detail');

        Route::get('eh-introduction/list', 'IntroController@index')->name('eh_introduction.list');
        Route::get('eh-introduction/{id}', 'IntroController@show')->name('eh_introduction.detail');

        Route::get('eh-introduction/faq/list', 'FaqController@index')->name('eh_introduction.faq');

        Route::post('ewhaian/report', 'EwhaianController@postReport')->name('ewhaian.report');
        Route::get('ewhaian/delete_comment/{id}', 'EwhaianController@deleteComment')->name('ewhaian.delete_comment');
        Route::post('ewhaian/passwd', 'EwhaianController@postPasswd')->name('ewhaian.passwd');

        Route::group(['prefix' => 'events-fe', 'middleware' => 'member'], function () {
            Route::get('/list', [
                'as' => 'eventsFE.list', // it will check permission with flag is eventsFE.list
                'uses' => 'EventsController@getList',
            ]);
            Route::get('/create/{idCategory}', [
                'as' => 'eventsFE.create', // it will check permission with flag is eventsFE.create
                'uses' => 'EventsController@getCreate',
            ]);

            Route::post('/create/{idCategory}', [
                'as' => 'eventsFE.create', // it will check permission with flag is eventsFE.create
                'uses' => 'EventsController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'eventsFE.edit', // it will check permission with flag is eventsFE.edit
                'uses' => 'EventsController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'eventsFE.edit', // it will check permission with flag is eventsFE.edit
                'uses' => 'EventsController@postUpdate',
            ]);

            Route::post('delete/', [
                'as' => 'eventsFE.delete', // it will check permission with flag is eventsFE.delete
                'uses' => 'EventsController@delete',
            ]);

            Route::post('/preview', [
                'as' => 'eventsFE.preview', // it will check permission with flag is eventsFE.list
                'uses' => 'EventsController@preview',
                'permission' => 'eventsFE.list',
            ]);

            Route::post('/checkSympathyPermissionOnPost/{id}', [
                'as' => 'eventsFE.checkSympathyPermissionOnPost',
                'uses' => 'EventsController@checkSympathyPermissionOnPost',
                'permission' => 'eventsFE.list', // it will check permission with flag is eventsFE.list
            ]);

            Route::post('/ajax/like/{id}', [
                'as' => 'eventsFE.likePost',
                'uses' => 'EventsController@likePost',
                'permission' => 'eventsFE.list', // it will check permission with flag is contentsFE.list
            ]);
            Route::post('/ajax/dislike/{id}', [
                'as' => 'eventsFE.dislikePost',
                'uses' => 'EventsController@dislikePost',
                'permission' => 'eventsFE.list', // it will check permission with flag is contentsFE.list
            ]);

            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'eventsFE.checkSympathyPermissionOnComment', // it will check permission with flag is eventsFE.list
                'uses' => 'EventsController@checkSympathyPermissionOnComment',
                'permission' => 'eventsFE.list',
            ]);

            Route::post('/like', [
                'as' => 'eventsFE.like', // it will check permission with flag is eventsFE.list
                'uses' => 'EventsController@like',
                'permission' => 'eventsFE.list',
            ]);
            Route::post('/dislike', [
                'as' => 'eventsFE.dislike', // it will check permission with flag is eventsFE.list
                'uses' => 'EventsController@dislike',
                'permission' => 'eventsFE.list',
            ]);

            Route::group(['prefix' => 'comments', 'middleware' => 'member'], function () {
                Route::get('/list', [
                    'as' => 'eventsFE.cmt.list', // it will check permission with flag is eventsFE.cmt.list
                    'uses' => 'EventsController@getEventCmtList',
                ]);
                Route::get('/create', [
                    'as' => 'eventsFE.cmt.create', // it will check permission with flag is eventsFE.cmt.create
                    'uses' => 'EventsController@getEventCmtCreate',
                ]);

                Route::post('/create', [
                    'as' => 'eventsFE.cmt.create', // it will check permission with flag is eventsFE.cmt.create
                    'uses' => 'EventsController@postEventCmtStore',
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'eventsFE.cmt.edit', // it will check permission with flag is eventsFE.cmt.edit
                    'uses' => 'EventsController@getEventCmtEdit',
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'eventsFE.cmt.edit', // it will check permission with flag is eventsFE.cmt.edit
                    'uses' => 'EventsController@postEventCmtUpdate',
                ]);

                Route::post('delete/', [
                    'as' => 'eventsFE.cmt.delete', // it will check permission with flag is eventsFE.cmt.delete
                    'uses' => 'EventsController@deleteEventCmt',
                ]);

                Route::post('/preview', [
                    'as' => 'eventsFE.cmt.preview', // it will check permission with flag is gardenFE.list
                    'uses' => 'EventsController@previewEventCmt',
                    'permission' => 'eventsFE.cmt.list',
                ]);


                Route::post('/checkSympathyPermissionOnEventComment', [
                    'as' => 'eventsFE.cmt.checkSympathyPermissionOnEventComment', // it will check permission with flag is eventsFE.list
                    'uses' => 'EventsController@checkSympathyPermissionOnEventComment',
                    'permission' => 'eventsFE.list',
                ]);

                Route::post('/like', [
                    'as' => 'eventsFE.cmt.like', // it will check permission with flag is eventsFE.list
                    'uses' => 'EventsController@likeEventCmt',
                    'permission' => 'eventsFE.list',
                ]);
                Route::post('/dislike', [
                    'as' => 'eventsFE.cmt.dislike', // it will check permission with flag is eventsFE.list
                    'uses' => 'EventsController@dislikeEventCmt',
                    'permission' => 'eventsFE.list',
                ]);
            });
        });

        Route::group(['prefix' => 'contents-fe', 'middleware' => 'member'], function () {
            Route::get('/list', [
                'as' => 'contentsFE.list', // it will check permission with flag is contentsFE.list
                'uses' => 'ContentsController@getList',
            ]);
            Route::get('/create/{idCategory}', [
                'as' => 'contentsFE.create', // it will check permission with flag is contentsFE.create
                'uses' => 'ContentsController@getCreate',
            ]);

            Route::post('/create/{idCategory}', [
                'as' => 'contentsFE.create', // it will check permission with flag is contentsFE.create
                'uses' => 'ContentsController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'contentsFE.edit', // it will check permission with flag is contentsFE.edit
                'uses' => 'ContentsController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'contentsFE.edit', // it will check permission with flag is contentsFE.edit
                'uses' => 'ContentsController@postUpdate',
            ]);

            Route::post('delete/', [
                'as' => 'contentsFE.delete', // it will check permission with flag is contentsFE.delete
                'uses' => 'ContentsController@delete',
            ]);

            Route::post('preview/', [
                'as' => 'contentsFE.preview', // it will check permission with flag is contentsFE.delete
                'uses' => 'ContentsController@preview',
                'permission' => 'contentsFE.list'
            ]);

            Route::post('/checkSympathyPermissionOnPost/{id}', [
                'as' => 'contentsFE.checkSympathyPermissionOnPost',
                'uses' => 'ContentsController@checkSympathyPermissionOnPost',
                'permission' => 'contentsFE.list', // it will check permission with flag is contentsFE.list
            ]);
            Route::post('/ajax/like/{id}', [
                'as' => 'contentsFE.like',
                'uses' => 'ContentsController@like',
                'permission' => 'contentsFE.list', // it will check permission with flag is contentsFE.list
            ]);
            Route::post('/ajax/dislike/{id}', [
                'as' => 'contentsFE.dislike',
                'uses' => 'ContentsController@dislike',
                'permission' => 'contentsFE.list', // it will check permission with flag is contentsFE.list
            ]);


            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'contentsFE.checkSympathyPermissionOnComment', // it will check permission with flag is contentsFE.list
                'uses' => 'ContentsController@checkSympathyPermissionOnComment',
                'permission' => 'contentsFE.list',
            ]);

            Route::post('/like', [
                'as' => 'contentsFE.comment.like', // it will check permission with flag is contentsFE.list
                'uses' => 'ContentsController@likeComments',
                'permission' => 'contentsFE.list',
            ]);
            Route::post('/dislike', [
                'as' => 'contentsFE.comment.dislike', // it will check permission with flag is contentsFE.list
                'uses' => 'ContentsController@dislikeComments',
                'permission' => 'contentsFE.list',
            ]);

        });

        Route::group(['prefix' => 'life/flea-market-fe', 'middleware' => 'member'], function () {
            Route::get('/list', [
                'as' => 'flareMarketFE.list', // it will check permission with flag is flareMarketFE.list
                'uses' => 'FlareMarketController@getList',
            ]);
            Route::get('/create/{categoryId}', [
                'as' => 'flareMarketFE.create', // it will check permission with flag is flareMarketFE.create
                'uses' => 'FlareMarketController@getCreate',
            ]);

            Route::post('/create/{categoryId}', [
                'as' => 'flareMarketFE.create', // it will check permission with flag is flareMarketFE.create
                'uses' => 'FlareMarketController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'flareMarketFE.edit', // it will check permission with flag is flareMarketFE.edit
                'uses' => 'FlareMarketController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'flareMarketFE.edit', // it will check permission with flag is flareMarketFE.edit
                'uses' => 'FlareMarketController@postUpdate',
            ]);
            Route::post('delete/', [
                'as' => 'flareMarketFE.delete', // it will check permission with flag is flareMarketFE.delete
                'uses' => 'FlareMarketController@delete',
            ]);

            Route::post('/preview', [
                'as' => 'flareMarketFE.preview', // it will check permission with flag is flareMarketFE.list
                'uses' => 'FlareMarketController@preview',
                'permission' => 'flareMarketFE.list',
            ]);



            Route::post('/checkSympathyPermissionOnPost/{id}', [
                'as' => 'flareMarketFE.checkSympathyPermissionOnPost',
                'uses' => 'FlareMarketController@checkSympathyPermissionOnPost',
                'permission' => 'flareMarketFE.list', // it will check permission with flag is flareMarketFE.list
            ]);

            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'flareMarketFE.checkSympathyPermissionOnComment', // it will check permission with flag is flareMarketFE.list
                'uses' => 'FlareMarketController@checkSympathyPermissionOnComment',
                'permission' => 'flareMarketFE.list',
            ]);

            Route::post('/like', [
                'as' => 'flareMarketFE.likeComments', // it will check permission with flag is flareMarketFE.list
                'uses' => 'FlareMarketController@likeComments',
                'permission' => 'flareMarketFE.list',
            ]);
            Route::post('/dislike', [
                'as' => 'flareMarketFE.dislikeComments', // it will check permission with flag is flareMarketFE.list
                'uses' => 'FlareMarketController@dislikeComments',
                'permission' => 'flareMarketFE.list',
            ]);
        });

        Route::group(['prefix' => 'life/part-time-jobs', 'middleware' => 'member'], function () {
            Route::get('/list', [
                'as' => 'jobsPartTimeFE.list', // it will check permission with flag is jobsPartTimeFE.list
                'uses' => 'JobsPartTimeController@getList',
            ]);
            Route::get('/create/{categoryId}', [
                'as' => 'jobsPartTimeFE.create', // it will check permission with flag is jobsPartTimeFE.create
                'uses' => 'JobsPartTimeController@getCreate',
            ]);

            Route::post('/create/{categoryId}', [
                'as' => 'jobsPartTimeFE.create', // it will check permission with flag is jobsPartTimeFE.create
                'uses' => 'JobsPartTimeController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'jobsPartTimeFE.edit', // it will check permission with flag is jobsPartTimeFE.edit
                'uses' => 'JobsPartTimeController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'jobsPartTimeFE.edit', // it will check permission with flag is jobsPartTimeFE.edit
                'uses' => 'JobsPartTimeController@postUpdate',
            ]);
            Route::post('delete/', [
                'as' => 'jobsPartTimeFE.delete', // it will check permission with flag is jobsPartTimeFE.edit
                'uses' => 'JobsPartTimeController@delete',
            ]);

            Route::post('/preview', [
                'as' => 'jobsPartTimeFE.preview', // it will check permission with flag is adsFE.list
                'uses' => 'JobsPartTimeController@preview',
                'permission' => 'jobsPartTimeFE.list',
            ]);


            Route::post('/checkSympathyPermissionOnPost/{id}', [
                'as' => 'jobsPartTimeFE.checkSympathyPermissionOnPost',
                'uses' => 'JobsPartTimeController@checkSympathyPermissionOnPost',
                'permission' => 'jobsPartTimeFE.list', // it will check permission with flag is jobsPartTimeFE.list
            ]);

            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'jobsPartTimeFE.checkSympathyPermissionOnComment', // it will check permission with flag is jobsPartTimeFE.list
                'uses' => 'JobsPartTimeController@checkSympathyPermissionOnComment',
                'permission' => 'jobsPartTimeFE.list',
            ]);

            Route::post('/like', [
                'as' => 'jobsPartTimeFE.likeComments', // it will check permission with flag is jobsPartTimeFE.list
                'uses' => 'JobsPartTimeController@likeComments',
                'permission' => 'jobsPartTimeFE.list',
            ]);
            Route::post('/dislike', [
                'as' => 'jobsPartTimeFE.dislikeComments', // it will check permission with flag is jobsPartTimeFE.list
                'uses' => 'JobsPartTimeController@dislikeComments',
                'permission' => 'jobsPartTimeFE.list',
            ]);
        });

        Route::group(['prefix' => 'life/advertisements-enroll', 'middleware' => 'member'], function () {
            Route::get('/list', [
                'as' => 'adsFE.list', // it will check permission with flag is adsFE.list
                'uses' => 'AdsController@getList',
            ]);
            Route::get('/create', [
                'as' => 'adsFE.create', // it will check permission with flag is adsFE.create
                'uses' => 'AdsController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'adsFE.create', // it will check permission with flag is adsFE.create
                'uses' => 'AdsController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'adsFE.edit', // it will check permission with flag is adsFE.edit
                'uses' => 'AdsController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'adsFE.edit', // it will check permission with flag is adsFE.edit
                'uses' => 'AdsController@postUpdate',
            ]);

            Route::post('delete/', [
                'as' => 'adsFE.delete', // it will check permission with flag is adsFE.edit
                'uses' => 'AdsController@delete',
            ]);

            Route::post('/preview', [
                'as' => 'adsFE.preview', // it will check permission with flag is adsFE.list
                'uses' => 'AdsController@preview',
                'permission' => 'adsFE.list',
            ]);


            Route::post('/checkSympathyPermissionOnPost/{id}', [
                'as' => 'adsFE.checkSympathyPermissionOnPost',
                'uses' => 'AdsController@checkSympathyPermissionOnPost',
                'permission' => 'adsFE.list', // it will check permission with flag is adsFE.list
            ]);

            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'adsFE.checkSympathyPermissionOnComment', // it will check permission with flag is adsFE.list
                'uses' => 'AdsController@checkSympathyPermissionOnComment',
                'permission' => 'adsFE.list',
            ]);

            Route::post('/like', [
                'as' => 'adsFE.likeComments', // it will check permission with flag is adsFE.list
                'uses' => 'AdsController@likeComments',
                'permission' => 'adsFE.list',
            ]);
            Route::post('/dislike', [
                'as' => 'adsFE.dislikeComments', // it will check permission with flag is adsFE.list
                'uses' => 'AdsController@dislikeComments',
                'permission' => 'adsFE.list',
            ]);
        });

        Route::group(['prefix' => 'life/shelter-info', 'middleware' => 'member'], function () {
            Route::get('/list', [
                'as' => 'shelterFE.list', // it will check permission with flag is adsFE.list
                'uses' => 'ShelterController@getList',
            ]);
            Route::get('/create', [
                'as' => 'shelterFE.create', // it will check permission with flag is shelterFE.create
                'uses' => 'ShelterController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'shelterFE.create', // it will check permission with flag is shelterFE.create
                'uses' => 'ShelterController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'shelterFE.edit', // it will check permission with flag is shelterFE.edit
                'uses' => 'ShelterController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'shelterFE.edit', // it will check permission with flag is shelterFE.edit
                'uses' => 'ShelterController@postUpdate',
            ]);

            Route::post('delete/', [
                'as' => 'shelterFE.delete', // it will check permission with flag is shelterFE.edit
                'uses' => 'ShelterController@delete',
            ]);

            Route::post('/preview', [
                'as' => 'shelterFE.preview', // it will check permission with flag is shelterFE.list
                'uses' => 'ShelterController@preview',
                'permission' => 'shelterFE.list',
            ]);

            Route::post('/checkSympathyPermissionOnPost/{id}', [
                'as' => 'shelterFE.checkSympathyPermissionOnPost',
                'uses' => 'ShelterController@checkSympathyPermissionOnPost',
                'permission' => 'shelterFE.list', // it will check permission with flag is shelterFE.list
            ]);

            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'shelterFE.checkSympathyPermissionOnComment', // it will check permission with flag is shelterFE.list
                'uses' => 'ShelterController@checkSympathyPermissionOnComment',
                'permission' => 'shelterFE.list',
            ]);

            Route::post('/like', [
                'as' => 'shelterFE.likeComments', // it will check permission with flag is shelterFE.list
                'uses' => 'ShelterController@likeComments',
                'permission' => 'shelterFE.list',
            ]);
            Route::post('/dislike', [
                'as' => 'shelterFE.dislikeComments', // it will check permission with flag is shelterFE.list
                'uses' => 'ShelterController@dislikeComments',
                'permission' => 'shelterFE.list',
            ]);

        });

        Route::group(['prefix' => 'life/open-space', 'middleware' => 'member'], function () {
            Route::get('/list', [
                'as' => 'openSpaceFE.list', // it will check permission with flag is openSpaceFE.list
                'uses' => 'OpenSpaceController@getList',
            ]);
            Route::get('/create', [
                'as' => 'openSpaceFE.create', // it will check permission with flag is openSpaceFE.create
                'uses' => 'OpenSpaceController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'openSpaceFE.create', // it will check permission with flag is openSpaceFE.create
                'uses' => 'OpenSpaceController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'openSpaceFE.edit', // it will check permission with flag is openSpaceFE.edit
                'uses' => 'OpenSpaceController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'openSpaceFE.edit', // it will check permission with flag is openSpaceFE.edit
                'uses' => 'OpenSpaceController@postUpdate',
            ]);

            Route::post('delete/', [
                'as' => 'openSpaceFE.delete', // it will check permission with flag is openSpaceFE.edit
                'uses' => 'OpenSpaceController@delete',
            ]);
            Route::post('preview/', [
                'as' => 'openSpaceFE.preview', // it will check permission with flag is openSpaceFE.edit
                'uses' => 'OpenSpaceController@preview',
                'permission' => 'openSpaceFE.list',
            ]);

            Route::post('/checkSympathyPermissionOnPost/{id}', [
                'as' => 'openSpaceFE.checkSympathyPermissionOnPost',
                'uses' => 'OpenSpaceController@checkSympathyPermissionOnPost',
                'permission' => 'openSpaceFE.list', // it will check permission with flag is openSpaceFE.list
            ]);

            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'openSpaceFE.checkSympathyPermissionOnComment', // it will check permission with flag is openSpaceFE.list
                'uses' => 'OpenSpaceController@checkSympathyPermissionOnComment',
                'permission' => 'openSpaceFE.list',
            ]);

            Route::post('/like', [
                'as' => 'openSpaceFE.likeComments', // it will check permission with flag is openSpaceFE.list
                'uses' => 'OpenSpaceController@likeComments',
                'permission' => 'openSpaceFE.list',
            ]);
            Route::post('/dislike', [
                'as' => 'openSpaceFE.dislikeComments', // it will check permission with flag is openSpaceFE.list
                'uses' => 'OpenSpaceController@dislikeComments',
                'permission' => 'openSpaceFE.list',
            ]);
        });

        Route::group(['prefix' => 'campus/study-room', 'middleware' => 'member'], function () {
            Route::get('/list', [
                'as' => 'studyRoomFE.list', // it will check permission with flag is studyRoomFE.list
                'uses' => 'StudyRoomController@getList',
            ]);
            Route::get('/create', [
                'as' => 'studyRoomFE.create', // it will check permission with flag is studyRoomFE.create
                'uses' => 'StudyRoomController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'studyRoomFE.create', // it will check permission with flag is studyRoomFE.create
                'uses' => 'StudyRoomController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'studyRoomFE.edit', // it will check permission with flag is studyRoomFE.edit
                'uses' => 'StudyRoomController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'studyRoomFE.edit', // it will check permission with flag is studyRoomFE.edit
                'uses' => 'StudyRoomController@postUpdate',
            ]);
            Route::post('delete/', [
                'as' => 'studyRoomFE.delete', // it will check permission with flag is studyRoomFE.delete
                'uses' => 'StudyRoomController@delete',
            ]);

            Route::post('/preview', [
                'as' => 'studyRoomFE.preview', // it will check permission with flag is studyRoomFE.list
                'uses' => 'StudyRoomController@preview',
                'permission' => 'studyRoomFE.list',
            ]);

            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'studyRoomFE.checkSympathyPermissionOnComment', // it will check permission with flag is studyRoomFE.list
                'uses' => 'StudyRoomController@checkSympathyPermissionOnComment',
                'permission' => 'studyRoomFE.list',
            ]);

            Route::post('/like', [
                'as' => 'studyRoomFE.likeComments', // it will check permission with flag is studyRoomFE.list
                'uses' => 'StudyRoomController@likeComments',
                'permission' => 'studyRoomFE.list',
            ]);
            Route::post('/dislike', [
                'as' => 'studyRoomFE.dislikeComments', // it will check permission with flag is studyRoomFE.list
                'uses' => 'StudyRoomController@dislikeComments',
                'permission' => 'studyRoomFE.list',
            ]);
        });

        Route::group(['prefix' => 'campus/eh-genealogy', 'middleware' => 'member'], function () {
            Route::get('/list', [
                'as' => 'genealogyFE.list', // it will check permission with flag is genealogyFE.list
                'uses' => 'GenealogyController@getList',
            ]);
            Route::get('/create', [
                'as' => 'genealogyFE.create', // it will check permission with flag is genealogyFE.create
                'uses' => 'GenealogyController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'genealogyFE.create', // it will check permission with flag is genealogyFE.create
                'uses' => 'GenealogyController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'genealogyFE.edit', // it will check permission with flag is genealogyFE.edit
                'uses' => 'GenealogyController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'genealogyFE.edit', // it will check permission with flag is genealogyFE.edit
                'uses' => 'GenealogyController@postUpdate',
            ]);
            Route::post('delete/', [
                'as' => 'genealogyFE.delete', // it will check permission with flag is genealogyFE.delete
                'uses' => 'GenealogyController@delete',
            ]);

            Route::post('/preview', [
                'as' => 'genealogyFE.preview', // it will check permission with flag is genealogyFE.list
                'uses' => 'GenealogyController@preview',
                'permission' => 'genealogyFE.list',
            ]);


            Route::post('/checkSympathyPermissionOnPost/{id}', [
                'as' => 'genealogyFE.checkSympathyPermissionOnPost',
                'uses' => 'GenealogyController@checkSympathyPermissionOnPost',
                'permission' => 'genealogyFE.list', // it will check permission with flag is genealogyFE.list
            ]);
            Route::post('/ajax/garden/like/{id}', [
                'as' => 'genealogyFE.like',
                'uses' => 'GenealogyController@like',
                'permission' => 'genealogyFE.list', // it will check permission with flag is genealogyFE.list
            ]);
            Route::post('/ajax/garden/dislike/{id}', [
                'as' => 'genealogyFE.dislike',
                'uses' => 'GenealogyController@dislike',
                'permission' => 'genealogyFE.list', // it will check permission with flag is genealogyFE.list
            ]);


            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'genealogyFE.checkSympathyPermissionOnComment', // it will check permission with flag is genealogyFE.list
                'uses' => 'GenealogyController@checkSympathyPermissionOnComment',
                'permission' => 'genealogyFE.list',
            ]);
            Route::post('/like', [
                'as' => 'genealogyFE.likeComments', // it will check permission with flag is genealogyFE.list
                'uses' => 'GenealogyController@likeComments',
                'permission' => 'genealogyFE.list',
            ]);
            Route::post('/dislike', [
                'as' => 'genealogyFE.dislikeComments', // it will check permission with flag is genealogyFE.list
                'uses' => 'GenealogyController@dislikeComments',
                'permission' => 'genealogyFE.list',
            ]);
        });

        Route::group(['prefix' => 'campus', 'middleware' => 'member', 'permission' => 'oldGenealogyFE.list'], function () {
            Route::get('/old-genealogy-list', 'OldGenealogyController@index')->name('campus.old.genealogy');
            Route::get('/old-genealogy-list/notices/{id}', 'OldGenealogyController@detailNotice')->name('campus.old.genealogy.notices.detail');
            Route::get('/old-genealogy-details/{id}', 'OldGenealogyController@show')->name('campus.old.genealogy.details');
            Route::post('/old-genealogy-details-comments/create', 'OldGenealogyController@createComment')->name('campus.old.genealogy.details.comments.create');
            Route::post('/old-genealogy-details-comments/delete/{id}', 'OldGenealogyController@deleteComment')->name('campus.old.genealogy.details.comments.delete');
            Route::get('/old-genealogy-search', 'SearchController@oldGenealogy')->name('old.genealogy.search');
        });

        Route::group(['prefix' => 'campus/old-genealogy', 'middleware' => 'member'], function () {

            Route::get('/list', [
                'as' => 'oldGenealogyFE.list', // it will check permission with flag is oldGenealogyFE.list
                'uses' => 'OldGenealogyController@getList',
            ]);
            Route::get('/create', [
                'as' => 'oldGenealogyFE.create', // it will check permission with flag is oldGenealogyFE.create
                'uses' => 'OldGenealogyController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'oldGenealogyFE.create', // it will check permission with flag is oldGenealogyFE.create
                'uses' => 'OldGenealogyController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'oldGenealogyFE.edit', // it will check permission with flag is oldGenealogyFE.edit
                'uses' => 'OldGenealogyController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'oldGenealogyFE.edit', // it will check permission with flag is oldGenealogyFE.edit
                'uses' => 'OldGenealogyController@postUpdate',
            ]);
            Route::post('delete/', [
                'as' => 'oldGenealogyFE.delete', // it will check permission with flag is oldGenealogyFE.delete
                'uses' => 'OldGenealogyController@delete',
            ]);

            Route::post('/preview', [
                'as' => 'oldGenealogyFE.preview', // it will check permission with flag is oldGenealogyFE.list
                'uses' => 'OldGenealogyController@preview',
                'permission' => 'oldGenealogyFE.list',
            ]);
            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'oldGenealogyFE.checkSympathyPermissionOnComment', // it will check permission with flag is oldGenealogyFE.list
                'uses' => 'OldGenealogyController@checkSympathyPermissionOnComment',
                'permission' => 'oldGenealogyFE.list',
            ]);
            Route::post('/like', [
                'as' => 'oldGenealogyFE.likeComments', // it will check permission with flag is oldGenealogyFE.list
                'uses' => 'OldGenealogyController@likeComments',
                'permission' => 'oldGenealogyFE.list',
            ]);
            Route::post('/dislike', [
                'as' => 'oldGenealogyFE.dislikeComments', // it will check permission with flag is oldGenealogyFE.list
                'uses' => 'OldGenealogyController@dislikeComments',
                'permission' => 'oldGenealogyFE.list',
            ]);
        });

        Route::group(['prefix' => 'campus/timetable', 'middleware' => 'member'], function () {
            Route::post('/ajax/search', [
                'as' => 'scheduleFE.ajaxSearch',
                'uses' => 'TimeTableController@ajaxSearch',
                'permission' => 'scheduleFE.list', // it will check permission with flag is scheduleFE.list
            ]);

            Route::post('/ajax/timetable', [
                'as' => 'scheduleFE.timetable',
                'uses' => 'TimeTableController@ajaxTimetable',
                'permission' => 'scheduleFE.list', // it will check permission with flag is scheduleFE.list
            ]);
            Route::post('/ajax/save/color', [
                'as' => 'scheduleFE.saveColor',
                'uses' => 'TimeTableController@ajaxSaveColor',
                'permission' => 'scheduleFE.list', // it will check permission with flag is scheduleFE.list
            ]);

            Route::get('/', [
                'as' => 'scheduleFE.list', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@index',
            ]);
            Route::get('/{id}', [
                'as' => 'scheduleFE.details', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@show',
            ]);

            Route::post('/schedule/create', [
                'as' => 'scheduleFE.create', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@createSchedule',
            ]);

            Route::post('/schedule/timeline/create', [
                'as' => 'scheduleFE.timeline.create', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@createScheduleTimeLine',
            ]);

            Route::post('/schedule/timeline/delete', [
                'as' => 'scheduleFE.timeline.delete', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@deleteScheduleTimeLine',
            ]);

            Route::get('/schedule/timeline/share', [
                'as' => 'scheduleFE.timeline.share', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@showFromShare',
                'permission' => 'scheduleFE.list',
            ]);

            Route::get('/schedule/timeline/share/{id}', [
                'as' => 'scheduleFE.timeline.sharebyid', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@showFromShareByID',
                'permission' => 'scheduleFE.list',
            ]);

            Route::get('/schedule/v2', [
                'as' => 'scheduleFE.timeline.v2', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@showV2',
                'permission' => 'scheduleFE.list',
            ]);

            Route::post('/schedule/timeline/setting', [
                'as' => 'scheduleFE.timeline.setting', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@updateSetting',
                'permission' => 'scheduleFE.list',
            ]);
            Route::post('/schedule/timeline/get-timeline', [
                'as' => 'scheduleFE.timeline.get.timeline', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@getTimeLine',
                'permission' => 'scheduleFE.list',
            ]);
            Route::get('/schedule/timeline/delete-timeline/{id}', [
                'as' => 'scheduleFE.timeline.delete.timeline', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@deleteTimeLine',
                'permission' => 'scheduleFE.list',
            ]);

            // Route::get('/schedule/v3', [
            //     'as' => 'scheduleFE.timeline.v3', // it will check permission with flag is genealogyFE.list
            //     'uses' => 'TimeTableController@showV3',
            //     'permission' => 'scheduleFE.list',
            // ]);
            // Route::get('/schedule/top', [
            //     'as' => 'scheduleFE.timeline.top', // it will check permission with flag is genealogyFE.list
            //     'uses' => 'TimeTableController@showTop',
            //     'permission' => 'scheduleFE.list',
            // ]);
            // Route::get('/schedule/bottom', [
            //     'as' => 'scheduleFE.timeline.bottom', // it will check permission with flag is genealogyFE.list
            //     'uses' => 'TimeTableController@showBottom',
            //     'permission' => 'scheduleFE.list',
            // ]);

            Route::post('/schedule/copySchedule', [
                'as' => 'scheduleFE.timeline.copySchedule', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@copySchedule',
                'permission' => 'scheduleFE.list',
            ]);

            Route::get('/schedule/showLecture/{id}', [
                'as' => 'scheduleFE.timeline.showLecture', // it will check permission with flag is scheduleFE.list
                'uses' => 'TimeTableController@showLecture',
                'permission' => 'scheduleFE.list',
            ]);

        });

        Route::group(['prefix' => 'campus/calculator', 'middleware' => 'member'], function () {
            Route::get('{id_calculator?}', [
                'as' => 'campus.calculator_list',// it will check permission with flag is campus.calculator_list
                'uses' => 'CalculatorController@index',
            ]);
            Route::post('create', [
                'as' => 'campus.calculator_create',
                'uses' => 'CalculatorController@create',
                'permission' => 'campus.calculator_list', // it will check permission with flag is campus.calculator_list
            ]);
            Route::post('create-detail', [
                'as' => 'campus.calculator_create_detail',
                'uses' => 'CalculatorController@createDetail',
                'permission' => 'campus.calculator_list', // it will check permission with flag is campus.calculator_list
            ]);
            Route::post('reset', [
                'as' => 'campus.calculator_reset',
                'uses' => 'CalculatorController@reset',
                'permission' => 'campus.calculator_list', // it will check permission with flag is campus.calculator_list
            ]);
            Route::post('factor', [
                'as' => 'campus.calculator_factor',
                'uses' => 'CalculatorController@factor',
                'permission' => 'campus.calculator_list', // it will check permission with flag is campus.calculator_list
            ]);
            Route::delete('destroy/{id}', [
                'as' => 'campus.calculator_destroy', // it will check permission with flag is gardenFE.comments
                'uses' => 'CalculatorController@destroy',
                'permission' => 'campus.calculator_list', // it will check permission with flag is gardenFE.comments
            ]);
        });

        Route::post('/room/transfer-to-admin', [
            'as' => 'egardenFE.room.transfer-to-admin',
            'uses' => 'EgardenController@transferToAdmin',
        ]);

        Route::post('/room/transfer-to-other-user', [
            'as' => 'egardenFE.room.transfer-to-other-user',
            'uses' => 'EgardenController@transferToOtherUser',
        ]);

        Route::post('/room/request-ownership', [
            'as' => 'egardenFE.room.request-ownership',
            'uses' => 'EgardenController@requestOwnership',
        ]);

        Route::group(['prefix' => 'garden', 'middleware' => 'member'], function () {
            Route::post('/passwd', [
                'as' => 'gardenFE.passwd',
                'uses' => 'GardenController@postPasswd',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);

            Route::post('/ajax/passwd', [
                'as' => 'gardenFE.ajaxPasswd',
                'uses' => 'GardenController@ajaxPasswd',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);
            Route::post('/ajax/passwd-post', [
                'as' => 'gardenFE.ajaxPasswdPost',
                'uses' => 'GardenController@ajaxPasswdPost',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);
            Route::post('/ajax/garden/checkSympathyPermissionOnPost/{id}', [
                'as' => 'gardenFE.checkSympathyPermissionOnPost',
                'uses' => 'GardenController@checkSympathyPermissionOnPost',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);
            Route::post('/ajax/garden/like/{id}', [
                'as' => 'gardenFE.like',
                'uses' => 'GardenController@like',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);
            Route::post('/ajax/garden/dislike/{id}', [
                'as' => 'gardenFE.dislike',
                'uses' => 'GardenController@dislike',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);

            Route::get('/list', [
                'as' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
                'uses' => 'GardenController@index',
            ]);
            Route::get('/show/{id}', [
                'as' => 'gardenFE.show',
                'uses' => 'GardenController@show',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);

            Route::get('/notices/{idCategory}/{id}', [
                'as' => 'garden.notices.detail',
                'uses' => 'GardenController@detailNotice',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);

            Route::get('/details/{idCategories}/{id}', [
                'as' => 'gardenFE.details',
                'uses' => 'GardenController@details',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);
            Route::get('/notice/details/{idCategories}/{id}', [
                'as' => 'gardenFE.notice.details',
                'uses' => 'GardenController@noticeDetails',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);
            Route::post('/comments/', [
                'as' => 'gardenFE.comments', // it will check permission with flag is gardenFE.comments
                'uses' => 'GardenController@createComment',
            ]);
            Route::post('/comments/delete/{id}', [
                'as' => 'gardenFE.comments.delete', // it will check permission with flag is gardenFE.comments
                'uses' => 'GardenController@deleteComment',
                'permission' => 'gardenFE.comments', // it will check permission with flag is gardenFE.comments
            ]);
            Route::get('/list/createByMember', [
                'as' => 'gardenFE.list.all', // it will check permission with flag is gardenFE.create
                'uses' => 'GardenController@getListCreateByMember',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);

            Route::get('/create/{id}', [
                'as' => 'gardenFE.create', // it will check permission with flag is gardenFE.create
                'uses' => 'GardenController@getCreate',
            ]);

            Route::post('/create/{id}', [
                'as' => 'gardenFE.create', // it will check permission with flag is gardenFE.create
                'uses' => 'GardenController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'gardenFE.edit', // it will check permission with flag is gardenFE.edit
                'uses' => 'GardenController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'gardenFE.edit', // it will check permission with flag is gardenFE.edit
                'uses' => 'GardenController@postUpdate',
            ]);
            Route::post('delete/', [
                'as' => 'gardenFE.delete', // it will check permission with flag is gardenFE.edit
                'uses' => 'GardenController@delete',
            ]);

            Route::post('/preview', [
                'as' => 'gardenFE.preview', // it will check permission with flag is gardenFE.list
                'uses' => 'GardenController@preview',
                'permission' => 'gardenFE.list',
            ]);

            Route::post('/photo-pod', [
                'as' => 'gardenFE.photo-pod', // it will check permission with flag is gardenFE.list
                'uses' => 'GardenController@photoPod',
                'permission' => 'gardenFE.list',
            ]);

            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'gardenFE.checkSympathyPermissionOnComment', // it will check permission with flag is gardenFE.list
                'uses' => 'GardenController@checkSympathyPermissionOnComment',
                'permission' => 'gardenFE.list',
            ]);

            Route::post('/like', [
                'as' => 'gardenFE.likeComments', // it will check permission with flag is gardenFE.list
                'uses' => 'GardenController@likeComments',
                'permission' => 'gardenFE.list',
            ]);
            Route::post('/dislike', [
                'as' => 'gardenFE.dislikeComments', // it will check permission with flag is gardenFE.list
                'uses' => 'GardenController@dislikeComments',
                'permission' => 'gardenFE.list',
            ]);

            Route::post('/{id}/addOrRemoveBookmark', [
                'as' => 'gardenFE.addOrRemoveBookmark',
                'uses' => 'GardenController@addOrRemoveBookmark',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);

            Route::get('/bookmarks', [
                'as' => 'gardenFE.bookmarks',
                'uses' => 'GardenController@bookmarks',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);

            Route::post('/addMultipleBookmark', [
                'as' => 'gardenFE.addMultipleBookmark',
                'uses' => 'GardenController@addMultipleBookmark',
                'permission' => 'gardenFE.list', // it will check permission with flag is gardenFE.list
            ]);

            //Egarden
            Route::group(['prefix' => 'egarden', 'middleware' => 'member'], function () {

                Route::post('/ajax/join/room', [
                    'as' => 'egardenFE.ajaxJoinRoom',
                    'uses' => 'EgardenController@ajaxJoinRoom',
                    'permission' => 'gardenFE.list', // it will check permission with flag is egardenFE.list
                ]);

                Route::get('/room/list', [
                    'as' => 'egardenFE.room.list', // it will check permission with flag is egardenFE.list
                    'uses' => 'EgardenController@room',
                ]);

                Route::get('/room/myroom/list', [
                    'as' => 'egardenFE.room.myroom.list',
                    'uses' => 'EgardenController@myRoom',
                    'permission' => 'egardenFE.list', // it will check permission with flag is egardenFE.list
                ]);

                Route::get('/room/create', [
                    'as' => 'egardenFE.room.create', // it will check permission with flag is egardenFE.list
                    'uses' => 'EgardenController@createRoom',
                ]);

                Route::post('/room/create', [
                    'as' => 'egardenFE.room.create', // it will check permission with flag is egardenFE.list
                    'uses' => 'EgardenController@storeRoom',
                ]);

                Route::get('room/edit/{id}', [
                    'as' => 'egardenFE.room.edit', // it will check permission with flag is egardenFE.edit
                    'uses' => 'EgardenController@getEditRoom',
                ]);

                Route::post('room/edit/{id}', [
                    'as' => 'egardenFE.room.edit', // it will check permission with flag is egardenFE.edit
                    'uses' => 'EgardenController@postUpdateRoom',
                ]);

                Route::post('room/delete', [
                    'as' => 'egardenFE.room.delete', // it will check permission with flag is egardenFE.edit
                    'uses' => 'EgardenController@deleteRoom',
                ]);

                Route::get('/room/detail/{id}', [
                    'as' => 'egardenFE.room.detail',
                    'uses' => 'EgardenController@roomDetail',
                    'permission' => 'gardenFE.list', // it will check permission with flag is egardenFE.list
                ]);

                Route::get('/details/{id}', [
                    'as' => 'egardenFE.details',
                    'uses' => 'EgardenController@details',
                    'permission' => 'egardenFE.list', // it will check permission with flag is gardenFE.list
                ]);

                Route::get('/notice/details/{id}', [
                    'as' => 'egardenFE.notice.details',
                    'uses' => 'EgardenController@noticeDetails',
                    'permission' => 'egardenFE.list', // it will check permission with flag is gardenFE.list
                ]);

                Route::get('room/approved/{id}', [
                    'as' => 'egardenFE.room.approved', // it will check permission with flag is egardenFE.edit
                    'uses' => 'EgardenController@getApprovedRoom',
                    'permission' => 'egardenFE.room.create',
                ]);

                Route::post('room/approved/{id}', [
                    'as' => 'egardenFE.room.approved', // it will check permission with flag is egardenFE.edit
                    'uses' => 'EgardenController@postApprovedRoom',
                    'permission' => 'egardenFE.room.create',
                ]);

                Route::get('list/room/{id}', [
                    'as' => 'egardenFE.list', // it will check permission with flag is egardenFE.edit
                    'uses' => 'EgardenController@getList',
                ]);

                Route::get('create/room/{id}', [
                    'as' => 'egardenFE.create', // it will check permission with flag is egardenFE.edit
                    'uses' => 'EgardenController@getCreate',
                ]);
                Route::post('create/room/{id}', [
                    'as' => 'egardenFE.create', // it will check permission with flag is egardenFE.edit
                    'uses' => 'EgardenController@postStore',
                ]);

                Route::get('edit/{idEgarden}/room/{id}', [
                    'as' => 'egardenFE.edit', // it will check permission with flag is egardenFE.edit
                    'uses' => 'EgardenController@getEdit',
                ]);
                Route::post('edit/{idEgarden}/room/{id}', [
                    'as' => 'egardenFE.edit', // it will check permission with flag is egardenFE.edit
                    'uses' => 'EgardenController@postUpdate',
                ]);

                Route::post('delete/', [
                    'as' => 'egardenFE.delete', // it will check permission with flag is egardenFE.edit
                    'uses' => 'EgardenController@deleteEgarden',
                ]);

                Route::post('/comments/', [
                    'as' => 'egardenFE.comments', // it will check permission with flag is egardenFE.comments
                    'uses' => 'EgardenController@createComment',
                ]);

                Route::post('/comments/delete/{id}', [
                    'as' => 'egardenFE.comments.delete',
                    'uses' => 'EgardenController@deleteComment',
                    'permission' => 'egardenFE.comments', // it will check permission with flag is egardenFE.comments
                ]);
                Route::post('/ajax/egarden/like/{id}', [
                    'as' => 'egardenFE.like',
                    'uses' => 'EgardenController@like',
                    'permission' => 'egardenFE.list', // it will check permission with flag is gardenFE.list
                ]);
                Route::post('/ajax/egarden/dislike/{id}', [
                    'as' => 'egardenFE.dislike',
                    'uses' => 'EgardenController@dislike',
                    'permission' => 'egardenFE.list', // it will check permission with flag is gardenFE.list
                ]);
                Route::post('/like', [
                    'as' => 'egardenFE.likeComments', // it will check permission with flag is gardenFE.list
                    'uses' => 'EgardenController@likeComments',
                    'permission' => 'egardenFE.list',
                ]);
                Route::post('/dislike', [
                    'as' => 'egardenFE.dislikeComments', // it will check permission with flag is gardenFE.list
                    'uses' => 'EgardenController@dislikeComments',
                    'permission' => 'egardenFE.list',
                ]);

                Route::post('/preview', [
                    'as' => 'egardenFE.preview', // it will check permission with flag is egardenFE.list
                    'uses' => 'EgardenController@preview',
                    'permission' => 'egardenFE.list',
                ]);
                Route::get('/home', [
                    'as' => 'egardenFE.home', // it will check permission with flag is egardenFE.list
                    'uses' => 'EgardenController@home',
                    'permission' => 'egardenFE.list',
                ]);
                Route::get('/room/{idRoom}/categories/list', [
                    'as' => 'egardenFE.room.categories.list',
                    'uses' => 'EgardenController@indexCategories',
                    'permission' => 'egardenFE.room.list',
                ]);

                Route::get('/room/{idRoom}/categories/create', [
                    'as' => 'egardenFE.room.categories.create',
                    'uses' => 'EgardenController@createCategories',
                    'permission' => 'egardenFE.room.create',
                ]);
                Route::post('/room/{idRoom}/categories/create', [
                    'as' => 'egardenFE.room.categories.store',
                    'uses' => 'EgardenController@storeCategories',
                    'permission' => 'egardenFE.room.create',
                ]);

                Route::get('/room/{idRoom}/categories/edit/{id}', [
                    'as' => 'egardenFE.room.categories.edit',
                    'uses' => 'EgardenController@editCategories',
                    'permission' => 'egardenFE.room.edit',
                ]);
                Route::post('/room/{idRoom}/categories/edit/{id}', [
                    'as' => 'egardenFE.room.categories.update',
                    'uses' => 'EgardenController@updateCategories',
                    'permission' => 'egardenFE.room.edit',
                ]);

                Route::post('/room/categories/delete', [
                    'as' => 'egardenFE.room.categories.delete',
                    'uses' => 'EgardenController@deleteCategories',
                    'permission' => 'egardenFE.room.delete',
                ]);
                Route::post('/room/important', [
                    'as' => 'egardenFE.room.important.update',
                    'uses' => 'EgardenController@updateImportant',
                    'permission' => 'egardenFE.room.list',
                ]);
            });
        });

        //Master Room
        Route::group(['prefix' => 'master-room', 'middleware' => 'member'], function () {

            Route::get('/reply/{id}/create/{idCategory}', [
                'as' => 'masterRoomFE.reply.create', // it will check permission with flag is masterRoomFE.create
                'uses' => 'MasterRoomController@getReplyCreate',
                'permission' => 'masterRoomFE.list',
            ]);
            Route::get('/reply/show/{id}', [
                'as' => 'masterRoomFE.reply.detail',
                'uses' => 'MasterRoomController@showReply',
                'permission' => 'masterRoomFE.list', // it will check permission with flag is masterRoomFE.list
            ]);
            Route::get('/reply/edit/{id}', [
                'as' => 'masterRoomFE.reply.edit', // it will check permission with flag is gardenFE.edit
                'uses' => 'MasterRoomController@getReplyEdit',
                'permission' => 'masterRoomFE.list'
            ]);

            Route::get('/list/{idCategory?}', [
                'as' => 'masterRoomFE.list', // it will check permission with flag is masterRoomFE.list
                'uses' => 'MasterRoomController@index',
            ]);
            Route::get('/show/{id}', [
                'as' => 'masterRoomFE.detail',
                'uses' => 'MasterRoomController@show',
                'permission' => 'masterRoomFE.list', // it will check permission with flag is masterRoomFE.list
            ]);

            Route::get('/comments/', [
                'as' => 'masterRoomFE.comments.list',
                'uses' => 'MasterRoomController@createComment',
                'permission' => 'masterRoomFE.comments.list', // it will check permission with flag is masterRoomFE.list
            ]);
            Route::post('/comments/', [
                'as' => 'masterRoomFE.comments.create',
                'uses' => 'MasterRoomController@createComment',
                'permission' => 'masterRoomFE.list', // it will check permission with flag is masterRoomFE.list
            ]);
            Route::post('/comments/delete/{id}', [
                'as' => 'masterRoomFE.comments.delete',
                'uses' => 'MasterRoomController@deleteComment',
                'permission' => 'masterRoomFE.list', // it will check permission with flag is masterRoomFE.list
            ]);

            Route::get('/createByMember/list', [
                'as' => 'masterRoomFE.list.all',
                'uses' => 'MasterRoomController@getList',
                'permission' => 'masterRoomFE.list', // it will check permission with flag is masterRoomFE.list
            ]);

            Route::get('/create/{idCategory}', [
                'as' => 'masterRoomFE.create', // it will check permission with flag is masterRoomFE.create
                'uses' => 'MasterRoomController@getCreate',
            ]);

            Route::post('/create/{idCategory}', [
                'as' => 'masterRoomFE.create', // it will check permission with flag is masterRoomFE.create
                'uses' => 'MasterRoomController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'masterRoomFE.edit', // it will check permission with flag is gardenFE.edit
                'uses' => 'MasterRoomController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'masterRoomFE.edit', // it will check permission with flag is gardenFE.edit
                'uses' => 'MasterRoomController@postUpdate',
            ]);

            Route::post('delete/', [
                'as' => 'masterRoomFE.delete', // it will check permission with flag is egardenFE.edit
                'uses' => 'MasterRoomController@delete',
            ]);

            //Address
            Route::group(['prefix' => 'address', 'middleware' => 'member'], function () {

                Route::get('/list', [
                    'as' => 'masterRoomFE.address.list',
                    'uses' => 'AddressController@index',
                    'permission' => 'masterRoomFE.list'
                ]);

                Route::get('/create', [
                    'as' => 'masterRoomFE.address.create',
                    'uses' => 'AddressController@getCreate',
                    'permission' => 'masterRoomFE.create'
                ]);

                Route::post('/create', [
                    'as' => 'masterRoomFE.address.create',
                    'uses' => 'AddressController@postStore',
                    'permission' => 'masterRoomFE.create'
                ]);

                Route::get('/edit/{id}', [
                    'as' => 'masterRoomFE.address.edit',
                    'uses' => 'AddressController@getEdit',
                    'permission' => 'masterRoomFE.edit'
                ]);

                Route::post('/edit/{id}', [
                    'as' => 'masterRoomFE.address.edit',
                    'uses' => 'AddressController@postUpdate',
                    'permission' => 'masterRoomFE.edit'
                ]);

                Route::post('delete', [
                    'as' => 'masterRoomFE.address.delete',
                    'uses' => 'AddressController@delete',
                    'permission' => 'masterRoomFE.delete'
                ]);

            });

        });

        //New Contents
        Route::group(['prefix' => 'new-contents', 'middleware' => 'member'], function () {

            Route::get('/list/{idCategory?}', [
                'as' => 'newContentsFE.list', // it will check permission with flag is newContentsFE.list
                'uses' => 'NewContentsController@index',
            ]);
            Route::get('/show/{id}', [
                'as' => 'newContentsFE.detail',
                'uses' => 'NewContentsController@show',
                'permission' => 'newContentsFE.list', // it will check permission with flag is newContentsFE.list
            ]);

            Route::post('/comments/', [
                'as' => 'newContentsFE.comments',
                'uses' => 'NewContentsController@createComment',
                'permission' => 'newContentsFE.list', // it will check permission with flag is newContentsFE.list
            ]);
            Route::post('/comments/delete/{id}', [
                'as' => 'newContentsFE.comments.delete',
                'uses' => 'NewContentsController@deleteComment',
                'permission' => 'newContentsFE.list', // it will check permission with flag is newContentsFE.list
            ]);

            Route::get('/createByMember/list', [
                'as' => 'newContentsFE.list.all',
                'uses' => 'NewContentsController@getList',
                'permission' => 'newContentsFE.list', // it will check permission with flag is newContentsFE.list
            ]);

            Route::get('/create/{idCategory}', [
                'as' => 'newContentsFE.create', // it will check permission with flag is newContentsFE.create
                'uses' => 'NewContentsController@getCreate',
            ]);

            Route::post('/create/{idCategory}', [
                'as' => 'newContentsFE.create', // it will check permission with flag is newContentsFE.create
                'uses' => 'NewContentsController@postStore',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'newContentsFE.edit', // it will check permission with flag is newContentsFE.edit
                'uses' => 'NewContentsController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'newContentsFE.edit', // it will check permission with flag is newContentsFE.edit
                'uses' => 'NewContentsController@postUpdate',
            ]);

            Route::post('delete/', [
                'as' => 'newContentsFE.delete', // it will check permission with flag is newContentsFE.edit
                'uses' => 'NewContentsController@delete',
            ]);

        });
        //Import DB
        Route::group(['prefix' => 'import', 'middleware' => 'member'], function () {
            Route::get('/index', [
                'as' => 'member.importDB', // it will check permission with flag is newContentsFE.list
                'uses' => 'ImportController@index',
            ]);
            // Route::post('/index', [
            //     'as' => 'member.importDB.postImport', // it will check permission with flag is newContentsFE.list
            //     'uses' => 'ImportController@postImport',
            //     'permission' => 'member.importDB'
            // ]);
        });

        Route::group(['middleware' => 'member', 'as' => 'public.member.'], function () {
            Route::get('member/message/list', 'MessageController@index')->name('message.index');
            Route::post('member/send-message', 'MessageController@send')->name('message.send');
            Route::post('member/showMessage', 'MessageController@show')->name('message.show');
            Route::post('member/delete/many', 'MessageController@deleteMany')->name('message.delete.many');
            Route::post('member/delete/', 'MessageController@delete')->name('message.delete');

        });

        // notice introduction FE
        Route::group(['prefix' => 'notice-introduction-fe'], function () {

            Route::post('/comments', [
                'as' => 'noticesFE.comments',
                'uses' => 'NoticesIntroController@createComment',
            ]);

            Route::post('/comments/delete/{id}', [
                'as' => 'noticesFE.comments.delete',
                'uses' => 'NoticesIntroController@deleteComment',
            ]);

            Route::post('/ajax/like/{id}', [
                'as' => 'noticesFE.like',
                'uses' => 'NoticesIntroController@like',
            ]);
            Route::post('/ajax/dislike/{id}', [
                'as' => 'noticesFE.dislike',
                'uses' => 'NoticesIntroController@dislike',
            ]);
            Route::post('/like', [
                'as' => 'noticesFE.likeComments', // it will check permission with flag is gardenFE.list
                'uses' => 'NoticesIntroController@likeComments',
            ]);
            Route::post('/dislike', [
                'as' => 'noticesFE.dislikeComments', // it will check permission with flag is gardenFE.list
                'uses' => 'NoticesIntroController@dislikeComments',
            ]);

            Route::post('/checkSympathyPermissionOnComment', [
                'as' => 'noticesFE.checkSympathyPermissionOnComment', // it will check permission with flag is gardenFE.list
                'uses' => 'NoticesIntroController@checkSympathyPermissionOnComment',
            ]);
        });
    });
});

Route::post('error', function (\Illuminate\Http\Request $request){
    $data = $request->get('data');
    $model = new \Botble\Setting\Models\Error();
    $model->content = $data;
    $model->save();
    return response([
        'success' => true
    ]);
});
