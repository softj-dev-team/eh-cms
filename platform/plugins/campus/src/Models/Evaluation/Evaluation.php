<?php

namespace Botble\Campus\Models\Evaluation;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class Evaluation extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'evaluation';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'professor_name',
        'year',
        'semester',
        'score', // credit
        'grade',
        'remark',
        'status',
        'datetime',
        'course_code',
        'lecture_room',
        'compete',
        'english_class',
        'foreign_language',
        'humanities_class',
        'sw_class',
        'quota',
        'online_class',
        'class_hours',
        'division',
        'class_type',
        'department',
        'major_type',
        'class_time',
        'college',
        'is_major',

    ];

    /**
     * @var string
     */
    protected $screen = EVALUATION_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function major()
    {
        return $this->belongsToMany(Major::class, 'major_evaluation')->orderBy('major.created_at', 'DESC');
    }

    public function comments()
    {
        return $this->hasMany(CommentsEvaluation::class, 'evaluation_id')->orderBy('created_at', 'DESC');
    }

    public function getValueLecture($col)
    {
        return $this->comments->countBy($col)->sort()->keys()->last();
    }

    public function getAvgVote()
    {
        return $this->comments->avg('votes');
    }

    public function getValueInArr($col){

       return $this->comments->pluck($col)->collapse()->countBy()->sort()->keys()->last();
    }

    public function scopeOrdered($query)
    {
        return $query
                ->withCount('comments')
                ->orderBy('created_at', 'desc')
                ->orderBy('lookup', 'desc')
                ->orderBy('comments_count', 'desc');
    }

}
