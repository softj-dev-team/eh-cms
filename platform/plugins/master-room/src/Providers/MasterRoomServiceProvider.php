<?php

namespace Botble\MasterRoom\Providers;

use Botble\MasterRoom\Models\MasterRoom;
use Illuminate\Support\ServiceProvider;
use Botble\MasterRoom\Repositories\Caches\MasterRoomCacheDecorator;
use Botble\MasterRoom\Repositories\Eloquent\MasterRoomRepository;
use Botble\MasterRoom\Repositories\Interfaces\MasterRoomInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\MasterRoom\Models\AddressMasterRoom;
use Botble\MasterRoom\Models\CategoriesMasterRoom;
use Botble\MasterRoom\Models\CommentsMasterRoom;
use Botble\MasterRoom\Repositories\Caches\AddressMasterRoomCacheDecorator;
use Botble\MasterRoom\Repositories\Caches\CategoriesMasterRoomCacheDecorator;
use Botble\MasterRoom\Repositories\Caches\CommentsMasterRoomCacheDecorator;
use Botble\MasterRoom\Repositories\Eloquent\AddressMasterRoomRepository;
use Botble\MasterRoom\Repositories\Eloquent\CategoriesMasterRoomRepository;
use Botble\MasterRoom\Repositories\Eloquent\CommentsMasterRoomRepository;
use Botble\MasterRoom\Repositories\Interfaces\AddressMasterRoomInterface;
use Botble\MasterRoom\Repositories\Interfaces\CategoriesMasterRoomInterface;
use Botble\MasterRoom\Repositories\Interfaces\CommentsMasterRoomInterface;
use Illuminate\Routing\Events\RouteMatched;

class MasterRoomServiceProvider extends ServiceProvider
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
        $this->app->singleton(MasterRoomInterface::class, function () {
            return new MasterRoomCacheDecorator(new MasterRoomRepository(new MasterRoom));
        });
        $this->app->singleton(CategoriesMasterRoomInterface::class, function () {
            return new CategoriesMasterRoomCacheDecorator(new CategoriesMasterRoomRepository(new CategoriesMasterRoom));
        });
        $this->app->singleton(CommentsMasterRoomInterface::class, function () {
            return new CommentsMasterRoomCacheDecorator(new CommentsMasterRoomRepository(new CommentsMasterRoom));
        });
        $this->app->singleton(AddressMasterRoomInterface::class, function () {
            return new AddressMasterRoomCacheDecorator(new AddressMasterRoomRepository(new AddressMasterRoom));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setNamespace('plugins/master-room')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishPublicFolder()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
            ->registerItem([
                'id'          => 'cms-plugins-parent_master_room',
                'priority'    => 6,
                'parent_id'   => null,
                'name'        => '마스터룸 관리',
                'icon'        => 'fas fa-snowflake',
                'url'         =>  route('master_room.list'),
                'permissions' => ['master_room.list'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-master_room',
                'priority'    => 1,
                'parent_id'   => 'cms-plugins-parent_master_room',
                'name'        => '마스터룸 관리',
                'icon'        => null,
                'url'         =>  route('master_room.list'),
                'permissions' => ['master_room.list'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-address_master_room',
                'priority'    => 2,
                'parent_id'   => 'cms-plugins-parent_master_room',
                'name'        => '주소록',
                'icon'        => null,
                'url'         =>  route('master_room.address.list'),
                'permissions' => ['master_room.list'],
            ]);
        });
    }
}
