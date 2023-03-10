<?php

namespace Botble\Campus\Models\Notices;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class NoticesCampus extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notices_campus';

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
    protected $screen = NOTICES_CAMPUS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
