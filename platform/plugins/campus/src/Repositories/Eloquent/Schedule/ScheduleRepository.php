<?php

namespace Botble\Campus\Repositories\Eloquent\Schedule;

use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class ScheduleRepository extends RepositoriesAbstract implements ScheduleInterface
{
    /**
     * @var string
     */
    protected $screen = SCHEDULE_MODULE_SCREEN_NAME;
}
