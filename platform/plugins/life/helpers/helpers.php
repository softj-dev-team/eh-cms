<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\SortItemsWithChildrenHelper;
use Botble\Life\Models\FlareCategories;
use Botble\Life\Repositories\Interfaces\FlareCategoriesInterface;

if (!function_exists('get_categories_with_children')) {
    /**
     * @return array
     * @throws Exception
     */
    function get_categories_with_children()
    {
        $categories = app(FlareCategoriesInterface::class)
            ->getAllCategoriesWithChildren(['status' => BaseStatusEnum::PUBLISH], [], ['id', 'name', 'parent_id']);
        $sortHelper = app(SortItemsWithChildrenHelper::class);
        $sortHelper
            ->setChildrenProperty('child_cats')
            ->setItems($categories);

        return $sortHelper->sort();
    }
}

// if (!function_exists('get_categories_by_id')) {
//     /**
//      * @return array
//      * @throws Exception
//      */
//     function get_categories_by_id($variable)
//     {
//         $categories =[];
//        foreach ($variable as $key => $item) {
//            $temp = FlareCategories::find($item);
//            array_push($categories,$temp);
//        }

//        return   $categories ;
//     }
// }