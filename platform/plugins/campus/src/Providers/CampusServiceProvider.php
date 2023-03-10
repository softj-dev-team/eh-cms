<?php

namespace Botble\Campus\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Campus\Models\Campus;
use Botble\Campus\Models\Description\DescriptionCampus;
use Botble\Campus\Models\Evaluation\CommentsEvaluation;
use Botble\Campus\Models\Evaluation\Evaluation;
use Botble\Campus\Models\Evaluation\Major;
use Botble\Campus\Models\Genealogy\Genealogy;
use Botble\Campus\Models\Genealogy\GenealogyComments;
use Botble\Campus\Models\Notices\NoticesCampus;
use Botble\Campus\Models\OldGenealogy\OldGenealogy;
use Botble\Campus\Models\OldGenealogy\OldGenealogyComments;
use Botble\Campus\Models\Schedule\Schedule;
use Botble\Campus\Models\Schedule\ScheduleDay;
use Botble\Campus\Models\Schedule\ScheduleFilter;
use Botble\Campus\Models\Schedule\ScheduleTime;
use Botble\Campus\Models\Schedule\ScheduleTimeLine;
use Botble\Campus\Models\StudyRoom\StudyRoom;
use Botble\Campus\Models\StudyRoom\StudyRoomCategories;
use Botble\Campus\Models\StudyRoom\StudyRoomComments;
use Botble\Campus\Repositories\Caches\CampusCacheDecorator;
use Botble\Campus\Repositories\Caches\Description\DescriptionCampusCacheDecorator;
use Botble\Campus\Repositories\Caches\Evaluation\CommentsEvaluationCacheDecorator;
use Botble\Campus\Repositories\Caches\Evaluation\EvaluationCacheDecorator;
use Botble\Campus\Repositories\Caches\Evaluation\MajorCacheDecorator;
use Botble\Campus\Repositories\Caches\Genealogy\GenealogyCacheDecorator;
use Botble\Campus\Repositories\Caches\Genealogy\GenealogyCommentsCacheDecorator;
use Botble\Campus\Repositories\Caches\Notices\NoticesCampusCacheDecorator;
use Botble\Campus\Repositories\Caches\OldGenealogy\OldGenealogyCacheDecorator;
use Botble\Campus\Repositories\Caches\OldGenealogy\OldGenealogyCommentsCacheDecorator;
use Botble\Campus\Repositories\Caches\Schedule\ScheduleCacheDecorator;
use Botble\Campus\Repositories\Caches\Schedule\ScheduleDayCacheDecorator;
use Botble\Campus\Repositories\Caches\Schedule\ScheduleFilterCacheDecorator;
use Botble\Campus\Repositories\Caches\Schedule\ScheduleTimeCacheDecorator;
use Botble\Campus\Repositories\Caches\Schedule\ScheduleTimeLineCacheDecorator;
use Botble\Campus\Repositories\Caches\StudyRoom\StudyRoomCacheDecorator;
use Botble\Campus\Repositories\Caches\StudyRoom\StudyRoomCategoriesCacheDecorator;
use Botble\Campus\Repositories\Caches\StudyRoom\StudyRoomCommentsCacheDecorator;
use Botble\Campus\Repositories\Eloquent\CampusRepository;
use Botble\Campus\Repositories\Eloquent\Description\DescriptionCampusRepository;
use Botble\Campus\Repositories\Eloquent\Evaluation\CommentsEvaluationRepository;
use Botble\Campus\Repositories\Eloquent\Evaluation\EvaluationRepository;
use Botble\Campus\Repositories\Eloquent\Evaluation\MajorRepository;
use Botble\Campus\Repositories\Eloquent\Genealogy\GenealogyCommentsRepository;
use Botble\Campus\Repositories\Eloquent\Genealogy\GenealogyRepository;
use Botble\Campus\Repositories\Eloquent\Notices\NoticesCampusRepository;
use Botble\Campus\Repositories\Eloquent\OldGenealogy\OldGenealogyCommentsRepository;
use Botble\Campus\Repositories\Eloquent\OldGenealogy\OldGenealogyRepository;
use Botble\Campus\Repositories\Eloquent\Schedule\ScheduleDayRepository;
use Botble\Campus\Repositories\Eloquent\Schedule\ScheduleFilterRepository;
use Botble\Campus\Repositories\Eloquent\Schedule\ScheduleRepository;
use Botble\Campus\Repositories\Eloquent\Schedule\ScheduleTimeLineRepository;
use Botble\Campus\Repositories\Eloquent\Schedule\ScheduleTimeRepository;
use Botble\Campus\Repositories\Eloquent\StudyRoom\StudyRoomCategoriesRepository;
use Botble\Campus\Repositories\Eloquent\StudyRoom\StudyRoomCommentsRepository;
use Botble\Campus\Repositories\Eloquent\StudyRoom\StudyRoomRepository;
use Botble\Campus\Repositories\Interfaces\CampusInterface;
use Botble\Campus\Repositories\Interfaces\Description\DescriptionCampusInterface;
use Botble\Campus\Repositories\Interfaces\Evaluation\CommentsEvaluationInterface;
use Botble\Campus\Repositories\Interfaces\Evaluation\EvaluationInterface;
use Botble\Campus\Repositories\Interfaces\Evaluation\MajorInterface;
use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyCommentsInterface;
use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyInterface;
use Botble\Campus\Repositories\Interfaces\Notices\NoticesCampusInterface;
use Botble\Campus\Repositories\Interfaces\OldGenealogy\OldGenealogyCommentsInterface;
use Botble\Campus\Repositories\Interfaces\OldGenealogy\OldGenealogyInterface;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleDayInterface;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleFilterInterface;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleInterface;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleTimeInterface;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleTimeLineInterface;
use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomCategoriesInterface;
use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomCommentsInterface;
use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomInterface;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class CampusServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        $this->app->singleton(CampusInterface::class, function () {
            return new CampusCacheDecorator(new CampusRepository(new Campus));
        });

        //StudyRoom
        $this->app->singleton(StudyRoomInterface::class, function () {
            return new StudyRoomCacheDecorator(new StudyRoomRepository(new StudyRoom));
        });
        $this->app->singleton(StudyRoomCategoriesInterface::class, function () {
            return new StudyRoomCategoriesCacheDecorator(new StudyRoomCategoriesRepository(new StudyRoomCategories));
        });
        $this->app->singleton(StudyRoomCommentsInterface::class, function () {
            return new StudyRoomCommentsCacheDecorator(new StudyRoomCommentsRepository(new StudyRoomComments));
        });

        //Genealogy
        $this->app->singleton(GenealogyInterface::class, function () {
            return new GenealogyCacheDecorator(new GenealogyRepository(new Genealogy));
        });
        $this->app->singleton(GenealogyCommentsInterface::class, function () {
            return new GenealogyCommentsCacheDecorator(new GenealogyCommentsRepository(new GenealogyComments));
        });

        //OldGenealogy
        $this->app->singleton(OldGenealogyInterface::class, function () {
            return new OldGenealogyCacheDecorator(new OldGenealogyRepository(new OldGenealogy));
        });
        $this->app->singleton(OldGenealogyCommentsInterface::class, function () {
            return new OldGenealogyCommentsCacheDecorator(new OldGenealogyCommentsRepository(new OldGenealogyComments));
        });

        //Evalution
        $this->app->singleton(EvaluationInterface::class, function () {
            return new EvaluationCacheDecorator(new EvaluationRepository(new Evaluation));
        });
        //Major
        $this->app->singleton(MajorInterface::class, function () {
            return new MajorCacheDecorator(new MajorRepository(new Major));
        });
        //comments
        $this->app->singleton(CommentsEvaluationInterface::class, function () {
            return new CommentsEvaluationCacheDecorator(new CommentsEvaluationRepository(new CommentsEvaluation));
        });

        //Schedule
        $this->app->singleton(ScheduleInterface::class, function () {
            return new ScheduleCacheDecorator(new ScheduleRepository(new Schedule));
        });
        $this->app->singleton(ScheduleTimeInterface::class, function () {
            return new ScheduleTimeCacheDecorator(new ScheduleTimeRepository(new ScheduleTime));
        });
        $this->app->singleton(ScheduleDayInterface::class, function () {
            return new ScheduleDayCacheDecorator(new ScheduleDayRepository(new ScheduleDay));
        });
        $this->app->singleton(ScheduleTimeLineInterface::class, function () {
            return new ScheduleTimeLineCacheDecorator(new ScheduleTimeLineRepository(new ScheduleTimeLine));
        });
        $this->app->singleton(ScheduleFilterInterface::class, function () {
            return new ScheduleFilterCacheDecorator(new ScheduleFilterRepository(new ScheduleFilter));
        });

        //Notices
        $this->app->singleton(NoticesCampusInterface::class, function () {
            return new NoticesCampusCacheDecorator(new NoticesCampusRepository(new NoticesCampus));
        });

        //Description
        $this->app->singleton(DescriptionCampusInterface::class, function () {
            return new DescriptionCampusCacheDecorator(new DescriptionCampusRepository(new DescriptionCampus));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setNamespace('plugins/campus')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishPublicFolder()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-campus',
                'priority' => 2,
                'parent_id' => null,
                'name' => '캠퍼스 관리',
                'icon' => 'fab fa-jedi-order',
                'url' => null,
                'permissions' => ['campus.list'],
            ])
                ->registerItem([
                    'id' => 'cms-plugins-study-room',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-campus',
                    'name' => '스터디룸 관리',
                    'icon' => null,
                    'url' => route('campus.study_room.list'),
                    'permissions' => ['campus.study_room.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-genealogy',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-campus',
                    'name' => '이화계보 관리',
                    'icon' => null,
                    'url' => route('campus.genealogy.list'),
                    'permissions' => ['campus.genealogy.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-old-genealogy',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-campus',
                    'name' => '지난 계보 관리',
                    'icon' => null,
                    'url' => route('campus.old.genealogy.list'),
                    'permissions' => ['campus.old.genealogy.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-evaluation',
                    'priority' => 4,
                    'parent_id' => 'cms-plugins-campus',
                    'name' => '강의평가 관리',
                    'icon' => null,
                    'url' => route('campus.evaluation.list'),
                    'permissions' => ['campus.evaluation.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-schedule',
                    'priority' => 5,
                    'parent_id' => 'cms-plugins-campus',
                    'name' => '시간표 관리',
                    'icon' => null,
                    'url' => route('campus.schedule.list'),
                    'permissions' => ['campus.schedule.list'],
                ])
//                ->registerItem([
//                    'id' => 'cms-plugins-campus-notices',
//                    'priority' => 6,
//                    'parent_id' => 'cms-plugins-campus',
//                    'name' => '캠퍼스 공지사항 관리',
//                    'icon' => null,
//                    'url' => route('campus.notices.list'),
//                    'permissions' => ['campus.notices.list'],
//                ])
                ->registerItem([
                    'id' => 'cms-plugins-campus-description',
                    'priority' => 7,
                    'parent_id' => 'cms-plugins-campus',
                    'name' => '캠퍼스 소개 관리',
                    'icon' => null,
                    'url' => route('campus.description.list'),
                    'permissions' => ['campus.description.list'],
                ]);
        });
    }
}
