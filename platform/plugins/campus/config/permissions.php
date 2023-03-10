<?php

return [
    //------------BE--------------------------------------
    [
        'name' => 'Campus',
        'flag' => 'campus.list',
    ],

    //Study room
    [
        'name' => 'Study room',
        'flag' => 'campus.study_room.list',
        'parent_flag' => 'campus.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'campus.study_room.create',
        'parent_flag' => 'campus.study_room.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'campus.study_room.edit',
        'parent_flag' => 'campus.study_room.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'campus.study_room.delete',
        'parent_flag' => 'campus.study_room.list',
    ],
        //Study room categories
        [
            'name' => 'Study Room Categories',
            'flag' => 'campus.study_room.categories.list',
            'parent_flag' => 'campus.study_room.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'campus.study_room.categories.create',
            'parent_flag' => 'campus.study_room.categories.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'campus.study_room.categories.edit',
            'parent_flag' => 'campus.study_room.categories.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'campus.study_room.categories.delete',
            'parent_flag' => 'campus.study_room.categories.list',
        ],

        //Study room comments
        [
            'name' => 'Study Room Comments',
            'flag' => 'campus.study_room.comments.list',
            'parent_flag' => 'campus.study_room.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'campus.study_room.comments.create',
            'parent_flag' => 'campus.study_room.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'campus.study_room.comments.delete',
            'parent_flag' => 'campus.study_room.comments.list',
        ],
    //Genealogy
    [
        'name' => 'Genealogy',
        'flag' => 'campus.genealogy.list',
        'parent_flag' => 'campus.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'campus.genealogy.create',
        'parent_flag' => 'campus.genealogy.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'campus.genealogy.edit',
        'parent_flag' => 'campus.genealogy.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'campus.genealogy.delete',
        'parent_flag' => 'campus.genealogy.list',
    ],
        //genealogy comments
        [
            'name' => 'Genealogy Comments',
            'flag' => 'campus.genealogy.comments.list',
            'parent_flag' => 'campus.genealogy.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'campus.genealogy.comments.create',
            'parent_flag' => 'campus.genealogy.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'campus.genealogy.comments.delete',
            'parent_flag' => 'campus.genealogy.comments.list',
        ],
    //OldGenealogy
    [
        'name' => 'Old Genealogy',
        'flag' => 'campus.old.genealogy.list',
        'parent_flag' => 'campus.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'campus.old.genealogy.create',
        'parent_flag' => 'campus.old.genealogy.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'campus.old.genealogy.edit',
        'parent_flag' => 'campus.old.genealogy.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'campus.old.genealogy.delete',
        'parent_flag' => 'campus.old.genealogy.list',
    ],
        //old genealogy comments
        [
            'name' => 'Old Genealogy Comments',
            'flag' => 'campus.old.genealogy.comments.list',
            'parent_flag' => 'campus.genealogy.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'campus.old.genealogy.comments.create',
            'parent_flag' => 'campus.genealogy.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'campus.old.genealogy.comments.delete',
            'parent_flag' => 'campus.genealogy.comments.list',
        ],
    //Evaluation
    [
        'name' => 'Evaluation',
        'flag' => 'campus.evaluation.list',
        'parent_flag' => 'campus.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'campus.evaluation.create',
        'parent_flag' => 'campus.evaluation.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'campus.evaluation.edit',
        'parent_flag' => 'campus.evaluation.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'campus.evaluation.delete',
        'parent_flag' => 'campus.evaluation.list',
    ],
        //Major
        [
            'name' => 'Major',
            'flag' => 'campus.evaluation.major.list',
            'parent_flag' => 'campus.evaluation.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'campus.evaluation.major.create',
            'parent_flag' => 'campus.evaluation.major.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'campus.evaluation.major.edit',
            'parent_flag' => 'campus.evaluation.major.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'campus.evaluation.major.delete',
            'parent_flag' => 'campus.evaluation.major.list',
        ],
        //evaluation comments
        [
            'name' => 'Evaluation Comments',
            'flag' => 'campus.evaluation.comments.list',
            'parent_flag' => 'campus.evaluation.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'campus.evaluation.comments.create',
            'parent_flag' => 'campus.evaluation.comments.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'campus.evaluation.comments.delete',
            'parent_flag' => 'campus.evaluation.comments.list',
        ],

    //Schedule
    [
        'name' => 'Schedule',
        'flag' => 'campus.schedule.list',
        'parent_flag' => 'campus.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'campus.schedule.create',
        'parent_flag' => 'campus.schedule.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'campus.schedule.edit',
        'parent_flag' => 'campus.schedule.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'campus.schedule.delete',
        'parent_flag' => 'campus.schedule.list',
    ],
        //Schedule Time
        [
            'name' => 'Schedule Time',
            'flag' => 'campus.schedule.time.list',
            'parent_flag' => 'campus.schedule.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'campus.schedule.time.create',
            'parent_flag' => 'campus.schedule.time.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'campus.schedule.time.edit',
            'parent_flag' => 'campus.schedule.time.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'campus.schedule.time.delete',
            'parent_flag' => 'campus.schedule.time.list',
        ],
         //Schedule Day
         [
            'name' => 'Schedule Day',
            'flag' => 'campus.schedule.day.list',
            'parent_flag' => 'campus.schedule.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'campus.schedule.day.create',
            'parent_flag' => 'campus.schedule.day.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'campus.schedule.day.edit',
            'parent_flag' => 'campus.schedule.day.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'campus.schedule.day.delete',
            'parent_flag' => 'campus.schedule.day.list',
        ],
         //Schedule TimeLine
         [
            'name' => 'Schedule TimeLine',
            'flag' => 'campus.schedule.timeline.list',
            'parent_flag' => 'campus.schedule.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'campus.schedule.timeline.create',
            'parent_flag' => 'campus.schedule.timeline.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'campus.schedule.timeline.edit',
            'parent_flag' => 'campus.schedule.timeline.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'campus.schedule.timeline.delete',
            'parent_flag' => 'campus.schedule.timeline.list',
        ],
        //Schedule Filter
        [
            'name' => 'Schedule Filter',
            'flag' => 'campus.schedule.filter.list',
            'parent_flag' => 'campus.schedule.list',
        ],
        [
            'name' => 'Create',
            'flag' => 'campus.schedule.filter.create',
            'parent_flag' => 'campus.schedule.filter.list',
        ],
        [
            'name' => 'Edit',
            'flag' => 'campus.schedule.filter.edit',
            'parent_flag' => 'campus.schedule.filter.list',
        ],
        [
            'name' => 'Delete',
            'flag' => 'campus.schedule.filter.delete',
            'parent_flag' => 'campus.schedule.filter.list',
        ],
    //Notices
    [
        'name' => 'Notices',
        'flag' => 'campus.notices.list',
        'parent_flag' => 'campus.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'campus.notices.create',
        'parent_flag' => 'campus.notices.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'campus.notices.edit',
        'parent_flag' => 'campus.notices.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'campus.notices.delete',
        'parent_flag' => 'campus.notices.list',
    ],

    //Description
    [
        'name' => 'Description',
        'flag' => 'campus.description.list',
        'parent_flag' => 'campus.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'campus.description.create',
        'parent_flag' => 'campus.description.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'campus.description.edit',
        'parent_flag' => 'campus.description.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'campus.description.delete',
        'parent_flag' => 'campus.description.list',
    ],


    //---------------------------FE----------------------------------
    //Campus FE
    [
        'name' => '4. CampusFE',
        'flag' => 'campusFE.list',
    ],
    //Study Room FE
    [
        'name' => 'Study Room FE',
        'flag' => 'studyRoomFE.list',
        'parent_flag' => 'campusFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'studyRoomFE.create',
        'parent_flag' => 'studyRoomFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'studyRoomFE.edit',
        'parent_flag' => 'studyRoomFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'studyRoomFE.delete',
        'parent_flag' => 'studyRoomFE.list',
    ],
    [
        'name' => 'Comment',
        'flag' => 'studyRoomFE.comment',
        'parent_flag' => 'studyRoomFE.list',
    ],
        //Create
        [
            'name' => 'Create',
            'flag' => 'studyRoomFE.comment.create',
            'parent_flag' => 'studyRoomFE.comment',
        ],
        //Delete
        [
            'name' => 'Delete',
            'flag' => 'studyRoomFE.comment.delete',
            'parent_flag' => 'studyRoomFE.comment',
        ],

    //Genealogy FE
    [
        'name' => 'Genealogy FE',
        'flag' => 'genealogyFE.list',
        'parent_flag' => 'campusFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'genealogyFE.create',
        'parent_flag' => 'genealogyFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'genealogyFE.edit',
        'parent_flag' => 'genealogyFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'genealogyFE.delete',
        'parent_flag' => 'genealogyFE.list',
    ],
    [
        'name' => 'Comment',
        'flag' => 'genealogyFE.comment',
        'parent_flag' => 'genealogyFE.list',
    ],
        //Create
        [
            'name' => 'Create',
            'flag' => 'genealogyFE.comment.create',
            'parent_flag' => 'genealogyFE.comment',
        ],
        //Delete
        [
            'name' => 'Delete',
            'flag' => 'genealogyFE.comment.delete',
            'parent_flag' => 'genealogyFE.comment',
        ],

    //Old Genealogy FE
    [
        'name' => 'Old Genealogy FE',
        'flag' => 'oldGenealogyFE.list',
        'parent_flag' => 'campusFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'oldGenealogyFE.create',
        'parent_flag' => 'oldGenealogyFE.list',
    ],
    [
        'name' => 'Edit',
        'flag' => 'oldGenealogyFE.edit',
        'parent_flag' => 'oldGenealogyFE.list',
    ],
    [
        'name' => 'Delete',
        'flag' => 'oldGenealogyFE.delete',
        'parent_flag' => 'oldGenealogyFE.list',
    ],
    [
        'name' => 'Comment',
        'flag' => 'oldGenealogyFE.comment',
        'parent_flag' => 'oldGenealogyFE.list',
    ],
        //Create
        [
            'name' => 'Create',
            'flag' => 'oldGenealogyFE.comment.create',
            'parent_flag' => 'oldGenealogyFE.comment',
        ],
        //Delete
        [
            'name' => 'Delete',
            'flag' => 'oldGenealogyFE.comment.delete',
            'parent_flag' => 'oldGenealogyFE.comment',
        ],

    //Schedule FE
    [
    'name' => 'Schedule',
    'flag' => 'scheduleFE.list',
    'parent_flag' => 'campusFE.list',
    ],
    [
        'name' => 'Details',
        'flag' => 'scheduleFE.details',
        'parent_flag' => 'scheduleFE.list',
    ],
    [
        'name' => 'Create',
        'flag' => 'scheduleFE.create',
        'parent_flag' => 'scheduleFE.list',
    ],
    [
        'name' => 'Create TimeLine',
        'flag' => 'scheduleFE.timeline.create',
        'parent_flag' => 'scheduleFE.list',
    ],
    [
        'name' => 'Delete TimeLine',
        'flag' => 'scheduleFE.timeline.delete',
        'parent_flag' => 'scheduleFE.list',
    ],
    //Schedule FE
    [
        'name' => 'Schedule',
        'flag' => 'scheduleFE.list',
        'parent_flag' => 'campusFE.list',
    ],
    //Lecture Evaluation
    [
        'name' => 'Lecture Evaluation',
        'flag' => 'evaluationFE.list',
        'parent_flag' => 'campusFE.list',
    ],
    [
        'name' => 'Comment',
        'flag' => 'evaluationFE.comment',
        'parent_flag' => 'evaluationFE.list',
    ],
        //Create
        [
            'name' => 'Create',
            'flag' => 'evaluationFE.comment.create',
            'parent_flag' => 'evaluationFE.comment',
        ],
    //Calculator
    [
        'name' => 'Calculator List',
        'flag' => 'campus.calculator_list',
        'parent_flag' => 'campusFE.list',
    ],

];
