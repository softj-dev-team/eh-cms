<?php

namespace Botble\Garden\Models;

use Eloquent;
use Botble\Member\Models\Member;
use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * Botble\Garden\Models\Garden
 *
 * @mixin \Eloquent
 */
class Garden extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gardens';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'detail',
        'notice',
        'lookup',
        'hot_garden',
        'active_empathy',
        'right_click',
        'member_id',
        'categories_gardens_id',
        'published',
        'status',
        'file_upload',
        'link',
        'hint',
        'can_reaction',
        'pwd_post',
    ];

    /**
     * @var string
     */
    protected $screen = GARDEN_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array',
    ];

    public function categories()
    {
        return $this->belongsTo(CategoriesGarden::class, 'categories_gardens_id')->where('status', 'publish')->orderBy('created_at', 'DESC');
    }

    public function comments()
    {
        //return $this->hasMany(CommentsGarden::class, 'gardens_id')->orderBy('created_at', 'DESC');
        return $this->hasMany(CommentsGarden::class, 'gardens_id')->where("is_deleted",0)->orderBy('created_at', 'DESC');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_gardens', 'gardens_id', 'member_id');
    }

    public function dislikes()
    {
        return $this->check_sympathy()->where('is_dislike', 1);
    }

    public function likes()
    {
        return $this->check_sympathy()->where('is_dislike', '!=', 1);
    }

    public function scopeOrdered($query)
    {
        return $query
//                ->withCount('comments')
//                ->withCount('likes')
//                ->orderBy('hot_garden', 'DESC')
//                ->orderBy('published', 'desc')
//                ->orderBy('lookup', 'desc')
//                ->orderBy('comments_count', 'desc')
//                ->orderBy('likes_count', 'desc');
                ->orderBy('id', 'desc');
    }

    public function gardenDetail()
    {
        return $this->hasOne(GardenDetail::class);
    }

    /**
     * @param Member $user
     * @return bool
     */
    public function isBookmarkedBy(Member $user)
    {
        if ($this->relationLoaded('bookmarkers')) {
            return $this->bookmarkers->contains($user);
        }
        return ($this->relationLoaded('bookmarks') ?
            $this->bookmarks :
            $this->bookmarks())->where(config('bookmark.user_foreign_key'), $user->getKey())->count() > 0;
    }

    /**
     * @return mixed
     */
    public function bookmarks()
    {
        return $this->morphMany(config('bookmark.bookmark_model'), 'bookmarkable');
    }

    /**
     * @return mixed
     */
    public function bookmarkers()
    {
        return $this->belongsToMany(Member::class, config('bookmark.bookmarks_table'), 'bookmarkable_id', config('bookmark.user_foreign_key'))->where('bookmarkable_type', $this->getMorphClass());
    }
}
