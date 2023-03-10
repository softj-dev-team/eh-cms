<?php

namespace Botble\Garden\Models\Description;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Garden\Models\CategoriesGarden;
use Eloquent;

/**
 * Botble\Garden\Models\Garden
 *
 * @mixin \Eloquent
 */
class DescriptionGarden extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'description_gardens';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'categories_gardens_id',
        'description',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = DESCRIPTION_GARDEN_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function categories()
    {
        return $this->belongsTo(CategoriesGarden::class, 'categories_gardens_id')->where('status', 'publish')->orderBy('created_at', 'DESC');
    }
}
