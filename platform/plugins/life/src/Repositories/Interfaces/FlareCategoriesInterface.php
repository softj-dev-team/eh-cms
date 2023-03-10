<?php

namespace Botble\Life\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface FlareCategoriesInterface extends RepositoryInterface
{
    public function getAllCategoriesWithChildren(array $condition = [], array $with = [], array $select = ['*']);
}
