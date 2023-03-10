<?php

namespace Botble\Member\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class MemberNotes extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_notes';

    /**
     * @var array
     */
    protected $fillable = [
        'member_from_id',
        'member_to_id',
        'contents',
    ];

    /**
     * @var string
     */
    protected $screen = MEMBER_NOTES_MODULE_SCREEN_NAME;


    public function member_from()
    {
        return $this->belongsTo(Member::class, 'member_from_id')->orderBy('created_at', 'DESC');
    }
    public function member_to()
    {
        return $this->belongsTo(Member::class, 'member_to_id')->orderBy('created_at', 'DESC');
    }

}
