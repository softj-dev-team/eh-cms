<?php

namespace Botble\Life\Repositories\Caches;

use Botble\Life\Repositories\Interfaces\FlareCategoriesInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class FlareCategoriesCacheDecorator extends CacheAbstractDecorator implements FlareCategoriesInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAllCategoriesWithChildren(array $condition = [], array $with = [], array $select = ['*'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
