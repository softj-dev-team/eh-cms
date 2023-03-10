<?php

namespace Botble\Introduction\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Introduction\Models\Introduction
 *
 * @mixin \Eloquent
 */
class CategoriesIntroduction extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories_introductions';

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
    protected $screen = CATEGORIES_INTRODUCTION_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
    public function intro()
    {
        return $this->hasMany(Introduction::class,'categories_introductions_id')->orderBy('created_at','DESC');
    }

    public function getFirstIntro()
    {
        return Introduction::where('categories_introductions_id',$this->id)->where('status','publish')->orderBy('created_at','DESC')->firstOrFail();
    }
}
