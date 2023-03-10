<?php

namespace Botble\MasterRoom\Repositories\Eloquent;

use Botble\MasterRoom\Repositories\Interfaces\CategoriesMasterRoomInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CategoriesMasterRoomRepository extends RepositoriesAbstract implements CategoriesMasterRoomInterface
{
    /**
     * @var string
     */
    protected $screen = CATEGORIES_MASTER_ROOM_MODULE_SCREEN_NAME;
}
