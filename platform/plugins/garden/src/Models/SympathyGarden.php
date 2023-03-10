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
class SympathyGarden extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sympathy_gardens';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'gardens_id',
        'member_id',
        'reason',
        'is_dislike',
    ];

    /**
     * @var string
     */
    protected $screen = SYMPATHY_GARDEN_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
