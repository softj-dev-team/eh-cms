<?php

namespace Botble\Life\Repositories\Eloquent;

use Botble\Life\Repositories\Interfaces\FlareCommentsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class FlareCommentsRepository extends RepositoriesAbstract implements FlareCommentsInterface
{
    /**
     * @var string
     */
    protected $screen = FLARE_COMMENTS_MODULE_SCREEN_NAME;
}
