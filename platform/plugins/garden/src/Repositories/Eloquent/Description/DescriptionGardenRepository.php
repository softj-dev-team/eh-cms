<?php

namespace Botble\Garden\Repositories\Eloquent\Description;

use Botble\Garden\Repositories\Interfaces\Description\DescriptionGardenInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class DescriptionGardenRepository extends RepositoriesAbstract implements DescriptionGardenInterface
{
    /**
     * @var string
     */
    protected $screen = DESCRIPTION_GARDEN_MODULE_SCREEN_NAME;
}
