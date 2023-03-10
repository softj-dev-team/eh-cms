<?php

namespace Botble\Campus\Models\OldGenealogy;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class OldGenealogyComments extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'old_genealogy_comments';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'old_genealogy_id',
        'parents_id',
        'member_id',
        'anonymous',
        'content',
        'status',
        'ip_address',
        'file_upload'
    ];

    /**
     * @var string
     */
    protected $screen = OLD_GENEALOGY_COMMENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
    public function getAllCommentByParentsID($id)
    {
        return $this->where('parents_id', $id)
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
        return $this->belongsToMany(Member::class, 'sympathy_old_genealogy_comments', 'comments_id', 'member_id');
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
