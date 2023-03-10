<?php

namespace Botble\Life\Models\Shelter;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class Shelter extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shelter';

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
        'real_estate',
        'option',
        'heating_type',
        'possible_moving_date',
        'building_type',
        'lease_period',
        'size',
        'location',
        'utility',
        'right_click',
        'deposit',
        'monthly_rent',
    ];

    /**
     * @var string
     */
    protected $screen = SHELTER_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array',
        'option' => 'array',
        'utility' => 'array',
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
        return $this->hasMany(ShelterComments::class, 'shelter_id')->where("is_deleted",0);
    }

    public function getNameCategories($id){
        $shelterCategories = ShelterCategories::where('id',$id)->where('status','publish')->first();
        return $shelterCategories ?? "No have Categories";
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
        return $this->belongsToMany(Member::class, 'sympathy_shelter', 'shelter_id', 'member_id');
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
