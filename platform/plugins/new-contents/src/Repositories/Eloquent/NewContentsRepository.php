<?php

namespace Botble\NewContents\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\NewContents\Repositories\Interfaces\NewContentsInterface;

class NewContentsRepository extends RepositoriesAbstract implements NewContentsInterface
{
    /**
     * @var string
     */
    protected $screen = NEW_CONTENTS_MODULE_SCREEN_NAME;
}
