<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class MemberAuthKey extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_auth_key';

    /**
     * @var array
     */
    protected $fillable = [
        'mem_idx',
        'auth_key',
        'biwon_key',
        'reg_date',
    ];

}
