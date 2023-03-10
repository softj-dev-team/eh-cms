<?php

namespace Botble\Garden\Models\Egarden;

use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Garden\Models\Garden
 *
 * @mixin \Eloquent
 */
class CategoriesRoom extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories_room';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'member_id',
        'background',
        'color',
        'room_id',
    ];

    /**
     * @var string
     */
    protected $screen = CATEGORIES_ROOM_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id')->where('status', 'publish')->withTimestamps();
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
