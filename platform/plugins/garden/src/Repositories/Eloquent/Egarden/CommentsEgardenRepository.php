<?php

namespace Botble\Garden\Repositories\Eloquent\Egarden;

use Botble\Garden\Repositories\Interfaces\Egarden\CommentsEgardenInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CommentsEgardenRepository extends RepositoriesAbstract implements CommentsEgardenInterface
{
    /**
     * @var string
     */
    protected $screen = COMMENTS_EGARDEN_MODULE_SCREEN_NAME;
}
