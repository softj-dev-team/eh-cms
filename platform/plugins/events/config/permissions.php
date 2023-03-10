<?php

return [
    //----------------BE-------------
    [
        'name' => '9. Events',
        'flag' => 'events.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'events.create',
        'parent_flag' => 'events.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'events.edit',
        'parent_flag' => 'events.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'events.delete',
        'parent_flag' => 'events.list',
    ],
        //Events categories
        [
            'name' => 'Events Categories',
            'flag' => 'events.category.list',
            'parent_flag' => 'events.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'events.category.create',
            'parent_flag' => 'events.category.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'events.category.edit',
            'parent_flag' => 'events.category.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'events.category.delete',
            'parent_flag' => 'events.category.list',
        ],

          //Events comments
          [
            'name' => 'Events Comments',
            'flag' => 'events.comments.list',
            'parent_flag' => 'events.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'events.comments.create',
            'parent_flag' => 'events.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'events.comments.delete',
            'parent_flag' => 'events.comments.list',
        ],
    //Event Cmt
    [
        'name' => 'Events Cmt',
        'flag' => 'events.cmt.list',
        'parent_flag' => 'events.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'events.cmt.create',
        'parent_flag' => 'events.cmt.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'events.cmt.edit',
        'parent_flag' => 'events.cmt.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'events.cmt.delete',
        'parent_flag' => 'events.cmt.list',
    ],
        //Event Cmt comments
        [
            'name' => 'Events Cmt Comments',
            'flag' => 'events.cmt.comments.list',
            'parent_flag' => 'events.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'events.cmt.comments.create',
            'parent_flag' => 'events.cmt.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'events.cmt.comments.delete',
            'parent_flag' => 'events.cmt.comments.list',
        ],
    //----------------FE--------------------
    [
        'name' => '1. EventsFE',
        'flag' => 'eventsFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'eventsFE.create',
        'parent_flag' => 'eventsFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'eventsFE.edit',
        'parent_flag' => 'eventsFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'eventsFE.delete',
        'parent_flag' => 'eventsFE.list',
    ],
    [
        'name' => 'Comment',
        'flag' => 'eventsFE.comment',
        'parent_flag' => 'eventsFE.list',
    ],
        //Create
            //Event
            [
                'name' => 'Event',
                'flag' => 'eventsFE.create.event',
                'parent_flag' => 'eventsFE.create',
            ],

            //Event Sketch
            [
                'name' => 'Event Sketch',
                'flag' => 'eventsFE.create.sketch',
                'parent_flag' => 'eventsFE.create',
            ],


            //Affliation Event
            [
                'name' => 'Affliation Event',
                'flag' => 'eventsFE.create.affliation',
                'parent_flag' => 'eventsFE.create',
            ],
            //EventComment
            [
                'name' => 'EventComment',
                'flag' => 'eventsFE.cmt.create',
                'parent_flag' => 'eventsFE.create',
            ],
       //Edit
            //Event
            [
                'name' => 'Event',
                'flag' => 'eventsFE.edit.event',
                'parent_flag' => 'eventsFE.edit',
            ],

            //Event Sketch
            [
                'name' => 'Event Sketch',
                'flag' => 'eventsFE.edit.sketch',
                'parent_flag' => 'eventsFE.edit',
            ],
            //Affliation Event
            [
                'name' => 'Affliation Event',
                'flag' => 'eventsFE.edit.affliation',
                'parent_flag' => 'eventsFE.edit',
            ],
            //EventComment
            [
                'name' => 'EventComment',
                'flag' => 'eventsFE.cmt.edit',
                'parent_flag' => 'eventsFE.edit',
            ],
        //Delete
            //Event
            [
                'name' => 'Event',
                'flag' => 'eventsFE.delete.event',
                'parent_flag' => 'eventsFE.delete',
            ],

            //Event Sketch
            [
                'name' => 'Event Sketch',
                'flag' => 'eventsFE.delete.sketch',
                'parent_flag' => 'eventsFE.delete',
            ],
            //Affliation Event
            [
                'name' => 'Affliation Event',
                'flag' => 'eventsFE.delete.affliation',
                'parent_flag' => 'eventsFE.delete',
            ],
            //EventComment
            [
                'name' => 'EventComment',
                'flag' => 'eventsFE.cmt.delete',
                'parent_flag' => 'eventsFE.delete',
            ],
    //Comment
            //Create
            [
                'name' => 'Create',
                'flag' => 'eventsFE.create.comment',
                'parent_flag' => 'eventsFE.comment',
            ],
                //Event
                [
                    'name' => 'Event',
                    'flag' => 'eventsFE.create.comment.event',
                    'parent_flag' => 'eventsFE.create.comment',
                ],

                //Event Sketch
                [
                    'name' => 'Event Sketch',
                    'flag' => 'eventsFE.create.comment.sketch',
                    'parent_flag' => 'eventsFE.create.comment',
                ],


                //Affliation Event
                [
                    'name' => 'Affliation Event',
                    'flag' => 'eventsFE.create.comment.affliation',
                    'parent_flag' => 'eventsFE.create.comment',
                ],
                //EventComment
                [
                    'name' => 'EventComment',
                    'flag' => 'eventsFE.create.comment.cmt',
                    'parent_flag' => 'eventsFE.create.comment',
                ],
            //Delete
            [
                'name' => 'Delete',
                'flag' => 'eventsFE.delete.comment',
                'parent_flag' => 'eventsFE.comment',
            ],
                //Event
                [
                    'name' => 'Event',
                    'flag' => 'eventsFE.delete.comment.event',
                    'parent_flag' => 'eventsFE.delete.comment',
                ],

                //Event Sketch
                [
                    'name' => 'Event Sketch',
                    'flag' => 'eventsFE.delete.comment.sketch',
                    'parent_flag' => 'eventsFE.delete.comment',
                ],


                //Affliation Event
                [
                    'name' => 'Affliation Event',
                    'flag' => 'eventsFE.delete.comment.affliation',
                    'parent_flag' => 'eventsFE.delete.comment',
                ],
                //EventComment
                [
                    'name' => 'EventComment',
                    'flag' => 'eventsFE.delete.comment.cmt',
                    'parent_flag' => 'eventsFE.delete.comment',
                ],

];
