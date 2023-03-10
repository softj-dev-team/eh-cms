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
class GardenDetail extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'garden_details';

    /**
     * @var array
     */
    protected $fillable = [
        'garden_id',
        'ip_address'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
