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
class ScheduleTimeLine extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schedule_timeline';

    /**
     * @var array
     */
    protected $fillable = [
        'schedule_id',
        'title',
        'day',
        'end_day',
        'from',
        'to',
        'status',
        'lecture_room',
        'professor_name',
        'color',
        'group_color',
        'course_division',
        'datetime',
    ];

    /**
     * @var string
     */
    protected $screen = SCHEDULE_TIMELINE_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'datetime' => 'array',
    ];

    public function schedule()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_id');
    }
}
