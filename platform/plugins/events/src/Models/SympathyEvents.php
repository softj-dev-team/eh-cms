<?php

namespace Botble\Events\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class SympathyEvents extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sympathy_events';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'events_id',
        'member_id',
        'is_dislike',
        'reason',
    ];

    /**
     * @var string
     */
    protected $screen = SYMPATHY_EVENT_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
