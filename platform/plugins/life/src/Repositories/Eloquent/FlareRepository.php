<?php

namespace Botble\Life\Repositories\Eloquent;

use Botble\Life\Repositories\Interfaces\FlareInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class FlareRepository extends RepositoriesAbstract implements FlareInterface
{
    /**
     * @var string
     */
    protected $screen = FLARE_MODULE_SCREEN_NAME;
}
