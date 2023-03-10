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
class Comments extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * @var array
     */
    protected $fillable = [
            'event_id',
            'member_id',
            'content',
            'status',
            'anonymous',
            'ip_address',
            'file_upload'
    ];

    /**
     * @var string
     */
    protected $screen = COMMENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function getAllCommentByParentsID($id)
    {
        $result = $this->where('parents_id',$id)->where('status', 'publish')->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->get();
        return $result;
    }

    public function events()
    {
        return $this->belongsTo(Events::class,'event_id');
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id')->orderBy('created_at', 'DESC');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_event_comments', 'comments_id', 'member_id');
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
