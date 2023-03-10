<?php

namespace Botble\Contents\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Contents\Models\CommentsContents;
use Botble\Contents\Models\Contents;
use Botble\Member\Models\Member;
use Eloquent;


/**
 * Botble\Events\Models\Events
 *
 * @mixin \Eloquent
 */
class SympathyCommentsContents extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sympathy_contents_comments';

    /**
     * @var array
     */
    protected $fillable = [
            'contents_id',
            'member_id',
            'comments_id',
            'is_dislike',
            'reason',
    ];

    /**
     * @var string
     */
    protected $screen = SYMPATHY_CONTENTS_COMMENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function contents()
    {
        return $this->belongsTo(Contents::class,'contents_id');
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id')->orderBy('created_at', 'DESC');
    }
    public function comments()
    {
        return $this->belongsTo(CommentsContents::class, 'comments_id')->orderBy('created_at', 'DESC');
    }
}
