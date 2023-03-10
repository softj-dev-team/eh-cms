<?php

namespace Botble\Life\Repositories\Eloquent;

use Botble\Life\Repositories\Interfaces\DescriptionInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class DescriptionRepository extends RepositoriesAbstract implements DescriptionInterface
{
    /**
     * @var string
     */
    protected $screen = DESCRIPTION_MODULE_SCREEN_NAME;
}
