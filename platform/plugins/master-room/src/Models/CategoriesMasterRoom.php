<?php

namespace Botble\MasterRoom\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\MasterRoom\Models\MasterRoom
 *
 * @mixin \Eloquent
 */
class CategoriesMasterRoom extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories_master_rooms';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = CATEGORIES_MASTER_ROOM_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
