<?php
namespace Botble\Garden\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class SympathyEgarden extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sympathy_egardens';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'egardens_id',
        'member_id',
        'reason',
        'is_dislike',
    ];

    /**
     * @var string
     */
    protected $screen = SYMPATHY_EGARDEN_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
