<?php

namespace Botble\Life\Repositories\Eloquent\Shelter;

use Botble\Life\Repositories\Interfaces\Shelter\ShelterInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class ShelterRepository extends RepositoriesAbstract implements ShelterInterface
{
    /**
     * @var string
     */
    protected $screen = SHELTER_MODULE_SCREEN_NAME;
}
