<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class MemberCurrentIp extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_current_ip';

    /**
     * @var array
     */
    protected $fillable = [
        'mem_idx',
        'mem_ip',
        'mem_date',
        'random_id',
    ];

}
