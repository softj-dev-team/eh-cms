<?php

namespace Botble\NewContents\Repositories\Eloquent;

use Botble\NewContents\Repositories\Interfaces\CommentsNewContentsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CommentsNewContentsRepository extends RepositoriesAbstract implements CommentsNewContentsInterface
{
    /**
     * @var string
     */
    protected $screen = COMMENTS_NEW_CONTENTS_MODULE_SCREEN_NAME;
}
