<?php

namespace Botble\Report\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Report\Repositories\Interfaces\ReportInterface;

class ReportRepository extends RepositoriesAbstract implements ReportInterface
{
    /**
     * @var string
     */
    protected $screen = REPORT_MODULE_SCREEN_NAME;
}
