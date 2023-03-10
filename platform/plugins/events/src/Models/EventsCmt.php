<?php

namespace Botble\Events\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Events\Models\Events
 *
 * @mixin \Eloquent
 */
class EventsCmt extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'events_cmt';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'detail',
        'category_events_id',
        'member_id',
        'views',
        'published',
        'status',
        'file_upload',
        'link',
    ];

    /**
     * @var string
     */
    protected $screen = EVENTS_CMT_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array',
    ];

    public function category_events()
    {
        return $this->belongsTo(CategoryEvents::class, 'category_events_id')->where('status', 'publish')->orderBy('created_at','DESC');
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function comments()
    {
        return $this->hasMany(CommentsEventsCmt::class, 'events_cmt_id')->where("is_deleted",0)->orderBy('created_at', 'DESC');
    }

    public function scopeOrdered($query)
    {
        return $query
                ->withCount('comments')
                ->orderBy('published', 'desc')
                ->orderBy('views', 'desc')
                ->orderBy('comments_count', 'desc');
    }
}
