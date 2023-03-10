<?php

namespace Botble\Life\Repositories\Eloquent\OpenSpace;

use Botble\Life\Repositories\Interfaces\OpenSpace\OpenSpaceCommentsInterface;
use Botble\Life\Repositories\Interfaces\OpenSpace\OpenSpaceInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class OpenSpaceCommentsRepository extends RepositoriesAbstract implements OpenSpaceCommentsInterface
{
    /**
     * @var string
     */
    protected $screen = OPEN_SPACE_COMMENTS_MODULE_SCREEN_NAME;
}
