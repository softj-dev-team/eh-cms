<?php

return [
    //------------BE--------------------------------------
    [
        'name' => 'Life',
        'flag' => 'life.list',
    ],
    //flare market
    [
        'name' => 'Flare',
        'flag' => 'life.flare.list',
        'parent_flag' => 'life.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'life.flare.create',
        'parent_flag' => 'life.flare.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'life.flare.edit',
        'parent_flag' => 'life.flare.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'life.flare.delete',
        'parent_flag' => 'life.flare.list',
    ],
        //flare categories
        [
            'name' => 'Flare Categories',
            'flag' => 'life.flare.categories.list',
            'parent_flag' => 'life.flare.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'life.flare.categories.create',
            'parent_flag' => 'life.flare.categories.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'life.flare.categories.edit',
            'parent_flag' => 'life.flare.categories.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'life.flare.categories.delete',
            'parent_flag' => 'life.flare.categories.list',
        ],

          //flare comments
          [
            'name' => 'Flare Comments',
            'flag' => 'life.flare.comments.list',
            'parent_flag' => 'life.flare.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'life.flare.comments.create',
            'parent_flag' => 'life.flare.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'life.flare.comments.delete',
            'parent_flag' => 'life.flare.comments.list',
        ],
    //Notices
    [
        'name' => 'Notices',
        'flag' => 'life.notices.list',
        'parent_flag' => 'life.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'life.notices.create',
        'parent_flag' => 'life.notices.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'life.notices.edit',
        'parent_flag' => 'life.notices.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'life.notices.delete',
        'parent_flag' => 'life.notices.list',
    ],

    //Description
    [
        'name' => 'Description',
        'flag' => 'life.description.list',
        'parent_flag' => 'life.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'life.description.create',
        'parent_flag' => 'life.description.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'life.description.edit',
        'parent_flag' => 'life.description.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'life.description.delete',
        'parent_flag' => 'life.description.list',
    ],

    //Jobs part time
    [
        'name' => 'Jobs Part Time',
        'flag' => 'life.jobs_part_time.list',
        'parent_flag' => 'life.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'life.jobs_part_time.create',
        'parent_flag' => 'life.jobs_part_time.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'life.jobs_part_time.edit',
        'parent_flag' => 'life.jobs_part_time.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'life.jobs_part_time.delete',
        'parent_flag' => 'life.jobs_part_time.list',
    ],
        //Jobs categories
        [
            'name' => 'Jobs Categories',
            'flag' => 'life.jobs_part_time.categories.list',
            'parent_flag' => 'life.jobs_part_time.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'life.jobs_part_time.categories.create',
            'parent_flag' => 'life.jobs_part_time.categories.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'life.jobs_part_time.categories.edit',
            'parent_flag' => 'life.jobs_part_time.categories.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'life.jobs_part_time.categories.delete',
            'parent_flag' => 'life.jobs_part_time.categories.list',
        ],

          //Jobs comments
          [
            'name' => 'Jobs Comments',
            'flag' => 'life.jobs_part_time.comments.list',
            'parent_flag' => 'life.jobs_part_time.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'life.jobs_part_time.comments.create',
            'parent_flag' => 'life.jobs_part_time.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'life.jobs_part_time.comments.delete',
            'parent_flag' => 'life.jobs_part_time.comments.list',
        ],

    //Advertisements
    [
        'name' => 'Advertisements',
        'flag' => 'life.advertisements.list',
        'parent_flag' => 'life.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'life.advertisements.create',
        'parent_flag' => 'life.advertisements.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'life.advertisements.edit',
        'parent_flag' => 'life.advertisements.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'life.advertisements.delete',
        'parent_flag' => 'life.advertisements.list',
    ],
        //Advertisements categories
        [
            'name' => 'Advertisements Categories',
            'flag' => 'life.advertisements.categories.list',
            'parent_flag' => 'life.advertisements.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'life.advertisements.categories.create',
            'parent_flag' => 'life.advertisements.categories.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'life.advertisements.categories.edit',
            'parent_flag' => 'life.advertisements.categories.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'life.advertisements.categories.delete',
            'parent_flag' => 'life.advertisements.categories.list',
        ],

          //Advertisements comments
        [
            'name' => 'Advertisements Comments',
            'flag' => 'life.advertisements.comments.list',
            'parent_flag' => 'life.advertisements.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'life.advertisements.comments.create',
            'parent_flag' => 'life.advertisements.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'life.advertisements.comments.delete',
            'parent_flag' => 'life.advertisements.comments.list',
        ],
    //Shelter
    [
        'name' => 'Shelter',
        'flag' => 'life.shelter.list',
        'parent_flag' => 'life.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'life.shelter.create',
        'parent_flag' => 'life.shelter.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'life.shelter.edit',
        'parent_flag' => 'life.shelter.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'life.shelter.delete',
        'parent_flag' => 'life.shelter.list',
    ],
        //Shelter categories
        [
            'name' => 'Shelter Categories',
            'flag' => 'life.shelter.categories.list',
            'parent_flag' => 'life.shelter.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'life.shelter.categories.create',
            'parent_flag' => 'life.shelter.categories.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'life.shelter.categories.edit',
            'parent_flag' => 'life.shelter.categories.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'life.shelter.categories.delete',
            'parent_flag' => 'life.shelter.categories.list',
        ],

          //Shelter comments
        [
            'name' => 'Shelter Comments',
            'flag' => 'life.shelter.comments.list',
            'parent_flag' => 'life.shelter.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'life.shelter.comments.create',
            'parent_flag' => 'life.shelter.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'life.shelter.comments.delete',
            'parent_flag' => 'life.shelter.comments.list',
        ],
        //Open Space
        [
            'name' => 'Open Space',
            'flag' => 'life.open.space.list',
            'parent_flag' => 'life.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'life.open.space.create',
            'parent_flag' => 'life.open.space.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'life.open.space.edit',
            'parent_flag' => 'life.open.space.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'life.open.space.delete',
            'parent_flag' => 'life.open.space.list',
        ],

            //Open Space Comments
            [
                'name' => 'Open Space Comments',
                'flag' => 'life.open.space.comments.list',
                'parent_flag' => 'life.open.space.list',
            ],
            [
                'name' => 'Create',
                'flag' => 'life.open.space.comments.create',
                'parent_flag' => 'life.open.space.comments.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'life.open.space.comments.delete',
                'parent_flag' => 'life.open.space.comments.list',
            ],

    //---------------------------FE----------------------------------
    //Life FE
    [
        'name' => '3. LifeFE',
        'flag' => 'lifeFE.list',
    ],
    //FleaMarket FE
    [
        'name' => 'Flea Market FE',
        'flag' => 'flareMarketFE.list',
        'parent_flag' => 'lifeFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'flareMarketFE.create',
        'parent_flag' => 'flareMarketFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'flareMarketFE.edit',
        'parent_flag' => 'flareMarketFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'flareMarketFE.delete',
        'parent_flag' => 'flareMarketFE.list',
    ],
    [
        'name' => 'Comment',
        'flag' => 'flareMarketFE.comment',
        'parent_flag' => 'flareMarketFE.list',
    ],
        //Create
        [
            'name' => 'Create',
            'flag' => 'flareMarketFE.comment.create',
            'parent_flag' => 'flareMarketFE.comment',
        ],
        //Delete
        [
            'name' => 'Delete',
            'flag' => 'flareMarketFE.comment.delete',
            'parent_flag' => 'flareMarketFE.comment',
        ],
    //Part Time Jobs FE
    [
        'name' => 'Part Time Jobs FE',
        'flag' => 'jobsPartTimeFE.list',
        'parent_flag' => 'lifeFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'jobsPartTimeFE.create',
        'parent_flag' => 'jobsPartTimeFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'jobsPartTimeFE.edit',
        'parent_flag' => 'jobsPartTimeFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'jobsPartTimeFE.delete',
        'parent_flag' => 'jobsPartTimeFE.list',
    ],
    [
        'name' => 'Comment',
        'flag' => 'jobsPartTimeFE.comment',
        'parent_flag' => 'jobsPartTimeFE.list',
    ],
        //Create
        [
            'name' => 'Create',
            'flag' => 'jobsPartTimeFE.comment.create',
            'parent_flag' => 'jobsPartTimeFE.comment',
        ],
        //Delete
        [
            'name' => 'Delete',
            'flag' => 'jobsPartTimeFE.comment.delete',
            'parent_flag' => 'jobsPartTimeFE.comment',
        ],

    //Advertisements FE
    [
        'name' => 'Advertisements FE',
        'flag' => 'adsFE.list',
        'parent_flag' => 'lifeFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'adsFE.create',
        'parent_flag' => 'adsFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'adsFE.edit',
        'parent_flag' => 'adsFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'adsFE.delete',
        'parent_flag' => 'adsFE.list',
    ],
    [
        'name' => 'Comment',
        'flag' => 'adsFE.comment',
        'parent_flag' => 'adsFE.list',
    ],
        //Create
        [
            'name' => 'Create',
            'flag' => 'adsFE.comment.create',
            'parent_flag' => 'adsFE.comment',
        ],
        //Delete
        [
            'name' => 'Delete',
            'flag' => 'adsFE.comment.delete',
            'parent_flag' => 'adsFE.comment',
        ],

    //Shelter FE
    [
        'name' => 'Shelter FE',
        'flag' => 'shelterFE.list',
        'parent_flag' => 'lifeFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'shelterFE.create',
        'parent_flag' => 'shelterFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'shelterFE.edit',
        'parent_flag' => 'shelterFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'shelterFE.delete',
        'parent_flag' => 'shelterFE.list',
    ],
    [
        'name' => 'Comment',
        'flag' => 'shelterFE.comment',
        'parent_flag' => 'shelterFE.list',
    ],
        //Create
        [
            'name' => 'Create',
            'flag' => 'shelterFE.comment.create',
            'parent_flag' => 'shelterFE.comment',
        ],
        //Delete
        [
            'name' => 'Delete',
            'flag' => 'shelterFE.comment.delete',
            'parent_flag' => 'shelterFE.comment',
        ],
      //Open Space FE
      [
        'name' => 'Open Space FE',
        'flag' => 'openSpaceFE.list',
        'parent_flag' => 'lifeFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'openSpaceFE.create',
        'parent_flag' => 'openSpaceFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'openSpaceFE.edit',
        'parent_flag' => 'openSpaceFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'openSpaceFE.delete',
        'parent_flag' => 'openSpaceFE.list',
    ],
    [
        'name' => 'Comment',
        'flag' => 'openSpaceFE.comment',
        'parent_flag' => 'openSpaceFE.list',
    ],
        //create
        [
            'name' => 'Create',
            'flag' => 'openSpaceFE.comment.create',
            'parent_flag' => 'openSpaceFE.comment',
        ],
        //delete
        [
            'name' => 'Delete',
            'flag' => 'openSpaceFE.comment.delete',
            'parent_flag' => 'openSpaceFE.comment',
        ],

];
