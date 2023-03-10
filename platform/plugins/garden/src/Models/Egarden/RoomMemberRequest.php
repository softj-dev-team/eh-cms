<?php

namespace Botble\Garden\Models\Egarden;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Garden\Models\Garden
 *
 * @mixin \Eloquent
 */
class RoomMemberRequest extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'room_member_request';

    /**
     * @var array
     */
    protected $fillable = [
        'room_id',
        'member_id',
        'important',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = ROOM_MEMBER_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function member() {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
