<?php

namespace Botble\MasterRoom\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\MasterRoom\Repositories\Interfaces\MasterRoomInterface;

class MasterRoomRepository extends RepositoriesAbstract implements MasterRoomInterface
{
    /**
     * @var string
     */
    protected $screen = MASTER_ROOM_MODULE_SCREEN_NAME;
}
