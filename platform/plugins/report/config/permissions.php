<?php

return [
    [
        'name' => 'Report',
        'flag' => 'report.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'report.create',
        'parent_flag' => 'report.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'report.edit',
        'parent_flag' => 'report.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'report.delete',
        'parent_flag' => 'report.list',
    ],
];