<?php

namespace Botble\Contents\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Contents\Models\Contents
 *
 * @mixin \Eloquent
 */
class CategoriesContents extends Eloquent
{
    use EnumCastable;

    const MULTICULTURE = 1;
    const CULTURAL_SYMPATHY = 2;
    const FINE_NOTEBOOK = 3;
    const WRITTEN_NOTE = 4;
    const CONTRIBUTION = 5;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories_contents';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'notice',
        'description',
        'status',
        'permisions'
    ];

    /**
     * @var string
     */
    protected $screen = CATEGORIES_CONTENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function contents()
    {
        return $this->hasMany(Contents::class,'categories_contents_id')->orderby('created_at','DESC');
    }
    public function slidesContents()
    {
        return $this->hasMany(Contents::class,'categories_contents_id')->where('is_slides','>',Contents::IS_NOT_SLIDE)->orderby('slide_no','DESC');
    }
    public static function getPermissions()
    {
        return [
            self::MULTICULTURE => '지난컨텐츠',
            self::CULTURAL_SYMPATHY => '기고만장',
            self::FINE_NOTEBOOK => '화연수첩',
            self::WRITTEN_NOTE => '문화공감',
            self::CONTRIBUTION => '다인다색',
        ];
    }
}
