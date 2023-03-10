<?php

namespace Botble\Campus\Models\Schedule;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class ScheduleShare extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schedule_share';

    /**
     * @var array
     */
    protected $fillable = [
        'schedule_id',
        'member_id',
        'author',
    ];

    /**
     * @var string
     */
    protected $screen = SCHEDULE_SHARE_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
