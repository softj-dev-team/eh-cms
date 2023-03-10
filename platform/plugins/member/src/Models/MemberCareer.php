<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class MemberCareer extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_career';

    /**
     * @var array
     */
    protected $fillable = [
        'mem_idx',
        'login_count',
        'good_s_count',
        'gubak_s_count',
        'good_r_count',
        'gubak_r_count',
        'board_count',
        'reple_count',
        'biwon_board_count',
        'biwon_reple_count',
        'hooli_s_count',
        'hooli_r_count',
        'last_login',
        'first_date',
        'rgood_r_count',
        'rgood_s_count',
        'rgubak_r_count',
        'rgubak_s_count',
        'bgood_r_count',
        'bgood_s_count',
        'bgubak_r_count',
        'bgubak_s_count',
        'brgood_r_count',
        'brgood_s_count',
        'brgubak_r_count',
        'brgubak_s_count',
    ];

}
