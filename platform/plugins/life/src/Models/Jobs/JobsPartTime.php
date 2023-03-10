<?php

namespace Botble\Life\Models\Jobs;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class JobsPartTime extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'jobs_part_time';

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
        'pay',
        'location',
        'period',
        'day',
        'time',
        'resume',
        'working_period',
        'applying_period',
        'open_position',
        'exact_location',
        'prefer_requirements',
        'id',
        'status'
    ];

    /**
     * @var string
     */
    protected $screen = JOBS_PART_TIME_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array',
        'pay' => 'array',
        'categories' => 'array',
        'exact_location' => 'array',
        'day' => 'array',
    ];


    public function comments()
    {
        return $this->hasMany(JobsComments::class, 'jobs_part_time_id')->where("is_deleted",0)->orderBy('created_at', 'DESC');
    }

    public function getNameCategories($id){
        $jobsCategories = JobsCategories::withcondition()->where('id',$id)->where('status','publish')->first();
        return $jobsCategories ?? "No have Categories";
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
        return $this->belongsToMany(Member::class, 'sympathy_jobs_part_time', 'jobs_part_time_id', 'member_id');
    }

    public function dislikes()
    {
        return $this->check_sympathy()->where('is_dislike', 1);
    }

    public function likes()
    {
        return $this->check_sympathy()->where('is_dislike', '!=', 1);
    }

    public function scopeRejectNotPublishCategories($query) {
        $rejectCategories = JobsCategories::where('status','!=','publish')->get();

        foreach ($rejectCategories as $key => $item) {
            $query->whereJsonDoesntContain('categories',['1'=> camel_case($item->id)]);

            $query->whereJsonDoesntContain('categories',['2'=> camel_case($item->id)]);
        }
    }

    public function scopeRejectCategories($query)
    {
        $query->rejectnotpublishcategories();
        $member = auth()->guard('member')->user();
        if( auth()->guard('member')->check() && $member->certification === 'certification') {
            return $query;
        }

        $conditionCategories = JobsCategories::where('type','!=',0)->where('status','publish')->first();
        if(is_null($conditionCategories)) {
            return $query;
        }
        return $query->whereJsonDoesntContain('categories',['1'=> camel_case($conditionCategories->id)]);
    }

}
