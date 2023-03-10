<?php

namespace Botble\Campus\Repositories\Eloquent\Schedule;

use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleDayInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class ScheduleDayRepository extends RepositoriesAbstract implements ScheduleDayInterface
{
    /**
     * @var string
     */
    protected $screen = SCHEDULE_DAY_MODULE_SCREEN_NAME;
}
