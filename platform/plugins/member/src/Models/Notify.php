<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class Notify extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notify';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'is_group',
        'user_id',
        'status',
    ];

    public function memberNotify()
    {
        return $this->hasMany(MemberNotify::class, 'notify_id')->with('member');
    }

}
