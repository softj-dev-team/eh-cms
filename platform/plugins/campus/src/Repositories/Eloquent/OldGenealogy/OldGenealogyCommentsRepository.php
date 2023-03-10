<?php

namespace Botble\Campus\Repositories\Eloquent\OldGenealogy;

use Botble\Campus\Repositories\Interfaces\OldGenealogy\OldGenealogyCommentsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class OldGenealogyCommentsRepository extends RepositoriesAbstract implements OldGenealogyCommentsInterface
{
    /**
     * @var string
     */
    protected $screen = OLD_GENEALOGY_COMMENTS_MODULE_SCREEN_NAME;
}
