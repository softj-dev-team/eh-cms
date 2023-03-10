<?php

namespace Botble\Contents\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Contents\Models\Contents
 *
 * @mixin \Eloquent
 */
class CommentsContents extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments_contents';

    /**
     * @var array
     */
    protected $fillable = [
        'contents_id',
        'parents_id',
        'member_id',
        'content',
        'status',
        'anonymous',
        'ip_address',
    ];

    /**
     * @var string
     */
    protected $screen = COMMENTS_CONTENTS_MODULE_SCREEN_NAME;

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

    public function getAllCommentByParentsID($id)
    {
        $result = $this->where('parents_id',$id)->where('status','publish')
        ->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->get();
        return $result;
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id')->orderBy('created_at', 'DESC');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_contents_comments', 'comments_id', 'member_id');
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
