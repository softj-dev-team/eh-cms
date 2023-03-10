<?php

namespace Botble\Campus\Models\Genealogy;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class MajorGenealogy extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'major_geneagoly';

    /**
     * @var array
     */
    protected $fillable = [
        'genealogy_id',
        'major_id',
        'status',

    ];

    /**
     * @var string
     */
    protected $screen = MAJOR_GENEALOGY_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function genealogy()
    {
        return $this->belongsToMany(Genealogy::class, 'major_geneagoly');
    }

}
