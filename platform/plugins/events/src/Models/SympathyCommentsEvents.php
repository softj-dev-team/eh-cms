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
class SympathyCommentsEvents extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sympathy_event_comments';

    /**
     * @var array
     */
    protected $fillable = [
            'event_id',
            'member_id',
            'comments_id',
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
        return $this->belongsTo(Events::class,'event_id');
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id')->orderBy('created_at', 'DESC');
    }
    public function comments()
    {
        return $this->belongsTo(Comments::class, 'comments_id')->orderBy('created_at', 'DESC');
    }
}
