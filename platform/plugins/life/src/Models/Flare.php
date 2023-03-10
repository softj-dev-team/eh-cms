<?php

namespace Botble\Life\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

use function GuzzleHttp\json_encode;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class Flare extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'flare_market';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'categories',
        'purchasing_price',
        'reason_selling',
        'sale_price',
        'exchange',
        'contact',
        'detail',
        'member_id',
        'images',
        'lookup',
        'published',
        'status',
        'file_upload',
        'link',
        'purchase_date',
        'purchase_location',
        'quality',
        'product',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'file_upload' => 'array',
        'link' => 'array',
    ];

    /**
     * @var string
     */
    protected $screen = FLARE_MODULE_SCREEN_NAME;

    public function setCategoriesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['categories'] = json_encode($value);
        }
        $this->attributes['categories'] = $value;
    }

    public function getCategoriesAttribute($value)
    {
        if ($value)
            return json_decode($value, true);
        return '';
    }

    public function setExchangeAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['exchange'] = json_encode($value);
        }
        $this->attributes['exchange'] = $value;
    }

    public function getExchangeAttribute($value)
    {
        if ($value)
            return json_decode($value, true);
        return '';
    }
    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['images'] = json_encode($value);
        }
        $this->attributes['images'] = $value;
    }

    public function getImagesAttribute($value)
    {
        if ($value)
            return json_decode($value, true);
        return '';
    }

    public function getNameMemberById($id)
    {
        if ($id == null) return "Admin";
        $member = Member::find($id);
        if ($member == null) {
            return "Anonymous";
        }
        return $member->nickname;
    }

    public function getStatusMember($id){
        $member = Member::find($id);
        if ($member == null) {
            return "real_name_certification";
        }
        return $member->certification;
    }

    public function getCategories($parent_id=1)
    {

        foreach ($this->categories as $key => $value) {
            $categories = FlareCategories::where('id',$value)->where('status', 'publish')->first();
            if ($categories->parent_id == $parent_id) {
                return $categories;
            }
        }
        return false;
    }

    public function comments()
    {
        return $this->hasMany(FlareComments::class, 'flare_id')->where("is_deleted",0)->orderBy('created_at', 'DESC');
    }
    public function getCategoriesByID($id){
        $categories = FlareCategories::where('id',$id)->where('status', 'publish')->first();
        return $categories;
    }
    public function scopeOrdered($query)
    {
        return $query
                ->withCount('comments')
                ->orderBy('published', 'desc')
                ->orderBy('views', 'desc')
                ->orderBy('comments_count', 'desc');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_flare_market', 'flare_market_id', 'member_id');
    }

    public function dislikes()
    {
        return $this->check_sympathy()->where('is_dislike', 1);
    }

    public function likes()
    {
        return $this->check_sympathy()->where('is_dislike', '!=', 1);
    }
}
