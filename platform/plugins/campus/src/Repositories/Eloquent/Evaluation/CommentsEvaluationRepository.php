<?php

namespace Botble\Campus\Repositories\Eloquent\Evaluation;

use Botble\Campus\Repositories\Interfaces\Evaluation\CommentsEvaluationInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CommentsEvaluationRepository extends RepositoriesAbstract implements CommentsEvaluationInterface
{
    /**
     * @var string
     */
    protected $screen = COMMENTS_EVALUATION_MODULE_SCREEN_NAME;
}
