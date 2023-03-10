<?php

namespace Botble\Life\Models\OpenSpace;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class OpenSpace extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'open_space';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'detail',
        'member_id',
        'views',
        'images',
        'published',
        'status',
        'file_upload',
        'link',
        'categories_id',
        'active_empathy',
        'right_click',
    ];

    /**
     * @var string
     */
    protected $screen = OPEN_SPACE_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'images' => 'array',
        'file_upload' => 'array',
        'link' => 'array',
    ];

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function comments()
    {
        return $this->hasMany(OpenSpaceComments::class, 'open_space_id')->where("is_deleted",0)->orderBy('created_at', 'DESC');
    }

    public function scopeOrdered($query)
    {
        return $query
                ->withCount('comments')
                ->orderBy('published', 'desc')
                ->orderBy('views', 'desc')
                ->orderBy('comments_count', 'desc');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_open_space', 'open_space_id', 'member_id');
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
