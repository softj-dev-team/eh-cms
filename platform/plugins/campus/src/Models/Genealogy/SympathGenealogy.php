<?php

namespace Botble\Campus\Models\Genealogy;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class SympathGenealogy extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sympathy_genealogy';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'genealogy_id',
        'member_id',
        'is_dislike',
        'reason',
    ];

    /**
     * @var string
     */
    protected $screen = SYMPATHY_GENEALOGY_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
