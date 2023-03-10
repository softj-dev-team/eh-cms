<?php

namespace Botble\Garden\Models\Notices;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Garden\Models\CategoriesGarden;
use Eloquent;

/**
 * Botble\Garden\Models\Garden
 *
 * @mixin \Eloquent
 */
class NoticesGarden extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notices_gardens';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'categories_gardens_id',
        'link',
        'notices',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = NOTICES_GARDEN_MODULE_SCREEN_NAME;

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
