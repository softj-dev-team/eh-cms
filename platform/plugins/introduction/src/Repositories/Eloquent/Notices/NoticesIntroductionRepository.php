<?php

namespace Botble\Introduction\Repositories\Eloquent\Notices;

use Botble\Introduction\Repositories\Interfaces\Notices\NoticesIntroductionInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class NoticesIntroductionRepository extends RepositoriesAbstract implements NoticesIntroductionInterface
{
    /**
     * @var string
     */
    protected $screen = NOTICES_INTRODUCTION_MODULE_SCREEN_NAME;
}
