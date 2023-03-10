<?php

namespace Botble\Life\Models\OpenSpace;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Contents\Models\Contents;
use Botble\Member\Models\Member;
use Eloquent;


/**
 * Botble\Events\Models\Events
 *
 * @mixin \Eloquent
 */
class SympathOpenSpaceComments extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sympathy_open_space_comments';

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
     * @var string
     */
    protected $screen = SYMPATHY_OPEN_SPACE_COMMENTS;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function contents()
    {
        return $this->belongsTo(OpenSpace::class,'post_id');
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id')->orderBy('created_at', 'DESC');
    }
    public function comments()
    {
        return $this->belongsTo(OpenSpaceComments::class, 'comments_id')->orderBy('created_at', 'DESC');
    }
}
