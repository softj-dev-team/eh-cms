<?php

namespace Botble\Campus\Repositories\Eloquent\StudyRoom;

use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomCommentsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class StudyRoomCommentsRepository extends RepositoriesAbstract implements StudyRoomCommentsInterface
{
    /**
     * @var string
     */
    protected $screen = STUDY_ROOM_COMMENTS_MODULE_SCREEN_NAME;
}
