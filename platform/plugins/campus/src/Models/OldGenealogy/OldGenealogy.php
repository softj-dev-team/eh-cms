<?php

namespace Botble\Campus\Models\OldGenealogy;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class OldGenealogy extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'old_genealogy';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'detail',
        'member_id',
        'images',
        'lookup',
        'published',
        'status',
        'file_upload',
        'link',
    ];

    /**
     * @var string
     */
    protected $screen = OLD_GENEALOGY_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array',
    ];

    public function comments()
    {
        return $this->hasMany(OldGenealogyComments::class, 'old_genealogy_id')->where("is_deleted",0)->orderBy('created_at', 'DESC');
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
        if ($value) {
            return json_decode($value, true);
        }

        return '';
    }

    public function scopeOrdered($query)
    {
        return $query
                ->withCount('comments')
                ->orderBy('published', 'desc')
                ->orderBy('lookup', 'desc')
                ->orderBy('comments_count', 'desc');
    }

}
