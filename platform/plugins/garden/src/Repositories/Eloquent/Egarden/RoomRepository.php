<?php

namespace Botble\Garden\Repositories\Eloquent\Egarden;

use Botble\Garden\Repositories\Interfaces\Egarden\RoomInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class RoomRepository extends RepositoriesAbstract implements RoomInterface
{
    /**
     * @var string
     */
    protected $screen = ROOM_MODULE_SCREEN_NAME;
}
