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
class MajorEvaluation extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'major_evaluation';

    /**
     * @var array
     */
    protected $fillable = [
        'evaluation_id',
        'major_id',
        'status',

    ];

    /**
     * @var string
     */
    protected $screen = MAJOR_EVALUATION_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function evaluation()
    {
        return $this->belongsToMany(Evaluation::class, 'major_evaluation');
    }

}
