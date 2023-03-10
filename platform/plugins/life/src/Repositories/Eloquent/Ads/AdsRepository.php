<?php

namespace Botble\Life\Repositories\Eloquent\Ads;

use Botble\Life\Repositories\Interfaces\Ads\AdsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class AdsRepository extends RepositoriesAbstract implements AdsInterface
{
    /**
     * @var string
     */
    protected $screen = ADS_MODULE_SCREEN_NAME;
}
