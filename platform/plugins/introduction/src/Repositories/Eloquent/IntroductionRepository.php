<?php

namespace Botble\Introduction\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Introduction\Repositories\Interfaces\IntroductionInterface;

class IntroductionRepository extends RepositoriesAbstract implements IntroductionInterface
{
    /**
     * @var string
     */
    protected $screen = INTRODUCTION_MODULE_SCREEN_NAME;
}
