<?php

namespace Botble\Garden\Models\Egarden;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Member\Models\Member;
use Botble\Theme\Theme;
use Eloquent;

/**
 * Botble\Garden\Models\Garden
 *
 * @mixin \Eloquent
 */
class Room extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'room';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'member_id',
        'description',
        'images',
        'status',
        'file_upload',
        'link',
        'cover',
        'important'
    ];

    /**
     * @var string
     */
    protected $screen = ROOM_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'file_upload' => 'array',
        'link' => 'array',
    ];

    protected $appends = [
        'isnew','routejoin','isjoined'
    ];

    public function getIsNewAttribute()
    {
        return $this->created_at->isToday();
    }
    public function getRouteJoinAttribute()
    {
        return route('egardenFE.ajaxJoinRoom',['id'=> $this->id]);
    }
    public function getIsJoinedAttribute()
    {
        if(is_null(auth()->guard('member')->user())) {
           return false;
        }
        $joined = auth()->guard('member')->user()->roomJoined;
        $roomCreated = auth()->guard('member')->user()->roomCreated;

        if($joined->contains('id', $this->id) || $roomCreated->contains('id', $this->id) ){
            return true;
        }
        return false;
    }
    public function member()
    {
        return $this->belongsToMany(Member::class, 'room_member', 'room_id', 'member_id')
                    ->where('status', 'publish')->withTimestamps()->orderBy('member_id', 'DESC');
    }

    public function author()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function statusMember($member_id)
    {
        $room = RoomMember::where('room_id', $this->id)->where('member_id', $member_id)->first();
        if ($room == null) {
            if ($this->member_id == $member_id) {
                return 'publish';
            }
            return 'draft'; // join us
        } else {
            return $room->status;
        }
    }

    public function egarden()
    {
        return $this->hasMany(Egarden::class, 'room_id')->where('status', 'publish');
    }

    public function approvedMember()
    {
        return $this->belongsToMany(Member::class, 'room_member', 'room_id', 'member_id')->where('status', 'pending')->withTimestamps();
    }

    public function getMemberJoined()
    {
        return RoomMember::where('room_id', $this->id)->where('member_id', auth()->guard('member')->user()->id)->first();
    }
    public function categoreis()
    {
        return $this->hasMany(CategoriesRoom::class, 'room_id');
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
    public function getStatusMember($id){
        $member = Member::find($id);
        if ($member == null) {
            return "real_name_certification";
        }
        return $member->certification;
    }

}
