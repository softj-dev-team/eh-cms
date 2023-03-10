<?php

return [
    [
        'name' => 'Members',
        'flag' => 'member.list',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'member.create',
        'parent_flag' => 'member.list',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'member.edit',
        'parent_flag' => 'member.list',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'member.delete',
        'parent_flag' => 'member.list',
    ],


    //--------------------FE-------------------
    [
        'name' => '8. MembersFE',
        'flag' => 'memberFE.list',
    ],
    [
        'name'        => 'Is AdminFE',
        'flag'        => 'memberFE.isAdmin',
        'parent_flag' => 'memberFE.list',
    ],
    [
        'name'        => 'Show Secret Comments',
        'flag'        => 'member.show',
        'parent_flag' => 'memberFE.list',
    ],
    // [
    //     'name'        => 'Import database',
    //     'flag'        => 'member.importDB',
    //     'parent_flag' => 'memberFE.list',
    // ],
];
