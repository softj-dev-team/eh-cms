<?php

namespace Botble\Campus\Repositories\Eloquent\Description;

use Botble\Campus\Repositories\Interfaces\Description\DescriptionCampusInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class DescriptionCampusRepository extends RepositoriesAbstract implements DescriptionCampusInterface
{
    /**
     * @var string
     */
    protected $screen = DESCRIPTION_CAMPUS_MODULE_SCREEN_NAME;
}
