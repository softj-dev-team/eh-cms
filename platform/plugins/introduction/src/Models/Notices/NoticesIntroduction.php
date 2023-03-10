<?php

namespace Botble\Introduction\Models\Notices;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Contents\Models\CommentsContents;
use Eloquent;

/**
 * Botble\Introduction\Models\Introduction
 *
 * @mixin \Eloquent
 */
class NoticesIntroduction extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notices_introduction';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'notices',
        'lookup',
        'status',
        'code',
        'allow_comment'
    ];

    /**
     * @var string
     */
    protected $screen = NOTICES_INTRODUCTION_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'code'   => 'array',
    ];

    public function scopeCode($query, $code)
    {
        return $query->whereRaw('json_contains(code, \'["' . $code . '"]\')');
    }

    public function comments()
    {
        return $this->hasMany(CommentsNoticeIntroduction::class,'notice_introduction_id')->where("is_deleted",0)->orderBy('created_at', 'DESC');
    }
}
