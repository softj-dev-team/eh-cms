<?php

namespace Botble\Garden\Repositories\Eloquent;

use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CategoriesGardenRepository extends RepositoriesAbstract implements CategoriesGardenInterface
{
    /**
     * @var string
     */
    protected $screen = CATEGORIES_GARDEN_MODULE_SCREEN_NAME;
}
