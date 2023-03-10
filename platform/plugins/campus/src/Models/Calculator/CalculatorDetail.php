<?php

namespace Botble\Campus\Models\Calculator;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class CalculatorDetail extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'calculator_detail';

    /**
     * @var array
     */
    protected $fillable = [
        'classification',
        'description',
        'grades',
        'point',
        'id_calculator',
        'group',
    ];

    protected $appends = [
        'count_point',
    ];
    public function getCountPointAttribute()
    {
        if ($this->grades != 10.0 && $this->grades != 15.0)
        return  round((float)$this->grades * (float)$this->point, 2) ;
    }
    /**
     * @var string
     */
    protected $screen = CALCULATOR_DETAIL;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function post()
    {
        return $this->belongsTo(Calculator::class);
    }
}
