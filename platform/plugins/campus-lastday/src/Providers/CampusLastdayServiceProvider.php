<?php

namespace Botble\CampusLastday\Providers;

use Botble\CampusLastday\Models\CampusLastday;
use Illuminate\Support\ServiceProvider;
use Botble\CampusLastday\Repositories\Caches\CampusLastdayCacheDecorator;
use Botble\CampusLastday\Repositories\Eloquent\CampusLastdayRepository;
use Botble\CampusLastday\Repositories\Interfaces\CampusLastdayInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class CampusLastdayServiceProvider extends ServiceProvider
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
        $this->app->singleton(CampusLastdayInterface::class, function () {
            return new CampusLastdayCacheDecorator(new CampusLastdayRepository(new CampusLastday));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setNamespace('plugins/campus-lastday')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-campus_lastday',
                'priority'    => 3,
                'parent_id'   => null,
                'name'        => '종강일관리',
                'icon'        => 'fa fa-clock',
                'url'         => route('campus_lastday.list'),
                'permissions' => ['campus_lastday.list'],
            ]);
        });
    }
}
