<?php

namespace Botble\Campus\Repositories\Eloquent\Schedule;

use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleFilterInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class ScheduleFilterRepository extends RepositoriesAbstract implements ScheduleFilterInterface
{
    /**
     * @var string
     */
    protected $screen = SCHEDULE_FILTER_MODULE_SCREEN_NAME;
}
