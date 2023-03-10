<?php

return [
    //------------BE--------------------------------------
    //Garden
    [
        'name' => 'Garden',
        'flag' => 'garden.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'garden.create',
        'parent_flag' => 'garden.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'garden.edit',
        'parent_flag' => 'garden.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'garden.delete',
        'parent_flag' => 'garden.list',
    ],
    //Garden categories
    [
        'name' => 'Categories Garden',
        'flag' => 'garden.categories.list',
        'parent_flag' => 'garden.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'garden.categories.create',
        'parent_flag' => 'garden.categories.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'garden.categories.edit',
        'parent_flag' => 'garden.categories.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'garden.categories.delete',
        'parent_flag' => 'garden.categories.list',
    ],

    //Garden comments
    [
        'name' => 'Comemnts Garden',
        'flag' => 'garden.comments.list',
        'parent_flag' => 'garden.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'garden.comments.create',
        'parent_flag' => 'garden.comments.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'garden.comments.delete',
        'parent_flag' => 'garden.comments.list',
    ],
    //Garden password
    [
        'name' => 'Reset password',
        'flag' => 'garden.password.reset',
        'parent_flag' => 'garden.list',
    ],
    [
        'name' => 'Edit password',
        'flag' => 'garden.password.edit',
        'parent_flag' => 'garden.password.reset',
    ],
    //Notices
    [
        'name' => 'Notices',
        'flag' => 'garden.notices.list',
        'parent_flag' => 'garden.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'garden.notices.create',
        'parent_flag' => 'garden.notices.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'garden.notices.edit',
        'parent_flag' => 'garden.notices.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'garden.notices.delete',
        'parent_flag' => 'garden.notices.list',
    ],
    //Description
    [
        'name' => 'Description',
        'flag' => 'garden.description.list',
        'parent_flag' => 'garden.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'garden.description.create',
        'parent_flag' => 'garden.description.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'garden.description.edit',
        'parent_flag' => 'garden.description.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'garden.description.delete',
        'parent_flag' => 'garden.description.list',
    ],
    //Egarden
    [
        'name' => 'Egarden',
        'flag' => 'garden.egarden.list',
        'parent_flag' => 'garden.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'garden.egarden.create',
        'parent_flag' => 'garden.egarden.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'garden.egarden.edit',
        'parent_flag' => 'garden.egarden.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'garden.egarden.delete',
        'parent_flag' => 'garden.egarden.list',
    ],
    //Egarden room
    [
        'name' => 'Room',
        'flag' => 'garden.egarden.room.list',
        'parent_flag' => 'garden.egarden.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'garden.egarden.room.create',
        'parent_flag' => 'garden.egarden.room.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'garden.egarden.room.edit',
        'parent_flag' => 'garden.egarden.room.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'garden.egarden.room.delete',
        'parent_flag' => 'garden.egarden.room.list',
    ],
    //Egarden comments
    [
        'name' => 'Comments',
        'flag' => 'garden.egarden.comments.list',
        'parent_flag' => 'garden.egarden.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'garden.egarden.comments.create',
        'parent_flag' => 'garden.egarden.comments.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'garden.egarden.comments.delete',
        'parent_flag' => 'garden.egarden.comments.list',
    ],
    //---------------------------FE----------------------------------
    //Garden FE
    [
        'name' => '5. GardenFE',
        'flag' => 'gardenFE.list',
    ],
    [
        'name' => 'Bypass password requirement',
        'flag' => 'gardenFE.bypass_password_requirement',
        'parent_flag' => 'gardenFE.list',
    ],
    [
        'name' => 'View',
        'flag' => 'gardenFE.view',
        'parent_flag' => 'gardenFE.list',
    ],
        //past_garden,
        [
            'name' => 'Past Garden',
            'flag' => 'gardenFE.view.past_garden',
            'parent_flag' => 'gardenFE.view',
        ],
        //law_garden
        [
            'name' => 'Law Garden',
            'flag' => 'gardenFE.view.law_garden',
            'parent_flag' => 'gardenFE.view',
        ],
        //job_garden
        [
            'name' => 'Job Garden',
            'flag' => 'gardenFE.view.job_garden',
            'parent_flag' => 'gardenFE.view',
        ],
        //graduation_garden
        [
            'name' => 'Graduation Garden',
            'flag' => 'gardenFE.view.graduation_garden',
            'parent_flag' => 'gardenFE.view',
        ],
        //secret_garden
        [
            'name' => 'Secret Garden',
            'flag' => 'gardenFE.view.secret_garden',
            'parent_flag' => 'gardenFE.view',
        ],
        //sprout_garden
        [
            'name' => 'Sprout Garden',
            'flag' => 'gardenFE.view.sprout_garden',
            'parent_flag' => 'gardenFE.view',
        ],
    [
        'name' => 'Create',
        'flag' => 'gardenFE.create',
        'parent_flag' => 'gardenFE.list',
    ],
        //past_garden,
        [
            'name' => 'Past Garden',
            'flag' => 'gardenFE.create.past_garden',
            'parent_flag' => 'gardenFE.create',
        ],
        //law_garden
        [
            'name' => 'Law Garden',
            'flag' => 'gardenFE.create.law_garden',
            'parent_flag' => 'gardenFE.create',
        ],
        //job_garden
        [
            'name' => 'Job Garden',
            'flag' => 'gardenFE.create.job_garden',
            'parent_flag' => 'gardenFE.create',
        ],
        //graduation_garden
        [
            'name' => 'Graduation Garden',
            'flag' => 'gardenFE.create.graduation_garden',
            'parent_flag' => 'gardenFE.create',
        ],
        //secret_garden
        [
            'name' => 'Secret Garden',
            'flag' => 'gardenFE.create.secret_garden',
            'parent_flag' => 'gardenFE.create',
        ],
        //sprout_garden
        [
            'name' => 'Sprout Garden',
            'flag' => 'gardenFE.create.sprout_garden',
            'parent_flag' => 'gardenFE.create',
        ],
    [
        'name' => 'Edit',
        'flag' => 'gardenFE.edit',
        'parent_flag' => 'gardenFE.list',
    ],
        //past_garden,
        [
            'name' => 'Past Garden',
            'flag' => 'gardenFE.edit.past_garden',
            'parent_flag' => 'gardenFE.edit',
        ],
        //law_garden
        [
            'name' => 'Law Garden',
            'flag' => 'gardenFE.edit.law_garden',
            'parent_flag' => 'gardenFE.edit',
        ],
        //job_garden
        [
            'name' => 'Job Garden',
            'flag' => 'gardenFE.edit.job_garden',
            'parent_flag' => 'gardenFE.edit',
        ],
        //graduation_garden
        [
            'name' => 'Graduation Garden',
            'flag' => 'gardenFE.edit.graduation_garden',
            'parent_flag' => 'gardenFE.edit',
        ],
        //secret_garden
        [
            'name' => 'Secret Garden',
            'flag' => 'gardenFE.edit.secret_garden',
            'parent_flag' => 'gardenFE.edit',
        ],
        //sprout_garden
        [
            'name' => 'Sprout Garden',
            'flag' => 'gardenFE.edit.sprout_garden',
            'parent_flag' => 'gardenFE.edit',
        ],
    [
        'name' => 'Delete',
        'flag' => 'gardenFE.delete',
        'parent_flag' => 'gardenFE.list',
    ],
         //past_garden,
         [
            'name' => 'Past Garden',
            'flag' => 'gardenFE.delete.past_garden',
            'parent_flag' => 'gardenFE.delete',
        ],
        //law_garden
        [
            'name' => 'Law Garden',
            'flag' => 'gardenFE.delete.law_garden',
            'parent_flag' => 'gardenFE.delete',
        ],
        //job_garden
        [
            'name' => 'Job Garden',
            'flag' => 'gardenFE.delete.job_garden',
            'parent_flag' => 'gardenFE.delete',
        ],
        //graduation_garden
        [
            'name' => 'Graduation Garden',
            'flag' => 'gardenFE.delete.graduation_garden',
            'parent_flag' => 'gardenFE.delete',
        ],
        //secret_garden
        [
            'name' => 'Secret Garden',
            'flag' => 'gardenFE.delete.secret_garden',
            'parent_flag' => 'gardenFE.delete',
        ],
        //sprout_garden
        [
            'name' => 'Sprout Garden',
            'flag' => 'gardenFE.delete.sprout_garden',
            'parent_flag' => 'gardenFE.delete',
        ],
    [
        'name' => 'Comments',
        'flag' => 'gardenFE.comments',
        'parent_flag' => 'gardenFE.list',
    ],
        //Create
        [
            'name' => 'Create',
            'flag' => 'gardenFE.comments.create',
            'parent_flag' => 'gardenFE.comments',
        ],
            //past_garden,
            [
                'name' => 'Past Garden',
                'flag' => 'gardenFE.comments.create.past_garden',
                'parent_flag' => 'gardenFE.comments.create',
            ],
            //law_garden
            [
                'name' => 'Law Garden',
                'flag' => 'gardenFE.comments.create.law_garden',
                'parent_flag' => 'gardenFE.comments.create',
            ],
            //job_garden
            [
                'name' => 'Job Garden',
                'flag' => 'gardenFE.comments.create.job_garden',
                'parent_flag' => 'gardenFE.comments.create',
            ],
            //graduation_garden
            [
                'name' => 'Graduation Garden',
                'flag' => 'gardenFE.comments.create.graduation_garden',
                'parent_flag' => 'gardenFE.comments.create',
            ],
            //secret_garden
            [
                'name' => 'Secret Garden',
                'flag' => 'gardenFE.comments.create.secret_garden',
                'parent_flag' => 'gardenFE.comments.create',
            ],
            //sprout_garden
            [
                'name' => 'Sprout Garden',
                'flag' => 'gardenFE.comments.create.sprout_garden',
                'parent_flag' => 'gardenFE.comments.create',
            ],
        //Delete
        [
            'name' => 'Delete',
            'flag' => 'gardenFE.comments.delete',
            'parent_flag' => 'gardenFE.comments',
        ],
            //past_garden,
            [
                'name' => 'Past Garden',
                'flag' => 'gardenFE.comments.delete.past_garden',
                'parent_flag' => 'gardenFE.comments.delete',
            ],
            //law_garden
            [
                'name' => 'Law Garden',
                'flag' => 'gardenFE.comments.delete.law_garden',
                'parent_flag' => 'gardenFE.comments.delete',
            ],
            //job_garden
            [
                'name' => 'Job Garden',
                'flag' => 'gardenFE.comments.delete.job_garden',
                'parent_flag' => 'gardenFE.comments.delete',
            ],
            //graduation_garden
            [
                'name' => 'Graduation Garden',
                'flag' => 'gardenFE.comments.delete.graduation_garden',
                'parent_flag' => 'gardenFE.comments.delete',
            ],
            //secret_garden
            [
                'name' => 'Secret Garden',
                'flag' => 'gardenFE.comments.delete.secret_garden',
                'parent_flag' => 'gardenFE.comments.delete',
            ],
            //sprout_garden
            [
                'name' => 'Sprout Garden',
                'flag' => 'gardenFE.comments.delete.sprout_garden',
                'parent_flag' => 'gardenFE.comments.delete',
            ],
    //Egarden FE
    [
        'name' => 'EgardenFE',
        'flag' => 'egardenFE.list',
        'parent_flag' => 'gardenFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'egardenFE.create',
        'parent_flag' => 'egardenFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'egardenFE.edit',
        'parent_flag' => 'egardenFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'egardenFE.delete',
        'parent_flag' => 'egardenFE.list',
    ],
    [
        'name' => 'Comments',
        'flag' => 'egardenFE.comments',
        'parent_flag' => 'egardenFE.list',
    ],
        [
            'name' => 'Create',
            'flag' => 'egardenFE.comments.create',
            'parent_flag' => 'egardenFE.comments',
        ],
        [
            'name' => 'Delete',
            'flag' => 'egardenFE.comments.delete',
            'parent_flag' => 'egardenFE.comments',
        ],
    //Room Egarden
    [
        'name' => 'RoomFE',
        'flag' => 'egardenFE.room.list',
        'parent_flag' => 'egardenFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'egardenFE.room.create',
        'parent_flag' => 'egardenFE.room.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'egardenFE.room.edit',
        'parent_flag' => 'egardenFE.room.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'egardenFE.room.delete',
        'parent_flag' => 'egardenFE.room.list',
    ],
];
