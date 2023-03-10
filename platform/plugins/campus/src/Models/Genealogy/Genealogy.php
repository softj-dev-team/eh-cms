<?php

namespace Botble\Campus\Models\Genealogy;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Campus\Models\Evaluation\Major;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class Genealogy extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'genealogy';

    /**
     * @var array
     */
    protected $fillable = [
        'semester_year',
        'semester_session',
        'class_name',
        'exam_name',
        'professor_name',
        'detail',
        'file_upload',
        'link',
        'member_id',
        'images',
        'lookup',
        'published',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = GENEALOGY_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'images' => 'array',
        'file_upload' => 'array',
        'link' => 'array',
    ];

    public function comments()
    {
        return $this->hasMany(GenealogyComments::class, 'genealogy_id')->where("is_deleted",0)->orderBy('created_at', 'DESC');
    }

    public function getNameCategories($id)
    {
        $genealogyCategories = GenealogyCategories::where('id', $id)->where('status', 'publish')->first();
        return $genealogyCategories ?? "No have Categories";
    }

    public function getNameMemberById($id)
    {
        if ($id == null) {
            return "Admin";
        }

        $member = Member::find($id);
        if ($member == null) {
            return "Anonymous";
        }
        return $member->nickname;
    }

    // public function setImagesAttribute($value)
    // {
    //     if (is_array($value)) {
    //         $this->attributes['images'] = json_encode($value);
    //     }
    //     $this->attributes['images'] = $value;
    // }

    // public function getImagesAttribute($value)
    // {
    //     if ($value) {
    //         return json_decode($value, true);
    //     }

    //     return '';
    // }

    public function major()
    {
        return $this->belongsToMany(Major::class, 'major_genealogy')->orderBy('created_at', 'DESC');
    }

    // public function setFileUploadAttribute($value)
    // {
    //     if (is_array($value)) {
    //         $this->attributes['file_upload'] = json_encode($value);
    //     }
    //     $this->attributes['file_upload'] = $value;
    // }

    // public function getFileUploadAttribute($value)
    // {
    //     if ($value)
    //         return json_decode($value, true);
    //     return '';
    // }


    // public function setLinkAttribute($value)
    // {
    //     if (is_array($value)) {
    //         $this->attributes['link'] = json_encode($value);
    //     }
    //     $this->attributes['link'] = $value;
    // }

    // public function getLinkAttribute($value)
    // {
    //     if ($value)
    //         return json_decode($value, true);
    //     return '';
    // }


    public function scopeOrdered($query)
    {
        return $query
                ->withCount('comments')
                ->orderBy('status', 'desc')
                ->orderBy('published', 'desc')
                ->orderBy('lookup', 'desc')
                ->orderBy('comments_count', 'desc');
    }

    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_genealogy', 'genealogy_id', 'member_id');
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
