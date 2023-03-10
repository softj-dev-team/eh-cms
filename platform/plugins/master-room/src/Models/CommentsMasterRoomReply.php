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
class CommentsMasterRoomReply extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments_master_room_replies';

    /**
     * @var array
     */
    protected $fillable = [
        'master_room_reply_id',
        'parents_id',
        'member_id',
        'anonymous',
        'content',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = COMMENTS_MASTER_ROOM_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function getAllCommentByParentsID($id)
    {
        return $this->where('parents_id',$id)->get();
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id')->orderBy('created_at', 'DESC');
    }

    public function dislikes()
    {
        return null;
    }
}
