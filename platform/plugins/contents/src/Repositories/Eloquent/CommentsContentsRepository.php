<?php

namespace Botble\Contents\Repositories\Eloquent;

use Botble\Contents\Repositories\Interfaces\CommentsContentsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CommentsContentsRepository extends RepositoriesAbstract implements CommentsContentsInterface
{
    /**
     * @var string
     */
    protected $screen = COMMENTS_CONTENTS_MODULE_SCREEN_NAME;
}
