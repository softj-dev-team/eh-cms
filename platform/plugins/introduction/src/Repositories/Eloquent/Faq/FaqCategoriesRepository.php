<?php

namespace Botble\Introduction\Repositories\Eloquent\Faq;

use Botble\Introduction\Repositories\Interfaces\Faq\FaqCategoriesInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class FaqCategoriesRepository extends RepositoriesAbstract implements FaqCategoriesInterface
{
    /**
     * @var string
     */
    protected $screen = FAQ_CATEGORIES_MODULE_SCREEN_NAME;
}
