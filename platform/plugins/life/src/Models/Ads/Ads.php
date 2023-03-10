<?php

namespace Botble\Life\Models\Ads;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class Ads extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'advertisements';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'categories',
        'details',
        'deadline',
        'member_id',
        'images',
        'lookup',
        'status',
        'start',
        'duration',
        'recruitment',
        'contact',
        'file_upload',
        'link',
        'is_deadline',
        'published',
        'club',
        'duration2'

    ];

    /**
     * @var string
     */
    protected $screen = ADS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'club' => 'array',
    ];


    public function comments()
    {
        return $this->hasMany(AdsComments::class, 'advertisements_id')->where("is_deleted",0)->orderBy('created_at', 'DESC');
    }

    public function getNameCategories($id){
        $adsCategories = AdsCategories::where('id',$id)->where('status','publish')->first();
        return $adsCategories ?? "No have Categories";
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

    public function setFileUploadAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['file_upload'] = json_encode($value);
        }
        $this->attributes['file_upload'] = $value;
    }

    public function getFileUploadAttribute($value)
    {
        if ($value)
            return json_decode($value, true);
        return '';
    }


    public function setLinkAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['link'] = json_encode($value);
        }
        $this->attributes['link'] = $value;
    }

    public function getLinkAttribute($value)
    {
        if ($value)
            return json_decode($value, true);
        return '';
    }

    public function member(){
        $member = Member::find($this->member_id);
        return $member;
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
        return $this->belongsToMany(Member::class, 'sympathy_ads', 'ads_id', 'member_id');
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
