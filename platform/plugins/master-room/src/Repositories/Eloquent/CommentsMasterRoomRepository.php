<?php

namespace Botble\MasterRoom\Repositories\Eloquent;

use Botble\MasterRoom\Repositories\Interfaces\CommentsMasterRoomInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CommentsMasterRoomRepository extends RepositoriesAbstract implements CommentsMasterRoomInterface
{
    /**
     * @var string
     */
    protected $screen = COMMENTS_MASTER_ROOM_MODULE_SCREEN_NAME;
}
