<?php

namespace Botble\Garden\Repositories\Eloquent\Egarden;

use Botble\Garden\Repositories\Interfaces\Egarden\EgardenInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class EgardenRepository extends RepositoriesAbstract implements EgardenInterface
{
    /**
     * @var string
     */
    protected $screen = EGARDEN_MODULE_SCREEN_NAME;
}
