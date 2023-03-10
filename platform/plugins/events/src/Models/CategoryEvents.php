<?php

namespace Botble\Events\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;


/**
 * Botble\Events\Models\Events
 *
 * @mixin \Eloquent
 */
class CategoryEvents extends Eloquent
{
    use EnumCastable;

    const EVENT = 1;
    const EVENT_SKETCH = 2;
    const AFFLIATION_EVENT = 3;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category_events';

    /**
     * @var array
     */
    protected $fillable = [
            'id',
            'name',
            'status',
            'permisions'
    ];

    /**
     * @var string
     */
    protected $screen = CATEGORY_EVENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function events()
    {
        return $this->hasMany(Events::class,'category_events_id')->where('status','publish')->orderby('published','DESC');
    }

    public static function getPermisions()
    {
        return [
            self::EVENT => '이벤트',
            self::EVENT_SKETCH => '이벤트 스케치',
            self::AFFLIATION_EVENT => '제휴이벤트',
        ];
    }
}
