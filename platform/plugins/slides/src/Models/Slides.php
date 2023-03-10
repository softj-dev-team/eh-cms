<?php

namespace Botble\Slides\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Gallery\Models\GalleryMeta;
use Carbon\Carbon;
use Eloquent;

/**
 * Botble\Slides\Models\Slides
 *
 * @mixin \Eloquent
 */
class Slides extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'slides';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'url',
        'type',
        'status',
        'start',
        'end',
    ];

    /**
     * @var string
     */
    protected $screen = SLIDES_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    //image
    public function setImageAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    public function getImageAttribute($value)
    {
        if($value)
            return strtoupper($value);
        return '';
    }

    public function getImageGallery(){
        $data = GalleryMeta::where('reference',SLIDES_MODULE_SCREEN_NAME)->where('content_id',$this->id)->first();
        $images = $data->images;
        $now = Carbon::now();
        foreach ($images as $index => $image){
            if (empty($images['start_date']) || empty($images['end_date'])){
                continue;
            }
            if ($now->lt($image['start_date']) && $now->gt($image['end_date'])){
                unset($images[$index]);
            }
        }
        $obj = new \stdClass();
        $obj->id = $data->id;
        $obj->content_id = $data->content_id;
        $obj->images = $images;
        $obj->reference = $data->reference;
        $obj->created_at = $data->created_at;
        $obj->updated_at = $data->updated_at;

       return  $obj;
    }
}
