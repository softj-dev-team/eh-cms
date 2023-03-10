<?php

namespace Botble\Introduction\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Introduction\Models\Introduction
 *
 * @mixin \Eloquent
 */
class Introduction extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'introductions';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'detail',
        'link',
        'status',
        'categories_introductions_id',
    ];

    /**
     * @var string
     */
    protected $screen = INTRODUCTION_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function categories()
    {
        return $this->belongsTo(CategoriesIntroduction::class,'categories_introductions_id');
    }
}
