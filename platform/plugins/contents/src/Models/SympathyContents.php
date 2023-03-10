<?php

namespace Botble\Contents\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class SympathyContents extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sympathy_contents';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'contents_id',
        'member_id',
        'is_dislike',
        'reason',
    ];

    /**
     * @var string
     */
    protected $screen = SYMPATHY_CONTENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
