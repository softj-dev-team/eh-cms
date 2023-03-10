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
class ScheduleTime extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schedule_time';

    /**
     * @var array
     */
    protected $fillable = [
        'from',
        'to',
        'unit',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = SCHEDULE_TIME_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
