<?php

namespace Botble\Life\Repositories\Eloquent;

use Botble\Life\Repositories\Interfaces\FlareCategoriesInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class FlareCategoriesRepository extends RepositoriesAbstract implements FlareCategoriesInterface
{
    /**
     * @var string
     */
    protected $screen = FLARE_CATEGORIES_MODULE_SCREEN_NAME;

    public function getAllCategoriesWithChildren(array $condition = [], array $with = [], array $select = ['*'])
    {
        $data = $this->model
            ->where($condition)
            ->with($with)
            ->select($select);

        return $this->applyBeforeExecuteQuery($data, $this->screen)->get();
    }
}
