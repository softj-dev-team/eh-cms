<?php

namespace Botble\Campus\Repositories\Eloquent\Schedule;

use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleTimeInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class ScheduleTimeRepository extends RepositoriesAbstract implements ScheduleTimeInterface
{
    /**
     * @var string
     */
    protected $screen = SCHEDULE_TIME_MODULE_SCREEN_NAME;
}
