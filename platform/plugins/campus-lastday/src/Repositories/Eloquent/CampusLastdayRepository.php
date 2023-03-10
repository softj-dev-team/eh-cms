<?php

namespace Botble\CampusLastday\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\CampusLastday\Repositories\Interfaces\CampusLastdayInterface;

class CampusLastdayRepository extends RepositoriesAbstract implements CampusLastdayInterface
{
    /**
     * @var string
     */
    protected $screen = CAMPUS_LASTDAY_MODULE_SCREEN_NAME;
}
