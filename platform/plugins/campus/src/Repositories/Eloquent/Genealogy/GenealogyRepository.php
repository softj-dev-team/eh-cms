<?php

namespace Botble\Campus\Repositories\Eloquent\Genealogy;

use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class GenealogyRepository extends RepositoriesAbstract implements GenealogyInterface
{
    /**
     * @var string
     */
    protected $screen = GENEALOGY_MODULE_SCREEN_NAME;
}
