<?php
namespace Botble\Garden\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class CommentsGarden extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments_gardens';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'gardens_id',
        'parents_id',
        'member_id',
        'anonymous',
        'content',
        'ip_address',
        'status',
        'is_deleted',
        'file_upload'
    ];

    /**
     * @var string
     */
    protected $screen = COMMENTS_GARDEN_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
    public function getAllCommentByParentsID($id)
    {
        return $this->where('parents_id',$id)
        ->withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])
        ->get();
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
    public function check_sympathy()
    {
        return $this->belongsToMany(Member::class, 'sympathy_garden_comments', 'comments_id', 'member_id');
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
