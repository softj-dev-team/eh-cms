<?php
namespace Botble\Introduction\Models\Notices;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class SympathyNotice extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sympathy_notice_introductions';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'notice_introduction_id',
        'member_id',
        'reason',
        'is_dislike',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
