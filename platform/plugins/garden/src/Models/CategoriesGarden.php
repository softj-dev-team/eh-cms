<?php

namespace Botble\Garden\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Carbon\Carbon;
use Eloquent;

/**
 * Botble\Garden\Models\Garden
 *
 * @mixin \Eloquent
 */
class CategoriesGarden extends Eloquent
{
    use EnumCastable;

    const PAST_GARDEN = 1;
    const LAW_GARDEN = 2;
    const JOB_GARDEN = 3;
    const GRADUATION_GARDEN = 4;
    const SECRET_GARDEN = 5;
    const SPROUT_GARDEN = 6;
    const E_GARDEN = 7;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories_gardens';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'level_access',
        'status',
        'special_garden'
    ];

    /**
     * @var string
     */
    protected $screen = CATEGORIES_GARDEN_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function garden()
    {
        return $this->hasMany(Garden::class,'categories_gardens_id')->withCount([
            'likes',
        ])->where('status','publish')->orderBy('created_at', 'DESC');
    }

    public function popular()
    {
        return $this->hasMany(PopularGarden::class,'categories_id')->orderBy('lookup', 'DESC');
    }

    public function todaySearch()
    {
        return PopularGarden::where('categories_id', $this->id)->where('today_lookup','>', 0)
            ->whereDate('updated_at',  Carbon::today())->orderBy('today_lookup', 'DESC')->take(5)->get();
    }

    public static function getSpecial()
    {
        return [
            self::PAST_GARDEN => '지난화원',
            self::LAW_GARDEN => '고시화원',
            self::JOB_GARDEN => '취업화원',
            self::GRADUATION_GARDEN => '졸업화원',
            self::SECRET_GARDEN => '비밀화원',
            self::SPROUT_GARDEN => '새싹 정원',
            self::E_GARDEN => 'E-garden',
        ];
    }
}
