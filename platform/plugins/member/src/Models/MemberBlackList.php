<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Model;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin Model
 */
class MemberBlackList extends Model
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_blacklist';

    /**
     * @var array
     */
    protected $fillable = [
        'member_id',
    ];

}
