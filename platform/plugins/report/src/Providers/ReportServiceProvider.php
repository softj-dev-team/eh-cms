<?php

namespace Botble\Report\Providers;

use Botble\Report\Models\Report;
use Illuminate\Support\ServiceProvider;
use Botble\Report\Repositories\Caches\ReportCacheDecorator;
use Botble\Report\Repositories\Eloquent\ReportRepository;
use Botble\Report\Repositories\Interfaces\ReportInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class ReportServiceProvider extends ServiceProvider
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
        $this->app->singleton(ReportInterface::class, function () {
            return new ReportCacheDecorator(new ReportRepository(new Report));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setNamespace('plugins/report')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-report',
                'priority'    => 23,
                'parent_id'   => null,
                'name'        => '신고 관리',
                'icon'        => 'fa fa-bug',
                'url'         => route('report.list'),
                'permissions' => ['report.list'],
            ]);
        });
    }
}
