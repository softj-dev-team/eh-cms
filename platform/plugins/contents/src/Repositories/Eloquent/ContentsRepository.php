<?php

namespace Botble\Contents\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Contents\Repositories\Interfaces\ContentsInterface;

class ContentsRepository extends RepositoriesAbstract implements ContentsInterface
{
    /**
     * @var string
     */
    protected $screen = CONTENTS_MODULE_SCREEN_NAME;
}
