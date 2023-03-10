<?php

namespace Botble\Life\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Life\Repositories\Interfaces\LifeInterface;

class LifeRepository extends RepositoriesAbstract implements LifeInterface
{
    /**
     * @var string
     */
    protected $screen = LIFE_MODULE_SCREEN_NAME;
}
