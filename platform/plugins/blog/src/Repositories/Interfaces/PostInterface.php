<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface PostInterface extends RepositoryInterface
{
    /**
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFeatured($limit = 5);

    /**
     * @param array $selected
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getListPostNonInList(array $selected = [], $limit = 7);

    /**
     * @param int|array $category_id
     * @param int $paginate
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getByCategory($category_id, $paginate = 12, $limit = 0);

    /**
     * @param int $author_id
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getByUserId($author_id, $limit = 6);

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap();

    /**
     * @param int $tag
     * @param int $paginate
     * @return mixed
     * @author Sang Nguyen
     */
    public function getByTag($tag, $paginate = 12);

    /**
     * @param int $id
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getRelated($id, $limit = 3);

    /**
     * @param int $limit
     * @param int $category_id
     * @return mixed
     * @author Sang Nguyen
     */
    public function getRecentPosts($limit = 5, $category_id = 0);

    /**
     * @param string $query
     * @param int $limit
     * @param int $paginate
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSearch($query, $limit = 10, $paginate = 10);

    /**
     * @param int $perPage
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllPosts($perPage = 12, $active = true);

    /**
     * @param int $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    public function getPopularPosts($limit, array $args = []);

    /**
     * @param \Eloquent|int $model
     * @return array
     */
    public function getRelatedCategoryIds($model);
}
