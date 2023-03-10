<?php

namespace Botble\Introduction\Repositories\Eloquent\Faq;

use Botble\Introduction\Repositories\Interfaces\Faq\FaqIntroductionInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class FaqIntroductionRepository extends RepositoriesAbstract implements FaqIntroductionInterface
{
    /**
     * @var string
     */
    protected $screen = FAQ_INTRODUCTION_MODULE_SCREEN_NAME;
}
