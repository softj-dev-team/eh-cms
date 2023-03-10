<?php

namespace Botble\Events\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;


/**
 * Botble\Events\Models\Events
 *
 * @mixin \Eloquent
 */
class CommentsEventsCmt extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments_events_cmt';

    /**
     * @var array
     */
    protected $fillable = [
            'events_cmt_id',
            'parents_id',
            'anonymous',
            'member_id',
            'content',
            'status',
            'ip_address',
            'file_upload'

    ];

    /**
     * @var string
     */
    protected $screen = COMMENTS_EVENTS_CMT_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function getAllCommentByParentsID($id)
    {
        $result = $this->where('parents_id',$id)->withCount([
            'dislikes',
        ])
        ->withCount([
            'likes',
        ])->get();
        return $result;
    }

    public function events()
    {
        return $this->belongsTo(EventsCmt::class,'events_cmt_id');
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_eventcmt_comment', 'ecmt_comments_id', 'member_id');
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
