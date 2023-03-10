<?php

namespace Botble\Campus\Repositories\Eloquent\Evaluation;

use Botble\Campus\Repositories\Interfaces\Evaluation\EvaluationInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class EvaluationRepository extends RepositoriesAbstract implements EvaluationInterface
{
    /**
     * @var string
     */
    protected $screen = EVALUATION_MODULE_SCREEN_NAME;
}
