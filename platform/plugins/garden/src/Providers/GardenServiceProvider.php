<?php

namespace Botble\Garden\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Garden\Models\CategoriesGarden;
use Botble\Garden\Models\CommentsGarden;
use Botble\Garden\Models\Description\DescriptionGarden;
use Botble\Garden\Models\Egarden\CommentsEgarden;
use Botble\Garden\Models\Egarden\Egarden;
use Botble\Garden\Models\Egarden\Room;
use Botble\Garden\Models\Ewha;
use Botble\Garden\Models\Garden;
use Botble\Garden\Models\Notices\NoticesGarden;
use Botble\Garden\Repositories\Caches\CategoriesGardenCacheDecorator;
use Botble\Garden\Repositories\Caches\CommentsGardenCacheDecorator;
use Botble\Garden\Repositories\Caches\Description\DescriptionGardenCacheDecorator;
use Botble\Garden\Repositories\Caches\Egarden\CommentsEgardenCacheDecorator;
use Botble\Garden\Repositories\Caches\Egarden\EgardenCacheDecorator;
use Botble\Garden\Repositories\Caches\Egarden\RoomCacheDecorator;
use Botble\Garden\Repositories\Caches\EwhaCacheDecorator;
use Botble\Garden\Repositories\Caches\GardenCacheDecorator;
use Botble\Garden\Repositories\Caches\Notices\NoticesGardenCacheDecorator;
use Botble\Garden\Repositories\Eloquent\CategoriesGardenRepository;
use Botble\Garden\Repositories\Eloquent\CommentsGardenRepository;
use Botble\Garden\Repositories\Eloquent\Description\DescriptionGardenRepository;
use Botble\Garden\Repositories\Eloquent\Egarden\CommentsEgardenRepository;
use Botble\Garden\Repositories\Eloquent\Egarden\EgardenRepository;
use Botble\Garden\Repositories\Eloquent\Egarden\RoomRepository;
use Botble\Garden\Repositories\Eloquent\EwhaRepository;
use Botble\Garden\Repositories\Eloquent\GardenRepository;
use Botble\Garden\Repositories\Eloquent\Notices\NoticesGardenRepository;
use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;
use Botble\Garden\Repositories\Interfaces\CommentsGardenInterface;
use Botble\Garden\Repositories\Interfaces\Description\DescriptionGardenInterface;
use Botble\Garden\Repositories\Interfaces\Egarden\CommentsEgardenInterface;
use Botble\Garden\Repositories\Interfaces\Egarden\EgardenInterface;
use Botble\Garden\Repositories\Interfaces\Egarden\RoomInterface;
use Botble\Garden\Repositories\Interfaces\EwhaInterface;
use Botble\Garden\Repositories\Interfaces\GardenInterface;
use Botble\Garden\Repositories\Interfaces\Notices\NoticesGardenInterface;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class GardenServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @author Sang Nguyen
     */
    public function register() {
        // Garden
        $this->app->singleton(GardenInterface::class, function () {
            return new GardenCacheDecorator(new GardenRepository(new Garden));
        });

        $this->app->singleton(CategoriesGardenInterface::class, function () {
            return new CategoriesGardenCacheDecorator(new CategoriesGardenRepository(new CategoriesGarden));
        });

        $this->app->singleton(CommentsGardenInterface::class, function () {
            return new CommentsGardenCacheDecorator(new CommentsGardenRepository(new CommentsGarden));
        });

        // Ewha
        $this->app->singleton(EwhaInterface::class, function () {
            return new EwhaCacheDecorator(new EwhaRepository(new Ewha));
        });

        // Notice
        $this->app->singleton(NoticesGardenInterface::class, function () {
            return new NoticesGardenCacheDecorator(new NoticesGardenRepository(new NoticesGarden));
        });

        // Description
        $this->app->singleton(DescriptionGardenInterface::class, function () {
            return new DescriptionGardenCacheDecorator(new DescriptionGardenRepository(new DescriptionGarden));
        });

        // Egarden
        $this->app->singleton(EgardenInterface::class, function () {
            return new EgardenCacheDecorator(new EgardenRepository(new Egarden));
        });
        $this->app->singleton(RoomInterface::class, function () {
            return new RoomCacheDecorator(new RoomRepository(new Room));
        });
        $this->app->singleton(CommentsEgardenInterface::class, function () {
            return new CommentsEgardenCacheDecorator(new CommentsEgardenRepository(new CommentsEgarden));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot() {
        $this->setNamespace('plugins/garden')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishPublicFolder()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-plugins-garden',
                    'priority' => 5,
                    'parent_id' => null,
                    'name' => '비밀의 화원 관리',
                    'icon' => 'fab fa-asymmetrik',
                    'url' => null,
                    'permissions' => ['garden.list'],
                ])->registerItem([
                    'id' => 'cms-plugins-garden-list',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-garden',
                    'name' => '화원 목록',
                    'icon' => null,
                    'url' => route('garden.list'),
                    'permissions' => ['garden.list'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-garden-pw',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-garden',
                    'name' => '비밀단어 관리',
                    'icon' => null,
                    'url' => route('garden.manage_pw.list'),
                    'permissions' => ['garden.manage_pw.list'],
                ])->registerItem([
                    'id' => 'cms-plugins-egarden',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-garden',
                    'name' => '이화원',
                    'icon' => null,
                    'url' => route('garden.egarden.list'),
                    'permissions' => ['garden.egarden.list'],
                ])->registerItem([
                    'id' => 'cms-plugins-notices-garden',
                    'priority' => 4,
                    'parent_id' => 'cms-plugins-garden',
                    'name' => '비밀의화원 공지 관리',
                    'icon' => null,
                    'url' => route('garden.notices.list'),
                    'permissions' => ['garden.notices.list'],
                ])->registerItem([
                    'id' => 'cms-plugins-description-garden',
                    'priority' => 5,
                    'parent_id' => 'cms-plugins-garden',
                    'name' => '비밀의화원 소개 관리',
                    'icon' => null,
                    'url' => route('garden.description.list'),
                    'permissions' => ['garden.description.list'],
                ])->registerItem([
                    'id' => 'cms-plugins-ewha',
                    'priority' => 6,
                    'parent_id' => 'cms-plugins-garden',
                    'name' => "지난 화원",
                    'icon' => null,
                    'url' => route('ewha.list'),
                    'permissions' => ['garden.list'],
                ]);
        });
    }
}
