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
class Contents extends Eloquent
{
    use EnumCastable;
    const IS_NOT_SLIDE = 0;
    const IS_SLIDE = 1;

    const REGISTER_MAIN_CONTENT = 'REGISTER_MAIN_CONTENT';
    const UN_REGISTER_MAIN_CONTENT = 'UN_REGISTER_MAIN_CONTENT';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contents';

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
        'categories_contents_id',
        'status',
        'member_id',
        'active_empathy',
        'published',
        'file_upload',
        'link',
        'is_slides',
        'slide_no',
        'is_main_content',
    ];

    /**
     * @var string
     */
    protected $screen = CONTENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array'
    ];

    public function categories_contents()
    {
        return $this->belongsTo(CategoriesContents::class,'categories_contents_id');
    }

    public function comments()
    {
        return $this->hasMany(CommentsContents::class,'contents_id')->where("is_deleted",0)->orderBy('created_at', 'DESC');
    }

    public function members()
    {
        return $this->belongsTo(Member::class,'member_id');
    }

    public function getBase64Image()
    {
        $file= file_get_contents (get_image_url($this->banner));
        $result = base64_encode($file);
        return  $result ;
    }

    public function getNameMemberById($id)
    {
        if ($id == null) return "Admin";
        $member = Member::find($id);
        if ($member == null) {
            return "Anonymous";
        }
        return $member->nickname;
    }

    public function getStatusMember($id){
        $member = Member::find($id);
        if ($member == null) {
            return "real_name_certification";
        }
        return $member->certification;
    }

    public function scopeOrdered($query)
    {
        return $query
                ->withCount('comments')
                ->orderBy('published', 'desc')
                ->orderBy('lookup', 'desc')
                ->orderBy('comments_count', 'desc');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_contents', 'contents_id', 'member_id');
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
