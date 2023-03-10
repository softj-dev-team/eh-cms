<?php

namespace Botble\NewContents\Providers;

use Botble\NewContents\Models\NewContents;
use Illuminate\Support\ServiceProvider;
use Botble\NewContents\Repositories\Caches\NewContentsCacheDecorator;
use Botble\NewContents\Repositories\Eloquent\NewContentsRepository;
use Botble\NewContents\Repositories\Interfaces\NewContentsInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\NewContents\Models\CategoriesNewContents;
use Botble\NewContents\Models\CommentsNewContents;
use Botble\NewContents\Repositories\Caches\CategoriesNewContentsCacheDecorator;
use Botble\NewContents\Repositories\Caches\CommentsNewContentsCacheDecorator;
use Botble\NewContents\Repositories\Eloquent\CategoriesNewContentsRepository;
use Botble\NewContents\Repositories\Eloquent\CommentsNewContentsRepository;
use Botble\NewContents\Repositories\Interfaces\CategoriesNewContentsInterface;
use Botble\NewContents\Repositories\Interfaces\CommentsNewContentsInterface;
use Illuminate\Routing\Events\RouteMatched;

class NewContentsServiceProvider extends ServiceProvider
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
        $this->app->singleton(NewContentsInterface::class, function () {
            return new NewContentsCacheDecorator(new NewContentsRepository(new NewContents));
        });
        $this->app->singleton(CategoriesNewContentsInterface::class, function () {
            return new CategoriesNewContentsCacheDecorator(new CategoriesNewContentsRepository(new CategoriesNewContents));
        });
        $this->app->singleton(CommentsNewContentsInterface::class, function () {
            return new CommentsNewContentsCacheDecorator(new CommentsNewContentsRepository(new CommentsNewContents));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setNamespace('plugins/new-contents')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishPublicFolder()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-new_contents',
                'priority'    => 7,
                'parent_id'   => null,
                'name'        => '새컨텐츠 관리',
                'icon'        => 'fas fa-paw',
                'url'         => route('new_contents.list'),
                'permissions' => ['new_contents.list'],
            ]);
        });
    }
}
