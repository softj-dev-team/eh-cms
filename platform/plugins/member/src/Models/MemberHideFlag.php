<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class MemberHideFlag extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_hide_flag';

    /**
     * @var array
     */
    protected $fillable = [
        'mem_idx',
        'gong_flag',
    ];

}
