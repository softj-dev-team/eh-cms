<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class MemberFreshToEwhainLog extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_fresh_to_ewhain_log';

    /**
     * @var array
     */
    protected $fillable = [
        'mem_idx',
        'fresh_num',
        'mem_num',
    ];

}
