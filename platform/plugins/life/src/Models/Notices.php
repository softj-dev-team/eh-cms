<?php

namespace Botble\Life\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class Notices extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notices';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'notices',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = NOTICES_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
