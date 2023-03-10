<?php

namespace Botble\Campus\Repositories\Eloquent\Genealogy;

use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyCommentsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class GenealogyCommentsRepository extends RepositoriesAbstract implements GenealogyCommentsInterface
{
    /**
     * @var string
     */
    protected $screen = GENEALOGY_COMMENTS_MODULE_SCREEN_NAME;
}
