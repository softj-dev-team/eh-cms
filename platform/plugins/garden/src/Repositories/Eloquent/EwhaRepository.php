<?php

namespace Botble\Garden\Repositories\Eloquent;

use Botble\Garden\Repositories\Interfaces\EwhaInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class EwhaRepository extends RepositoriesAbstract implements EwhaInterface
{
    /**
     * @var string
     */
    protected $screen = EWHA_MODULE_SCREEN_NAME;
}
