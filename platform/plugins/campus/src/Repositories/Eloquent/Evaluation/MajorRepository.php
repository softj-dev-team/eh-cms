<?php

namespace Botble\Campus\Repositories\Eloquent\Evaluation;

use Botble\Campus\Repositories\Interfaces\Evaluation\MajorInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class MajorRepository extends RepositoriesAbstract implements MajorInterface
{
    /**
     * @var string
     */
    protected $screen = MAJOR_MODULE_SCREEN_NAME;
}
