<?php

namespace Botble\Campus\Repositories\Eloquent\Schedule;

use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleTimeLineInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class ScheduleTimeLineRepository extends RepositoriesAbstract implements ScheduleTimeLineInterface
{
    /**
     * @var string
     */
    protected $screen = SCHEDULE_TIMELINE_MODULE_SCREEN_NAME;
}
