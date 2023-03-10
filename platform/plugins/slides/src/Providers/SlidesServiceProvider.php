<?php

namespace Botble\Slides\Providers;

use Botble\Slides\Models\Slides;
use Illuminate\Support\ServiceProvider;
use Botble\Slides\Repositories\Caches\SlidesCacheDecorator;
use Botble\Slides\Repositories\Eloquent\SlidesRepository;
use Botble\Slides\Repositories\Interfaces\SlidesInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class SlidesServiceProvider extends ServiceProvider
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
        $this->app->singleton(SlidesInterface::class, function () {
            return new SlidesCacheDecorator(new SlidesRepository(new Slides));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setNamespace('plugins/slides')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->publishPublicFolder()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes();

        \Gallery::registerModule(SLIDES_MODULE_SCREEN_NAME);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-slides',
                'priority'    => 7,
                'parent_id'   => null,
                'name'        => '슬라이드 관리',
                'icon'        => 'fas fa-sliders-h',
                'url'         => route('slides.list'),
                'permissions' => ['slides.list'],
            ]);
        });


    }
}
