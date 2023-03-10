<?php

namespace Botble\Events\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Events\Models\Events
 *
 * @mixin \Eloquent
 */
class Events extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'events';

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
        'views',
        'status',
        'category_events_id',
        'member_id',
        'published',
        'file_upload',
        'link',
    ];

    /**
     * @var string
     */
    protected $screen = EVENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array',
    ];

    // public function getStartAttribute()
    // {
    //     return date('d M | H:i a', strtotime('start'));
    // }
    // public function getEndAttribute()
    // {
    //     return date('d M | h:i a', strtotime('end'));
    // }
    public function comments()
    {
        return $this->hasMany(Comments::class, 'event_id')->where('status', 'publish')->where("is_deleted",0)->orderBy('created_at', 'DESC');
    }
    public function search_comments($keyword)
    {
        return $this->hasMany(Comments::class, 'event_id')->orderBy('created_at', 'DESC')->where('comments.content', 'like', '%' . $keyword . '%')->take(2);
    }

    public function category_events()
    {
        return $this->belongsTo(CategoryEvents::class, 'category_events_id')->where('status', 'publish')->orderBy('created_at', 'DESC');
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id');
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
                ->orderBy('views', 'desc')
                ->orderBy('comments_count', 'desc');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_events', 'events_id', 'member_id');
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
