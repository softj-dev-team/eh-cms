<?php

namespace Botble\Campus\Repositories\Eloquent\Notices;

use Botble\Campus\Repositories\Interfaces\Notices\NoticesCampusInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class NoticesCampusRepository extends RepositoriesAbstract implements NoticesCampusInterface
{
    /**
     * @var string
     */
    protected $screen = NOTICES_CAMPUS_MODULE_SCREEN_NAME;
}
