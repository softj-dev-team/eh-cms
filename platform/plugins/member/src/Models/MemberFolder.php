<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class MemberFolder extends Eloquent
{
    use EnumCastable;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_folder';

    /**
     * @var array
     */
    protected $fillable = [
        'folder_idx',
        'mem_idx',
        'folder_name',
        'folder_date',
        'folder_open',
    ];

}
