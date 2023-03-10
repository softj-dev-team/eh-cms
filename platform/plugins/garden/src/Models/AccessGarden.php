<?php

namespace Botble\Garden\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Carbon\Carbon;
use Eloquent;

/**
 * Botble\Garden\Models\Garden
 *
 * @mixin \Eloquent
 */
class AccessGarden extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'access_garden';

    /**
     * @var array
     */
    protected $fillable = [
        'member_id',
        'time_access_from',
        'time_access_to',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = ACCESS_GARDEN_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function member()
    {
        return $this->hasOne(Member::class,'member_id');
    }


}
