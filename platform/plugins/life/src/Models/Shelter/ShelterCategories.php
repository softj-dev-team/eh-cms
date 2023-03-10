<?php

namespace Botble\Life\Models\Shelter;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class ShelterCategories extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shelter_categories';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_id',
        'background',
        'color',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = SHELTER_CATEGORIES_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
