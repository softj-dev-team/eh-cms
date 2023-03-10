<?php

namespace Botble\MasterRoom\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\MasterRoom\Models\MasterRoom
 *
 * @mixin \Eloquent
 */
class MasterRoom extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'master_rooms';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'start',
        'end',
        'enrollment_limit',
        'banner',
        'content',
        'notice',
        'description',
        'lookup',
        'member_id',
        'categories_master_rooms_id',
        'published',
        'status',
        'file_upload',
        'link',
    ];

    /**
     * @var string
     */
    protected $screen = MASTER_ROOM_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array',
    ];

    public function comments()
    {
        return $this->hasMany(CommentsMasterRoom::class, 'master_rooms_id')->orderBy('created_at', 'DESC');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function categories()
    {
        return $this->belongsTo(CategoriesMasterRoom::class, 'categories_master_rooms_id')->where('status', 'publish')->orderBy('created_at', 'DESC');
    }

    public function scopeOrdered($query)
    {
        return $query
                ->withCount('comments')
                ->orderBy('published', 'desc')
                ->orderBy('lookup', 'desc')
                ->orderBy('comments_count', 'desc');
    }

    public function masterRoomReplies()
    {
        return $this->hasMany(MasterRoomReply::class);
    }
}
