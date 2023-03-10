<?php

return [
    //------------BE-------------------
    [
        'name' => 'Contents',
        'flag' => 'contents.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'contents.create',
        'parent_flag' => 'contents.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'contents.edit',
        'parent_flag' => 'contents.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'contents.delete',
        'parent_flag' => 'contents.list',
    ],
         //Contents categories
         [
            'name' => 'Contents Categories',
            'flag' => 'contents.categories.list',
            'parent_flag' => 'contents.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'contents.categories.create',
            'parent_flag' => 'contents.categories.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'contents.categories.edit',
            'parent_flag' => 'contents.categories.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'contents.categories.delete',
            'parent_flag' => 'contents.categories.list',
        ],

          //flare comments
          [
            'name' => 'Contents Comments',
            'flag' => 'contents.comments.list',
            'parent_flag' => 'contents.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'contents.comments.create',
            'parent_flag' => 'contents.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'contents.comments.delete',
            'parent_flag' => 'contents.comments.list',
        ],
    //---------------FE-------------------------
    [
        'name' => '2. ContentsFE',
        'flag' => 'contentsFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'contentsFE.create',
        'parent_flag' => 'contentsFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'contentsFE.edit',
        'parent_flag' => 'contentsFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'contentsFE.delete',
        'parent_flag' => 'contentsFE.list',
    ],
    [
        'name' => 'Comment',
        'flag' => 'contentsFE.comment',
        'parent_flag' => 'contentsFE.list',
    ],
    //Create
        //  multiculture
        //  cultural_sympathy
        //  fine_notebook
        //  written_note
        //  contribution

        //  multiculture
        [
            'name' => 'Multiculture',
            'flag' => 'contentsFE.create.multiculture',
            'parent_flag' => 'contentsFE.create',
        ],
        //  cultural_sympathy
        [
            'name' => 'Cultural Sympathy',
            'flag' => 'contentsFE.create.cultural_sympathy',
            'parent_flag' => 'contentsFE.create',
        ],
        //  fine_notebook
        [
            'name' => 'Fine Notebook',
            'flag' => 'contentsFE.create.fine_notebook',
            'parent_flag' => 'contentsFE.create',
        ],
        //  written_note
        [
            'name' => 'Written Note',
            'flag' => 'contentsFE.create.written_note',
            'parent_flag' => 'contentsFE.create',
        ],
        //  contribution
        [
            'name' => 'Contribution',
            'flag' => 'contentsFE.create.contribution',
            'parent_flag' => 'contentsFE.create',
        ],
    //Edit
        //  multiculture
        [
            'name' => 'Multiculture',
            'flag' => 'contentsFE.edit.multiculture',
            'parent_flag' => 'contentsFE.edit',
        ],
        //  cultural_sympathy
        [
            'name' => 'Cultural Sympathy',
            'flag' => 'contentsFE.edit.cultural_sympathy',
            'parent_flag' => 'contentsFE.edit',
        ],
        //  fine_notebook
        [
            'name' => 'Fine Notebook',
            'flag' => 'contentsFE.edit.fine_notebook',
            'parent_flag' => 'contentsFE.edit',
        ],
        //  written_note
        [
            'name' => 'Written Note',
            'flag' => 'contentsFE.edit.written_note',
            'parent_flag' => 'contentsFE.edit',
        ],
        //  contribution
        [
            'name' => 'Contribution',
            'flag' => 'contentsFE.edit.contribution',
            'parent_flag' => 'contentsFE.edit',
        ],
    //Delete
        //  multiculture
        [
            'name' => 'Multiculture',
            'flag' => 'contentsFE.delete.multiculture',
            'parent_flag' => 'contentsFE.delete',
        ],
        //  cultural_sympathy
        [
            'name' => 'Cultural Sympathy',
            'flag' => 'contentsFE.delete.cultural_sympathy',
            'parent_flag' => 'contentsFE.delete',
        ],
        //  fine_notebook
        [
            'name' => 'Fine Notebook',
            'flag' => 'contentsFE.delete.fine_notebook',
            'parent_flag' => 'contentsFE.delete',
        ],
        //  written_note
        [
            'name' => 'Written Note',
            'flag' => 'contentsFE.delete.written_note',
            'parent_flag' => 'contentsFE.delete',
        ],
        //  contribution
        [
            'name' => 'Contribution',
            'flag' => 'contentsFE.delete.contribution',
            'parent_flag' => 'contentsFE.delete',
        ],
    //Comment
        //show name commened
        [
            'name' => 'Show commenter',
            'flag' => 'contentsFE.show.commenter.comment',
            'parent_flag' => 'contentsFE.comment',
        ],
        //Create
        [
            'name' => 'Create',
            'flag' => 'contentsFE.create.comment',
            'parent_flag' => 'contentsFE.comment',
        ],
            //  multiculture
            [
                'name' => 'Multiculture',
                'flag' => 'contentsFE.create.comment.multiculture',
                'parent_flag' => 'contentsFE.create.comment',
            ],
            //  cultural_sympathy
            [
                'name' => 'Cultural Sympathy',
                'flag' => 'contentsFE.create.comment.cultural_sympathy',
                'parent_flag' => 'contentsFE.create.comment',
            ],
            //  fine_notebook
            [
                'name' => 'Fine Notebook',
                'flag' => 'contentsFE.create.comment.fine_notebook',
                'parent_flag' => 'contentsFE.create.comment',
            ],
            //  written_note
            [
                'name' => 'Written Note',
                'flag' => 'contentsFE.create.comment.written_note',
                'parent_flag' => 'contentsFE.create.comment',
            ],
            //  contribution
            [
                'name' => 'Contribution',
                'flag' => 'contentsFE.create.comment.contribution',
                'parent_flag' => 'contentsFE.create.comment',
            ],
        //Delete
        [
            'name' => 'Delete',
            'flag' => 'contentsFE.delete.comment',
            'parent_flag' => 'contentsFE.comment',
        ],
            //  multiculture
            [
                'name' => 'Multiculture',
                'flag' => 'contentsFE.delete.comment.multiculture',
                'parent_flag' => 'contentsFE.delete.comment',
            ],
            //  cultural_sympathy
            [
                'name' => 'Cultural Sympathy',
                'flag' => 'contentsFE.delete.comment.cultural_sympathy',
                'parent_flag' => 'contentsFE.delete.comment',
            ],
            //  fine_notebook
            [
                'name' => 'Fine notebook',
                'flag' => 'contentsFE.delete.comment.fine_notebook',
                'parent_flag' => 'contentsFE.delete.comment',
            ],
            //  written_note
            [
                'name' => 'Written Note',
                'flag' => 'contentsFE.delete.comment.written_note',
                'parent_flag' => 'contentsFE.delete.comment',
            ],
            //  contribution
            [
                'name' => 'Contribution',
                'flag' => 'contentsFE.delete.comment.contribution',
                'parent_flag' => 'contentsFE.delete.comment',
            ],

];
