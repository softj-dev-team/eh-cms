<?php

namespace Botble\Campus\Models\Description;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class DescriptionCampus extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'description_campus';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = DESCRIPTION_CAMPUS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
