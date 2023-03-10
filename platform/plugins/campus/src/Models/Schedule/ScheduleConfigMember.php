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
class ScheduleConfigMember extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schedule_config_member';

    /**
     * @var array
     */
    protected $fillable = [
        'member_id',
        'time',
        'day',
        'schedule_id',
        'show_lecture',
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
        'time' => 'array',
        'day' => 'array',
        'show_lecture' => 'array',
    ];

    public function member()
    {
        return $this->belongsToMany(Member::class, 'schedule_share');
    }

}
