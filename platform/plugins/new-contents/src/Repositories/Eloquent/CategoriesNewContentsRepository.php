<?php

namespace Botble\NewContents\Repositories\Eloquent;

use Botble\NewContents\Repositories\Interfaces\CategoriesNewContentsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CategoriesNewContentsRepository extends RepositoriesAbstract implements CategoriesNewContentsInterface
{
    /**
     * @var string
     */
    protected $screen = CATEGORIES_NEW_CONTENTS_MODULE_SCREEN_NAME;
}
