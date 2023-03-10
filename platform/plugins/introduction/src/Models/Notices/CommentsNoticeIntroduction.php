<?php

namespace Botble\Introduction\Models\Notices;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Introduction\Models\Introduction
 *
 * @mixin \Eloquent
 */
class CommentsNoticeIntroduction extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments_notice_introductions';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'notice_introduction_id',
        'parents_id',
        'member_id',
        'anonymous',
        'content',
        'ip_address',
        'status',
        'is_deleted',
        'file_upload'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function getAllCommentByParentsID($id)
    {
        return $this->where('parents_id', $id)->withCount(['dislikes'])->withCount(['likes'])->get();
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_notice_introductions_comments', 'comments_id', 'member_id');
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
