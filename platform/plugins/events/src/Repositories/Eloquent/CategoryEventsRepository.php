<?php

namespace Botble\Events\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Events\Repositories\Interfaces\CategoryEventsInterface;

class CategoryEventsRepository extends RepositoriesAbstract implements CategoryEventsInterface
{
    /**
     * @var string
     */
    protected $screen = CATEGORY_EVENTS_MODULE_SCREEN_NAME;
}
