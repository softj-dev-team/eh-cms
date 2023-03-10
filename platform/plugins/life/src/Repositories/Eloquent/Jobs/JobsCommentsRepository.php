<?php

namespace Botble\Life\Repositories\Eloquent\Jobs;

use Botble\Life\Repositories\Interfaces\Jobs\JobsCommentsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class JobsCommentsRepository extends RepositoriesAbstract implements JobsCommentsInterface
{
    /**
     * @var string
     */
    protected $screen = JOBS_COMMENTS_MODULE_SCREEN_NAME;
}
