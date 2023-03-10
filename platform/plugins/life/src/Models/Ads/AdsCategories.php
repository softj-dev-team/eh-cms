<?php

namespace Botble\Life\Models\Ads;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class AdsCategories extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ads_categories';

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
    protected $screen = ADS_CATEGORIES_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
