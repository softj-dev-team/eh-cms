<?php

namespace Botble\Life\Repositories\Eloquent\Ads;

use Botble\Life\Repositories\Interfaces\Ads\AdsCategoriesInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class AdsCategoriesRepository extends RepositoriesAbstract implements AdsCategoriesInterface
{
    /**
     * @var string
     */
    protected $screen = ADS_CATEGORIES_MODULE_SCREEN_NAME;
}
