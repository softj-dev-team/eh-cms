<?php

namespace Botble\Campus\Models\StudyRoom;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class StudyRoomCategories extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'study_room_categories';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_id',
        'background',
        'color',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = STUDY_ROOM_CATEGORIES_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
