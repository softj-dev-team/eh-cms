<?php

namespace Botble\Introduction\Repositories\Eloquent;

use Botble\Introduction\Repositories\Interfaces\CategoriesIntroductionInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CategoriesIntroductionRepository extends RepositoriesAbstract implements CategoriesIntroductionInterface
{
    /**
     * @var string
     */
    protected $screen = CATEGORIES_INTRODUCTION_MODULE_SCREEN_NAME;
}
