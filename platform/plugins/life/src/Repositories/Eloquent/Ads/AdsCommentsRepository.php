<?php

namespace Botble\Life\Repositories\Eloquent\Ads;

use Botble\Life\Repositories\Interfaces\Ads\AdsCommentsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class AdsCommentsRepository extends RepositoriesAbstract implements AdsCommentsInterface
{
    /**
     * @var string
     */
    protected $screen = ADS_COMMENTS_MODULE_SCREEN_NAME;
}
