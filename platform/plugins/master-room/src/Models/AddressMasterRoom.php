<?php

namespace Botble\MasterRoom\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\MasterRoom\Models\MasterRoom
 *
 * @mixin \Eloquent
 */
class AddressMasterRoom extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'address_master_rooms';

    /**
     * @var array
     */
    protected $fillable = [
        'classification',
        'email',
        'home_page',
        'name',
        'address',
        'home_phone',
        'mobile_phone',
        'company_phone',
        'member_id',
        'published',
        'memo',
        'status',
        'address_id'
    ];

    /**
     * @var string
     */
    protected $screen = ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
