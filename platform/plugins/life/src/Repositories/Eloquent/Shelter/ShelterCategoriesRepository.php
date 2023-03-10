<?php

namespace Botble\Life\Repositories\Eloquent\Shelter;

use Botble\Life\Repositories\Interfaces\Shelter\ShelterCategoriesInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class ShelterCategoriesRepository extends RepositoriesAbstract implements ShelterCategoriesInterface
{
     /**
     * @var string
     */
    protected $screen = SHELTER_CATEGORIES_MODULE_SCREEN_NAME;
}
