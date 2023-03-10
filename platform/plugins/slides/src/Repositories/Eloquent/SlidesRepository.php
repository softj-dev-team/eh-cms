<?php

namespace Botble\Slides\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Slides\Repositories\Interfaces\SlidesInterface;

class SlidesRepository extends RepositoriesAbstract implements SlidesInterface
{
    /**
     * @var string
     */
    protected $screen = SLIDES_MODULE_SCREEN_NAME;
}
