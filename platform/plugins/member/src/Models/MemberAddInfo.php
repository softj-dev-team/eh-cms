<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class MemberAddInfo extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_addinfo';

    /**
     * @var array
     */
    protected $fillable = [
        'mem_idx',
        'mem_email',
        'mem_addr',
        'mem_post',
        'mem_phone',
    ];

}
