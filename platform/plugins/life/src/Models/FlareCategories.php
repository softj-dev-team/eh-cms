<?php

namespace Botble\Life\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class FlareCategories extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories_market';

    /**
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'background',
        'color',
        'name',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = FLARE_CATEGORIES_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    
    public function parent()
    {
        return $this->belongsTo(FlareCategories::class,'parent_id')->where('status','publish');
    }

    public function children($id)
    {
        return $this->where('parent_id',$id)->where('status','publish')->get();
    }


}
