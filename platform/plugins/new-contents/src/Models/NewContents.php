<?php

namespace Botble\NewContents\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\NewContents\Models\NewContents
 *
 * @mixin \Eloquent
 */
class NewContents extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'new_contents';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'start',
        'end',
        'enrollment_limit',
        'banner',
        'content',
        'notice',
        'description',
        'lookup',
        'member_id',
        'categories_new_contents_id',
        'published',
        'status',
        'file_upload',
        'link',
    ];

    /**
     * @var string
     */
    protected $screen = NEW_CONTENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array',
    ];
    public function comments()
    {
        return $this->hasMany(CommentsNewContents::class, 'new_contents_id')->orderBy('created_at', 'DESC');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function categories()
    {
        return $this->belongsTo(CategoriesNewContents::class, 'categories_new_contents_id')->where('status', 'publish')->orderBy('created_at', 'DESC');
    }
    
    public function scopeOrdered($query)
    {
        return $query
                ->withCount('comments')
                ->orderBy('published', 'desc')
                ->orderBy('lookup', 'desc')
                ->orderBy('comments_count', 'desc');
    }
}
