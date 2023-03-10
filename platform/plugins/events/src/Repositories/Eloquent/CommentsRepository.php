<?php

namespace Botble\Events\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Events\Repositories\Interfaces\CommentsInterface;

class CommentsRepository extends RepositoriesAbstract implements CommentsInterface
{
    /**
     * @var string
     */
    protected $screen = COMMENTS_MODULE_SCREEN_NAME;
}
