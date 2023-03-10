<?php

return [
    [
        'name' => 'MasterRoom',
        'flag' => 'master_room.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'master_room.create',
        'parent_flag' => 'master_room.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'master_room.edit',
        'parent_flag' => 'master_room.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'master_room.delete',
        'parent_flag' => 'master_room.list',
    ],
        //Categories
        [
            'name' => 'Categories',
            'flag' => 'master_room.categories.list',
            'parent_flag' => 'master_room.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'master_room.categories.create',
            'parent_flag' => 'master_room.categories.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'master_room.categories.edit',
            'parent_flag' => 'master_room.categorieslist',
        ],
        [
            'name' => 'Delete',
            'flag' => 'master_room.categories.delete',
            'parent_flag' => 'master_room.categories.list',
        ],
        //Comments
        [
            'name' => 'Comments',
            'flag' => 'master_room.comments.list',
            'parent_flag' => 'master_room.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'master_room.comments.create',
            'parent_flag' => 'master_room.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'master_room.comments.delete',
            'parent_flag' => 'master_room.comments.list',
        ],
    //---------------FE-------------------------
    [
        'name' => '6. MasterRoomFE',
        'flag' => 'masterRoomFE.list',//index , detail , comments and list
    ],
    [
        'name' => 'Create',
        'flag' => 'masterRoomFE.create',
        'parent_flag' => 'masterRoomFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'masterRoomFE.edit',
        'parent_flag' => 'masterRoomFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'masterRoomFE.delete',
        'parent_flag' => 'masterRoomFE.list',
    ],
];
