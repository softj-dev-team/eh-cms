<?php

namespace Botble\Life\Repositories\Eloquent\OpenSpace;

use Botble\Life\Repositories\Interfaces\OpenSpace\OpenSpaceInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class OpenSpaceRepository extends RepositoriesAbstract implements OpenSpaceInterface
{
    /**
     * @var string
     */
    protected $screen = OPEN_SPACE_MODULE_SCREEN_NAME;
}
