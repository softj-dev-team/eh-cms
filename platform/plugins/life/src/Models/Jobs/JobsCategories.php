<?php

namespace Botble\Life\Models\Jobs;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class JobsCategories extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'jobs_categories';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_id',
        'background',
        'color',
        'type',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = JOBS_CATEGORIES_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function parent()
    {
        return $this->belongsTo(JobsCategories::class,'parent_id')->where('status','publish');
    }

    public function children($id)
    {
        return $this->where('parent_id',$id)->where('status','publish')->get();
    }

    public function scopeWithCondition($query)
    {
        $member = auth()->guard('member')->user();
        if( auth()->guard('member')->check() && $member->certification === 'certification') {
            return $query->where('status','publish');
        }
        return $query->where('type',0)->where('status','publish');
    }
}
