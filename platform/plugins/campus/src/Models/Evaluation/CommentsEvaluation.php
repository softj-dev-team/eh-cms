<?php

namespace Botble\Campus\Models\Evaluation;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class CommentsEvaluation extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments_evaluation';

    /**
     * @var array
     */
    protected $fillable = [
        'evaluation_id',
        'votes',
        'member_id',
        'comments',
        'grade',
        'assignment',
        'attendance',
        'textbook',
        'team_project',
        'number_times',
        'type',
        'ip_address',
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

    public function setTextbookAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['textbook'] = json_encode($value);
        }
        $this->attributes['textbook'] = $value;
    }

    public function getTextbookAttribute($value)
    {
        if ($value)
            return json_decode($value, true);
        return '';
    }

    public function setTypeAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['type'] = json_encode($value);
        }
        $this->attributes['type'] = $value;
    }

    public function getTypeAttribute($value)
    {
        if ($value)
            return json_decode($value, true);
        return '';
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id')->orderBy('created_at', 'DESC');
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

}
