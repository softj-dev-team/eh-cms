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
class SympathyCommentsEventscmt extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sympathy_eventcmt_comments';

    /**
     * @var array
     */
    protected $fillable = [
            'ecmt_id',
            'member_id',
            'ecmt_comments_id',
            'is_dislike',
            'reason',
    ];

    /**
     * @var string
     */
    protected $screen = SYMPATHY_EVENT_COMMENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function events()
    {
        return $this->belongsTo(EventsCmt::class,'ecmt_id');
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id')->orderBy('created_at', 'DESC');
    }
    public function comments()
    {
        return $this->belongsTo(CommentsEventsCmt::class, 'ecmt_comments_id')->orderBy('created_at', 'DESC');
    }
}
