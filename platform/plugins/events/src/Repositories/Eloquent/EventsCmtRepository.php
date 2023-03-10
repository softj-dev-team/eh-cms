<?php

namespace Botble\Events\Repositories\Eloquent;

use Botble\Events\Repositories\Interfaces\EventsCmtInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class EventsCmtRepository extends RepositoriesAbstract implements EventsCmtInterface
{
    /**
     * @var string
     */
    protected $screen = EVENTS_CMT_MODULE_SCREEN_NAME;

}
