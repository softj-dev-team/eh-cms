<?php

namespace Botble\Life\Repositories\Eloquent\Jobs;

use Botble\Life\Repositories\Interfaces\Jobs\JobsPartTimeInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class JobsPartTimeRepository extends RepositoriesAbstract implements JobsPartTimeInterface
{
    /**
     * @var string
     */
    protected $screen = JOBS_PART_TIME_MODULE_SCREEN_NAME;
}
