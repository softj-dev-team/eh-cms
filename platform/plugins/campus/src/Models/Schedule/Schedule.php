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
class Schedule extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schedule';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'start',
        'end',
        'lookup',
        'id_login',
        'total_credit',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = SCHEDULE_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function timeline()
    {
        return $this->hasMany(ScheduleTimeLine::class, 'schedule_id');
    }

    public function member()
    {
        return $this->belongsToMany(Member::class, 'schedule_share');
    }

    public function scheduleShare()
    {
        return ScheduleShare::where('schedule_id',$this->id)->get();
    }


}
