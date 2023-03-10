<?php

namespace Botble\Introduction\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Introduction\Models\CategoriesIntroduction;
use Botble\Introduction\Models\Faq\FaqCategories;
use Botble\Introduction\Models\Faq\FaqIntroduction;
use Botble\Introduction\Models\Introduction;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Introduction\Repositories\Caches\CategoriesIntroductionCacheDecorator;
use Botble\Introduction\Repositories\Caches\Faq\FaqCategoriesCacheDecorator;
use Botble\Introduction\Repositories\Caches\Faq\FaqIntroductionCacheDecorator;
use Botble\Introduction\Repositories\Caches\IntroductionCacheDecorator;
use Botble\Introduction\Repositories\Caches\Notices\NoticesIntroductionCacheDecorator;
use Botble\Introduction\Repositories\Eloquent\CategoriesIntroductionRepository;
use Botble\Introduction\Repositories\Eloquent\Faq\FaqCategoriesRepository;
use Botble\Introduction\Repositories\Eloquent\Faq\FaqIntroductionRepository;
use Botble\Introduction\Repositories\Eloquent\IntroductionRepository;
use Botble\Introduction\Repositories\Eloquent\Notices\NoticesIntroductionRepository;
use Botble\Introduction\Repositories\Interfaces\CategoriesIntroductionInterface;
use Botble\Introduction\Repositories\Interfaces\Faq\FaqCategoriesInterface;
use Botble\Introduction\Repositories\Interfaces\Faq\FaqIntroductionInterface;
use Botble\Introduction\Repositories\Interfaces\IntroductionInterface;
use Botble\Introduction\Repositories\Interfaces\Notices\NoticesIntroductionInterface;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class IntroductionServiceProvider extends ServiceProvider
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
        //Introduction
        $this->app->singleton(IntroductionInterface::class, function () {
            return new IntroductionCacheDecorator(new IntroductionRepository(new Introduction));
        });
        $this->app->singleton(CategoriesIntroductionInterface::class, function () {
            return new CategoriesIntroductionCacheDecorator(new CategoriesIntroductionRepository(new CategoriesIntroduction));
        });

        //Notices
        $this->app->singleton(NoticesIntroductionInterface::class, function () {
            return new NoticesIntroductionCacheDecorator(new NoticesIntroductionRepository(new NoticesIntroduction));
        });

        //Faq
        $this->app->singleton(FaqIntroductionInterface::class, function () {
            return new FaqIntroductionCacheDecorator(new FaqIntroductionRepository(new FaqIntroduction));
        });
        $this->app->singleton(FaqCategoriesInterface::class, function () {
            return new FaqCategoriesCacheDecorator(new FaqCategoriesRepository(new FaqCategories));
        });
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setNamespace('plugins/introduction')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-introduction',
                'priority' => 6,
                'parent_id' => null,
                'name' => '이화이언 소개 관리',
                'icon' => 'fab fa-youtube',
                'url' => null,
                'permissions' => ['introduction.list'],
            ])
                ->registerItem([
                    'id' => 'cms-plugins-introduction-2',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-introduction',
                    'name' => '소개 관리',
                    'icon' => null,
                    'url' => route('introduction.list'),
                    'permissions' => ['introduction.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-notices-introduction',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-introduction',
                    'name' => '공지사항 관리',
                    'icon' => null,
                    'url' => route('introduction.notices.list'),
                    'permissions' => ['introduction.notices.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-faq-introduction',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-introduction',
                    'name' => 'FAQ 관리',
                    'icon' => null,
                    'url' => route('introduction.faq.list'),
                    'permissions' => ['introduction.faq.list'],
                ]);
        });
    }
}
