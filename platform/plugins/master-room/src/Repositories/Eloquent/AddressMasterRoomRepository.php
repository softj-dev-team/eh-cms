<?php

namespace Botble\MasterRoom\Repositories\Eloquent;

use Botble\MasterRoom\Repositories\Interfaces\AddressMasterRoomInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class AddressMasterRoomRepository extends RepositoriesAbstract implements AddressMasterRoomInterface
{
    /**
     * @var string
     */
    protected $screen = ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME;
}
