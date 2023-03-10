<?php

namespace Botble\Events\Repositories\Eloquent;

use Botble\Events\Repositories\Interfaces\CommentsEventsCmtInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CommentsEventsCmtRepository extends RepositoriesAbstract implements CommentsEventsCmtInterface
{
    /**
     * @var string
     */
    protected $screen = COMMENTS_EVENTS_CMT_MODULE_SCREEN_NAME;
}
