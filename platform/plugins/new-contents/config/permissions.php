<?php

return [
    [
        'name' => 'NewContents',
        'flag' => 'new_contents.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'new_contents.create',
        'parent_flag' => 'new_contents.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'new_contents.edit',
        'parent_flag' => 'new_contents.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'new_contents.delete',
        'parent_flag' => 'new_contents.list',
    ],
        //Categories
        [
            'name' => 'Categories',
            'flag' => 'new_contents.categories.list',
            'parent_flag' => 'new_contents.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'new_contents.categories.create',
            'parent_flag' => 'new_contents.categories.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'new_contents.categories.edit',
            'parent_flag' => 'new_contents.categorieslist',
        ],
        [
            'name' => 'Delete',
            'flag' => 'new_contents.categories.delete',
            'parent_flag' => 'new_contents.categories.list',
        ],
        //Comments
        [
            'name' => 'Comments',
            'flag' => 'new_contents.comments.list',
            'parent_flag' => 'new_contents.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'new_contents.comments.create',
            'parent_flag' => 'new_contents.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'new_contents.comments.delete',
            'parent_flag' => 'new_contents.comments.list',
        ],
    //---------------FE-------------------------
    [
        'name' => '7. NewContentsFE',
        'flag' => 'newContentsFE.list',//index , detail , comments and list
    ],
    [
        'name' => 'Create',
        'flag' => 'newContentsFE.create',
        'parent_flag' => 'newContentsFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'newContentsFE.edit',
        'parent_flag' => 'newContentsFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'newContentsFE.delete',
        'parent_flag' => 'newContentsFE.list',
    ],
];
