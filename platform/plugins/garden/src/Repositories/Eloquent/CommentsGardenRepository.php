<?php

namespace Botble\Garden\Repositories\Eloquent;

use Botble\Garden\Repositories\Interfaces\CommentsGardenInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CommentsGardenRepository extends RepositoriesAbstract implements CommentsGardenInterface
{
    /**
     * @var string
     */
    protected $screen = COMMENTS_GARDEN_MODULE_SCREEN_NAME;
}
