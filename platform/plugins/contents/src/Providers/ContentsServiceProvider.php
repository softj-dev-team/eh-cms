<?php

namespace Botble\Contents\Providers;

use Botble\Contents\Models\Contents;
use Illuminate\Support\ServiceProvider;
use Botble\Contents\Repositories\Caches\ContentsCacheDecorator;
use Botble\Contents\Repositories\Eloquent\ContentsRepository;
use Botble\Contents\Repositories\Interfaces\ContentsInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Contents\Models\CategoriesContents;
use Botble\Contents\Models\CommentsContents;
use Botble\Contents\Observers\ContentsObservers;
use Botble\Contents\Repositories\Caches\CategoriesContentsCacheDecorator;
use Botble\Contents\Repositories\Caches\CommentsContentsCacheDecorator;
use Botble\Contents\Repositories\Eloquent\CategoriesContentsRepository;
use Botble\Contents\Repositories\Eloquent\CommentsContentsRepository;
use Botble\Contents\Repositories\Interfaces\CategoriesContentsInterface;
use Botble\Contents\Repositories\Interfaces\CommentsContentsInterface;
use Botble\Contents\Repositories\Observers\ContentsObserver;
use Illuminate\Routing\Events\RouteMatched;

class ContentsServiceProvider extends ServiceProvider
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
        $this->app->singleton(CategoriesContentsInterface::class, function () {
            return new CategoriesContentsCacheDecorator(new CategoriesContentsRepository(new CategoriesContents));
        });
        $this->app->singleton(ContentsInterface::class, function () {
            return new ContentsCacheDecorator(new ContentsRepository(new Contents));
        });
        $this->app->singleton(CommentsContentsInterface::class, function () {
            return new CommentsContentsCacheDecorator(new CommentsContentsRepository(new CommentsContents));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setNamespace('plugins/contents')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->publishPublicFolder()
            ->loadAndPublishTranslations()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
            ->registerItem([
                'id'          => 'cms-plugins-slide-content',
                'priority'    => 6,
                'parent_id'   => null,
                'name'        => '컨텐츠 배너 관리',
                'icon'        => 'far fa-lemon',
                'url'         => route('contents.slides.list'),
                'permissions' => ['contents.slides.list'],
            ]);
        });
        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-contents',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => '컨텐츠 관리',
                'icon'        => 'fa fa-book',
                'url'         => route('contents.list'),
                'permissions' => ['contents.list'],
            ]);
        });
        Contents::observe(ContentsObserver::class);
    }
}
