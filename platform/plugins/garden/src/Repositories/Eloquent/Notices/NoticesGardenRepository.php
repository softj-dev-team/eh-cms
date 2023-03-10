<?php

namespace Botble\Garden\Repositories\Eloquent\Notices;

use Botble\Garden\Repositories\Interfaces\Notices\NoticesGardenInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class NoticesGardenRepository extends RepositoriesAbstract implements NoticesGardenInterface
{
    /**
     * @var string
     */
    protected $screen = NOTICES_GARDEN_MODULE_SCREEN_NAME;
}
