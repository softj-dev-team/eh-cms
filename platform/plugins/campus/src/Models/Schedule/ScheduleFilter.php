<?php

namespace Botble\Campus\Models\Schedule;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class ScheduleFilter extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schedule_filter';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'start',
        'end',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = SCHEDULE_FILTER_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function getSchedule()
    {
        return Schedule::whereBetween('created_at', [
            date_format(date_create($this->start), "Y-m-d 00:00:00"),
            date_format(date_create($this->end), "Y-m-d 23:59:59")
        ])
        ->where('id_login',auth()->guard('member')->user()->id_login)
        ->orderBy('created_at','DESC')
        ->get();
    }

}
