<?php

return [
    [
        'name' => 'Slides',
        'flag' => 'slides.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'slides.create',
        'parent_flag' => 'slides.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'slides.edit',
        'parent_flag' => 'slides.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'slides.delete',
        'parent_flag' => 'slides.list',
    ],
];