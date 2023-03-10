<?php

namespace Botble\Garden\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Garden\Repositories\Interfaces\GardenInterface;

class GardenRepository extends RepositoriesAbstract implements GardenInterface
{
    /**
     * @var string
     */
    protected $screen = GARDEN_MODULE_SCREEN_NAME;
}
