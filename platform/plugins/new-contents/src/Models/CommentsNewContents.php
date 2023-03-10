<?php

namespace Botble\NewContents\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\NewContents\Models\NewContents
 *
 * @mixin \Eloquent
 */
class CommentsNewContents extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments_new_contents';

    /**
     * @var array
     */
    protected $fillable = [
        'new_contents_id',
        'parents_id',
        'member_id',
        'anonymous',
        'content',
        'status',
    ];

    /**
     * @var string
     */
    protected $screen = COMMENTS_NEW_CONTENTS_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function getAllCommentByParentsID($id)
    {
        return $this->where('parents_id',$id)->get();
    }

    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id')->orderBy('created_at', 'DESC');
    }
}
