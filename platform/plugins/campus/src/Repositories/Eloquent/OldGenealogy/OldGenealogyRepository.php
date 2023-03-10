<?php

namespace Botble\Campus\Repositories\Eloquent\OldGenealogy;

use Botble\Campus\Repositories\Interfaces\OldGenealogy\OldGenealogyInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class OldGenealogyRepository extends RepositoriesAbstract implements OldGenealogyInterface
{
    /**
     * @var string
     */
    protected $screen = OLD_GENEALOGY_MODULE_SCREEN_NAME;
}
