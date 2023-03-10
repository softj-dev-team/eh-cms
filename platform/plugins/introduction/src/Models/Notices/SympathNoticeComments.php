<?php

namespace Botble\Introduction\Models\Notices;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Contents\Models\Contents;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Events\Models\Events
 *
 * @mixin \Eloquent
 */
class SympathNoticeComments extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sympathy_notice_introductions_comments';

    /**
     * @var array
     */
    protected $fillable = [
        'post_id',
        'member_id',
        'comments_id',
        'is_dislike',
        'reason',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function contents()
    {
        return $this->belongsTo(NoticesIntroduction::class, 'notice_introduction_id');
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id')->orderBy('created_at', 'DESC');
    }
    public function comments()
    {
        return $this->belongsTo(CommentsNoticeIntroduction::class, 'comments_id')->orderBy('created_at', 'DESC');
    }
}
