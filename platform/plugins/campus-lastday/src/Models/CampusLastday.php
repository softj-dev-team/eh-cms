<?php

namespace Botble\CampusLastday\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\CampusLastday\Models\CampusLastday
 *
 * @mixin \Eloquent
 */
class CampusLastday extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campus_lastdays';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'start',
        'end',
        'year',
        'semester'
    ];

    /**
     * @var string
     */
    protected $screen = CAMPUS_LASTDAY_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
