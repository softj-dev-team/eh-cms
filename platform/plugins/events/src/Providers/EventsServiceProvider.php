<?php

namespace Botble\Events\Providers;

use Botble\Events\Models\Events;
use Botble\Events\Models\Comments;
use Illuminate\Support\ServiceProvider;
use Botble\Events\Repositories\Caches\EventsCacheDecorator;
use Botble\Events\Repositories\Caches\CommentsCacheDecorator;
use Botble\Events\Repositories\Eloquent\EventsRepository;
use Botble\Events\Repositories\Eloquent\CommentsRepository;
use Botble\Events\Repositories\Interfaces\EventsInterface;
use Botble\Events\Repositories\Interfaces\CommentsInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Botble\Events\Models\CategoryEvents;
use Botble\Events\Models\CommentsEventsCmt;
use Botble\Events\Models\EventsCmt;
use Botble\Events\Repositories\Eloquent\CategoryEventsRepository;
use Botble\Events\Repositories\Caches\CategoryEventsCacheDecorator;
use Botble\Events\Repositories\Caches\CommentsEventsCmtCacheDecorator;
use Botble\Events\Repositories\Caches\EventsCmtCacheDecorator;
use Botble\Events\Repositories\Eloquent\CommentsEventsCmtRepository;
use Botble\Events\Repositories\Eloquent\EventsCmtRepository;
use Botble\Events\Repositories\Interfaces\CategoryEventsInterface;
use Botble\Events\Repositories\Interfaces\CommentsEventsCmtInterface;
use Botble\Events\Repositories\Interfaces\EventsCmtInterface;

class EventsServiceProvider extends ServiceProvider
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

        $this->app->singleton(EventsInterface::class, function () {
            return new EventsCacheDecorator(new EventsRepository(new Events));
        });

        $this->app->singleton(CommentsInterface::class, function () {
            return new CommentsCacheDecorator(new CommentsRepository(new Comments));
        });

        $this->app->singleton(CategoryEventsInterface::class, function () {
            return new CategoryEventsCacheDecorator(new CategoryEventsRepository(new CategoryEvents));
        });

        $this->app->singleton(EventsCmtInterface::class, function () {
            return new EventsCmtCacheDecorator(new EventsCmtRepository(new EventsCmt));
        });
        $this->app->singleton(CommentsEventsCmtInterface::class, function () {
            return new CommentsEventsCmtCacheDecorator(new CommentsEventsCmtRepository(new CommentsEventsCmt));
        });


        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {

        $this->setNamespace('plugins/events')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->publishPublicFolder()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-events-menu',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => '이벤트 관리',
                'icon'        => 'fa fa-list',
                'url'         => null,
                'permissions' => ['events.list'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-events',
                'priority'    => 1,
                'parent_id'   => 'cms-plugins-events-menu',
                'name'        => '이벤트',
                'icon'        => null,
                'url'         => route('events.list'),
                'permissions' => ['events.list'],
            ])
            ->registerItem([
                'id' => 'cms-plugins-events-cmt',
                'priority' => 2,
                'parent_id' => 'cms-plugins-events-menu',
                'name' => '이벤트 후기',
                'icon' => null,
                'url' => route('events.cmt.list'),
                'permissions' => ['events.cmt.list'],
            ]);
        });

    }
}
