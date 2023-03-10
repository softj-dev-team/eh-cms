<?php

namespace Botble\Life\Repositories\Eloquent\Shelter;

use Botble\Life\Repositories\Interfaces\Shelter\ShelterCommentsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class ShelterCommentsRepository extends RepositoriesAbstract implements ShelterCommentsInterface
{
    /**
     * @var string
     */
    protected $screen = SHELTER_COMMENTS_MODULE_SCREEN_NAME;
}
