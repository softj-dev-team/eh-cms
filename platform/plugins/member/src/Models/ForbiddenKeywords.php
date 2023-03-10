<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class ForbiddenKeywords extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'forbidden_keywords';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'type',
        'status',
    ];

    public function scopeTitle($query)
    {
        return $query->where('status', 'publish');
    }

}
