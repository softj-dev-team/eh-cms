<?php

return [
    [
        'name' => 'CampusLastday',
        'flag' => 'campus_lastday.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'campus_lastday.create',
        'parent_flag' => 'campus_lastday.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'campus_lastday.edit',
        'parent_flag' => 'campus_lastday.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'campus_lastday.delete',
        'parent_flag' => 'campus_lastday.list',
    ],
];