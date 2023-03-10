<?php

namespace Botble\Introduction\Models\Faq;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Introduction\Models\Introduction
 *
 * @mixin \Eloquent
 */
class FaqIntroduction extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'faq_introduction';

    /**
     * @var array
     */
    protected $fillable = [
        'faq_categories_id',
        'question',
        'answer',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = FAQ_INTRODUCTION_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function categories()
    {
        return $this->belongsTo(FaqCategories::class,'faq_categories_id');
    }
}
