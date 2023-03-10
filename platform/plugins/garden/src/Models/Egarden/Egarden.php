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
class Egarden extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'egardens';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'detail',
        'lookup',
        'active_empathy',
        'right_click',
        'member_id',
        'room_id',
        'published',
        'status',
        'file_upload',
        'link',
        'hint',
        'categories_room_id',
        'banner'
    ];

    /**
     * @var string
     */
    protected $screen = EGARDEN_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id')
            ->whereIn('status', [BaseStatusEnum::PUBLISH(), BaseStatusEnum::CAN_APPLY()])
            ->orderBy('created_at', 'DESC');
    }

    public function comments()
    {
        return $this->hasMany(CommentsEgarden::class, 'egardens_id')->orderBy('created_at', 'DESC');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function scopeOrdered($query)
    {
        return $query
                ->withCount('comments')
                ->orderBy('published', 'desc')
                ->orderBy('lookup', 'desc')
                ->orderBy('comments_count', 'desc');
    }

    public function categoriesRoom()
    {
        return $this->belongsTo(CategoriesRoom::class, 'categories_room_id')->orderBy('created_at', 'DESC');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_egardens', 'egardens_id', 'member_id');
    }

    public function dislikes()
    {
        return $this->check_sympathy()->where('is_dislike', 1);
    }

    public function likes()
    {
        return $this->check_sympathy()->where('is_dislike', '!=', 1);
    }

}
