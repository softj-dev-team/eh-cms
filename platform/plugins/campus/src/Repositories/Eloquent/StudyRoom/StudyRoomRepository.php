<?php

namespace Botble\Campus\Repositories\Eloquent\StudyRoom;

use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class StudyRoomRepository extends RepositoriesAbstract implements StudyRoomInterface
{
    /**
     * @var string
     */
    protected $screen = STUDY_ROOM_MODULE_SCREEN_NAME;
}
