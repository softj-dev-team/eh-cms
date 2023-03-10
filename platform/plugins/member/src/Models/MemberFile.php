<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class MemberFile extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_file';

    /**
     * @var array
     */
    protected $fillable = [
        'f_idx',
        'mem_idx',
        'folder_idx',
        'f_name',
        'f_update',
        'f_upfilename',
        'f_upfileext',
        'f_date',
        'f_size',
        'f_open',
        'f_count',
    ];

}
