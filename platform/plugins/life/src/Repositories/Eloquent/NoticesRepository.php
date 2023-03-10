<?php

namespace Botble\Life\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Life\Repositories\Interfaces\NoticesInterface;

class NoticesRepository extends RepositoriesAbstract implements NoticesInterface
{
    /**
     * @var string
     */
    protected $screen = NOTICES_MODULE_SCREEN_NAME;
}
