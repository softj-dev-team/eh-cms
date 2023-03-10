<?php
namespace Botble\Garden\Models;

use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class PopularGarden extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'popular_gardens';

    /**
     * @var array
     */
    protected $fillable = [
        'keyword',
        'lookup',
        'categories_id',
        'today_lookup'
    ];

    /**
     * @var string
     */
    protected $screen = POPULAR_GARDEN_MODULE_SCREEN_NAME;

}
