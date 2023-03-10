<?php

namespace Botble\Garden\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Garden\Models\Garden
 *
 * @mixin \Eloquent
 */
class Ewha extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *^
     * @var string
     */
    protected $table = 'EWHA_BOARD_POST';

    protected $primaryKey = 'BP_IDX';

    /**
     * @var array
     */
    protected $fillable = [
        'BP_IDX',
        'BP_TITLE',
        'BP_CONTENT',
    ];

    /**
     * @var string
     */
    protected $screen = EWHA_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
    ];
}
