<?php

namespace Botble\Campus\Models\StudyRoom;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class StudyRoom extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'study_room';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'categories',
        'contact',
        'detail',
        'member_id',
        'images',
        'lookup',
        'published',
        'status',
        'file_upload',
        'link',
    ];

    /**
     * @var string
     */
    protected $screen = STUDY_ROOM_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array',
    ];

    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['images'] = json_encode($value);
        }
        $this->attributes['images'] = $value;
    }

    public function getImagesAttribute($value)
    {
        if ($value)
            return json_decode($value, true);
        return '';
    }

    public function comments()
    {
        return $this->hasMany(StudyRoomComments::class, 'study_room_id')->where("is_deleted",0)->orderBy('created_at', 'DESC');
    }

    public function getNameCategories($id){
        $studyRoomCategories = StudyRoomCategories::where('id',$id)->where('status','publish')->first();
        return $studyRoomCategories ?? "No have Categories";
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
                ->orderBy('status', 'desc')
                ->orderBy('published', 'desc')
                ->orderBy('lookup', 'desc')
                ->orderBy('comments_count', 'desc');
    }


}
