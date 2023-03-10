<?php

namespace Botble\Member\Models;

use Botble\Member\Models\Member;
use Illuminate\Database\Eloquent\Model;

class MemberSetting extends Model
{
    protected $table = 'member_settings';
    //
    protected $fillable = [
        'member_id',
        'site_notice',
        'eh_content',
        'bulletin_comment_on_post',
        'bulletin_comment_on_comment',
        'secret_garden_comment_on_post',
        'secret_garden_comment_on_comment',
        'garden_notice',
        'garden_new_post',
        'garden_comment_on_post',
        'garden_comment_on_comment',
        'message_notification',
    ];
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
