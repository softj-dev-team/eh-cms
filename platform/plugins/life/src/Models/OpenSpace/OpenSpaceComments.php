<?php

namespace Botble\Life\Models\OpenSpace;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Life\Models\OpenSpace\OpenSpace;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Events\Models\Events
 *
 * @mixin \Eloquent
 */
class OpenSpaceComments extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'open_space_comments';

    /**
     * @var array
     */
    protected $fillable = [
        'open_space_id',
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
    protected $screen = OPEN_SPACE_COMMENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function getAllCommentByParentsID($id)
    {
      return $this->where('parents_id', $id)->where('status','publish')
        ->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->get();

    }

    public function openSpace()
    {
        return $this->belongsTo(OpenSpace::class, 'open_space_id');
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_open_space_comments', 'comments_id', 'member_id');
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
