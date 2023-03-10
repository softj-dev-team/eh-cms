<?php

namespace Botble\Campus\Repositories\Eloquent\StudyRoom;

use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomCategoriesInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class StudyRoomCategoriesRepository extends RepositoriesAbstract implements StudyRoomCategoriesInterface
{
     /**
     * @var string
     */
    protected $screen = STUDY_ROOM_CATEGORIES_MODULE_SCREEN_NAME;
}
