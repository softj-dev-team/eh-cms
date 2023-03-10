<?php

namespace Botble\Campus\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Campus\Repositories\Interfaces\CampusInterface;

class CampusRepository extends RepositoriesAbstract implements CampusInterface
{
    /**
     * @var string
     */
    protected $screen = CAMPUS_MODULE_SCREEN_NAME;
}
