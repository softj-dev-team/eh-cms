<?php

return [
    //Introduction
    [
        'name' => 'Introduction',
        'flag' => 'introduction.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'introduction.create',
        'parent_flag' => 'introduction.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'introduction.edit',
        'parent_flag' => 'introduction.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'introduction.delete',
        'parent_flag' => 'introduction.list',
    ],
        //categories introduction
        [
            'name' => 'Categories',
            'flag' => 'introduction.categories.list',
            'parent_flag' => 'introduction.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'introduction.categories.create',
            'parent_flag' => 'introduction.categories.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'introduction.categories.edit',
            'parent_flag' => 'introduction.categories.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'introduction.categories.delete',
            'parent_flag' => 'introduction.categories.list',
        ],

    //Notices
    [
        'name' => 'Notices',
        'flag' => 'introduction.noties.list',
        'parent_flag' => 'introduction.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'introduction.noties.create',
        'parent_flag' => 'introduction.noties.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'introduction.noties.edit',
        'parent_flag' => 'introduction.noties.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'introduction.noties.delete',
        'parent_flag' => 'introduction.noties.list',
    ],
    //Faq
    [
        'name' => 'Faq',
        'flag' => 'introduction.faq.list',
        'parent_flag' => 'introduction.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'introduction.faq.create',
        'parent_flag' => 'introduction.faq.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'introduction.faq.edit',
        'parent_flag' => 'introduction.faq.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'introduction.faq.delete',
        'parent_flag' => 'introduction.faq.list',
    ],
        //Faq categories
        [
            'name' => 'Categories',
            'flag' => 'introduction.faq.categories.list',
            'parent_flag' => 'introduction.faq.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'introduction.faq.categories.create',
            'parent_flag' => 'introduction.faq.categories.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'introduction.faq.categories.edit',
            'parent_flag' => 'introduction.faq.categories.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'introduction.faq.categories.delete',
            'parent_flag' => 'introduction.faq.categories.list',
        ],
];
