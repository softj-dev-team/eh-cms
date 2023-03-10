<?php

namespace Botble\Life\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Life\Models\Ads\Ads;
use Botble\Life\Models\Ads\AdsCategories;
use Botble\Life\Models\Ads\AdsComments;
use Botble\Life\Models\Description;
use Botble\Life\Models\Flare;
use Botble\Life\Models\FlareCategories;
use Botble\Life\Models\FlareComments;
use Botble\Life\Models\Jobs\JobsCategories;
use Botble\Life\Models\Jobs\JobsComments;
use Botble\Life\Models\Jobs\JobsPartTime;
use Botble\Life\Models\Life;
use Botble\Life\Models\Notices;
use Botble\Life\Models\OpenSpace\OpenSpace;
use Botble\Life\Models\OpenSpace\OpenSpaceComments;
use Botble\Life\Models\Shelter\Shelter;
use Botble\Life\Models\Shelter\ShelterCategories;
use Botble\Life\Models\Shelter\ShelterComments;
use Botble\Life\Repositories\Caches\Ads\AdsCacheDecorator;
use Botble\Life\Repositories\Caches\Ads\AdsCategoriesCacheDecorator;
use Botble\Life\Repositories\Caches\Ads\AdsCommentsCacheDecorator;
use Botble\Life\Repositories\Caches\DescriptionCacheDecorator;
use Botble\Life\Repositories\Caches\FlareCacheDecorator;
use Botble\Life\Repositories\Caches\FlareCategoriesCacheDecorator;
use Botble\Life\Repositories\Caches\FlareCommentsCacheDecorator;
use Botble\Life\Repositories\Caches\Jobs\JobsCategoriesCacheDecorator;
use Botble\Life\Repositories\Caches\Jobs\JobsCommentsCacheDecorator;
use Botble\Life\Repositories\Caches\Jobs\JobsPartTimeCacheDecorator;
use Botble\Life\Repositories\Caches\LifeCacheDecorator;
use Botble\Life\Repositories\Caches\NoticesCacheDecorator;
use Botble\Life\Repositories\Caches\OpenSpace\OpenSpaceCacheDecorator;
use Botble\Life\Repositories\Caches\OpenSpace\OpenSpaceCommentsCacheDecorator;
use Botble\Life\Repositories\Caches\Shelter\ShelterCacheDecorator;
use Botble\Life\Repositories\Caches\Shelter\ShelterCategoriesCacheDecorator;
use Botble\Life\Repositories\Caches\Shelter\ShelterCommentsCacheDecorator;
use Botble\Life\Repositories\Eloquent\Ads\AdsCategoriesRepository;
use Botble\Life\Repositories\Eloquent\Ads\AdsCommentsRepository;
use Botble\Life\Repositories\Eloquent\Ads\AdsRepository;
use Botble\Life\Repositories\Eloquent\DescriptionRepository;
use Botble\Life\Repositories\Eloquent\FlareCategoriesRepository;
use Botble\Life\Repositories\Eloquent\FlareCommentsRepository;
use Botble\Life\Repositories\Eloquent\FlareRepository;
use Botble\Life\Repositories\Eloquent\Jobs\JobsCategoriesRepository;
use Botble\Life\Repositories\Eloquent\Jobs\JobsCommentsRepository;
use Botble\Life\Repositories\Eloquent\Jobs\JobsPartTimeRepository;
use Botble\Life\Repositories\Eloquent\LifeRepository;
use Botble\Life\Repositories\Eloquent\NoticesRepository;
use Botble\Life\Repositories\Eloquent\OpenSpace\OpenSpaceCommentsRepository;
use Botble\Life\Repositories\Eloquent\OpenSpace\OpenSpaceRepository;
use Botble\Life\Repositories\Eloquent\Shelter\ShelterCategoriesRepository;
use Botble\Life\Repositories\Eloquent\Shelter\ShelterCommentsRepository;
use Botble\Life\Repositories\Eloquent\Shelter\ShelterRepository;
use Botble\Life\Repositories\Interfaces\Ads\AdsCategoriesInterface;
use Botble\Life\Repositories\Interfaces\Ads\AdsCommentsInterface;
use Botble\Life\Repositories\Interfaces\Ads\AdsInterface;
use Botble\Life\Repositories\Interfaces\DescriptionInterface;
use Botble\Life\Repositories\Interfaces\FlareCategoriesInterface;
use Botble\Life\Repositories\Interfaces\FlareCommentsInterface;
use Botble\Life\Repositories\Interfaces\FlareInterface;
use Botble\Life\Repositories\Interfaces\Jobs\JobsCategoriesInterface;
use Botble\Life\Repositories\Interfaces\Jobs\JobsCommentsInterface;
use Botble\Life\Repositories\Interfaces\Jobs\JobsPartTimeInterface;
use Botble\Life\Repositories\Interfaces\LifeInterface;
use Botble\Life\Repositories\Interfaces\NoticesInterface;
use Botble\Life\Repositories\Interfaces\OpenSpace\OpenSpaceCommentsInterface;
use Botble\Life\Repositories\Interfaces\OpenSpace\OpenSpaceInterface;
use Botble\Life\Repositories\Interfaces\Shelter\ShelterCategoriesInterface;
use Botble\Life\Repositories\Interfaces\Shelter\ShelterCommentsInterface;
use Botble\Life\Repositories\Interfaces\Shelter\ShelterInterface;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class LifeServiceProvider extends ServiceProvider
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
        $this->app->singleton(LifeInterface::class, function () {
            return new LifeCacheDecorator(new LifeRepository(new Life));
        });
        //Flare Market
        $this->app->singleton(FlareInterface::class, function () {
            return new FlareCacheDecorator(new FlareRepository(new Flare));
        });
        $this->app->singleton(FlareCategoriesInterface::class, function () {
            return new FlareCategoriesCacheDecorator(new FlareCategoriesRepository(new FlareCategories));
        });
        $this->app->singleton(FlareCommentsInterface::class, function () {
            return new FlareCommentsCacheDecorator(new FlareCommentsRepository(new FlareComments));
        });
        //Notices
        $this->app->singleton(NoticesInterface::class, function () {
            return new NoticesCacheDecorator(new NoticesRepository(new Notices));
        });
        //Part Time Jobs
        $this->app->singleton(JobsPartTimeInterface::class, function () {
            return new JobsPartTimeCacheDecorator(new JobsPartTimeRepository(new JobsPartTime));
        });
        $this->app->singleton(JobsCategoriesInterface::class, function () {
            return new JobsCategoriesCacheDecorator(new JobsCategoriesRepository(new JobsCategories));
        });
        $this->app->singleton(JobsCommentsInterface::class, function () {
            return new JobsCommentsCacheDecorator(new JobsCommentsRepository(new JobsComments));
        });

        //Advertisements
        $this->app->singleton(AdsInterface::class, function () {
            return new AdsCacheDecorator(new AdsRepository(new Ads));
        });
        $this->app->singleton(AdsCategoriesInterface::class, function () {
            return new AdsCategoriesCacheDecorator(new AdsCategoriesRepository(new AdsCategories));
        });
        $this->app->singleton(AdsCommentsInterface::class, function () {
            return new AdsCommentsCacheDecorator(new AdsCommentsRepository(new AdsComments));
        });
        //Shelter
        $this->app->singleton(ShelterInterface::class, function () {
            return new ShelterCacheDecorator(new ShelterRepository(new Shelter));
        });
        $this->app->singleton(ShelterCategoriesInterface::class, function () {
            return new ShelterCategoriesCacheDecorator(new ShelterCategoriesRepository(new ShelterCategories));
        });
        $this->app->singleton(ShelterCommentsInterface::class, function () {
            return new ShelterCommentsCacheDecorator(new ShelterCommentsRepository(new ShelterComments));
        });

        //Description
        $this->app->singleton(DescriptionInterface::class, function () {
            return new DescriptionCacheDecorator(new DescriptionRepository(new Description));
        });

        //OpenSpace
        $this->app->singleton(OpenSpaceInterface::class, function () {
            return new OpenSpaceCacheDecorator(new OpenSpaceRepository(new OpenSpace));
        });
        //Open Space Comments
        $this->app->singleton(OpenSpaceCommentsInterface::class, function () {
            return new OpenSpaceCommentsCacheDecorator(new OpenSpaceCommentsRepository(new OpenSpaceComments));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {

        $this->setNamespace('plugins/life')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->publishAssetsFolder()
            ->publishPublicFolder()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-plugins-life',
                    'priority' => 3,
                    'parent_id' => null,
                    'name' => '라이프 관리',
                    'icon' => 'fa fa-cubes',
                    'url' => '',
                    'permissions' => ['life.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-flare-market',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-life',
                    'name' => '벼룩시장 관리',
                    'icon' => null,
                    'url' => route('life.flare.list'),
                    'permissions' => ['life.flare.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-jobs_part_time',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-life',
                    'name' => '알바하자 관리',
                    'icon' => null,
                    'url' => route('life.jobs_part_time.list'),
                    'permissions' => ['life.jobs_part_time.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-advertisements',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-life',
                    'name' => '광고홍보 관리',
                    'icon' => null,
                    'url' => route('life.advertisements.list'),
                    'permissions' => ['life.advertisements.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-shelter',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-life',
                    'name' => '주거정보 관리',
                    'icon' => null,
                    'url' => route('life.shelter.list'),
                    'permissions' => ['life.shelter.list'],
                ])
//                ->registerItem([
//                    'id' => 'cms-plugins-notices',
//                    'priority' => 4,
//                    'parent_id' => 'cms-plugins-life',
//                    'name' => '공지사항 관리',
//                    'icon' => null,
//                    'url' => route('life.notices.list'),
//                    'permissions' => ['life.notices.list'],
//                ])
                ->registerItem([
                    'id' => 'cms-plugins-description',
                    'priority' => 5,
                    'parent_id' => 'cms-plugins-life',
                    'name' => '라이프 소개 관리',
                    'icon' => null,
                    'url' => route('life.description.list'),
                    'permissions' => ['life.description.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-open-space',
                    'priority' => 6,
                    'parent_id' => 'cms-plugins-life',
                    'name' => '열린광장 관리',
                    'icon' => null,
                    'url' => route('life.open.space.list'),
                    'permissions' => ['life.open.space.list'],
                ]);
        });
    }
}
