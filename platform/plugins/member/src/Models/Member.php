<?php

namespace Botble\Member\Models;

use App\MemberSetting;
use Botble\ACL\Models\Role;
use Botble\ACL\Models\User;
use Botble\Events\Models\Events;
use Laravel\Passport\HasApiTokens;
use Botble\Contents\Models\Contents;
use Botble\Member\Supports\Gravatar;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Garden\Models\Egarden\Room;
use Illuminate\Notifications\Notifiable;
use Botble\Campus\Models\Schedule\Schedule;
use Botble\Garden\Models\Egarden\RoomMember;
use Trendsoft\LaravelBookmark\Traits\Bookmarker;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Botble\Member\Notifications\MemberResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use Bookmarker;

    const IS_BLACKLIST = 1;
    const IS_NOT_BLACKLIST = 0;

    /**
     * @var string
     */
    protected $table = 'members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'dob',
        'phone',
        'confirmed_at',
        'description',
        'gender',
        'fullname',
        'namemail',
        'domainmail',
        'nickname',
        'id_login',
        'role_member_id',
        'passwd_garden',
        'certification',
        'point',
        'freshman1',
        'freshman2',
        'student_number',
        'status_fresh1',
        'status_fresh2',
        'id',
        'passwd_enc',
        'verify_at',
        'is_blacklist',
        'note_freshman1',
        'note_freshman2',
        'auth_studentid',
        'update_freshman1',
        'update_freshman2',
        'sprouts_number',
        'is_active',
        'last_login',
        'email_verify_code',
        'email_verified',
        'reason_reject_1',
        'reason_reject_2',
        'block_user',
        'start_block_time',
        'end_block_time',
        'block_reason',
        'count_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'level',
        'account_image',
        'bookmark_limit',
    ];

    protected $memberToken;

    public function getLevelAttribute()
    {
        return round(sqrt(($this->point * 1.2) / 15) + 1);
    }

    public function getAccountImageAttribute()
    {
        $level = round(sqrt(($this->point * 1.2) / 15) + 1);
        switch (true) {
            case 0 < $level && $level < 10:
                return '/themes/ewhaian/img/lvl_img/lv,1~9.png';
                break;
            case 10 <= $level && $level < 30:
                # code...
                return '/themes/ewhaian/img/lvl_img/lv,10~29.png';
                break;
            case 30 <= $level && $level < 60:
                # code...
                return '/themes/ewhaian/img/lvl_img/lv,30~59.png';
                break;
            case 60 <= $level && $level < 100:
                # code...
                return '/themes/ewhaian/img/lvl_img/lv,60~99.png';
                break;
            case 100 <= $level:
                # code...
                return '/themes/ewhaian/img/lvl_img/lv,100~.png';
                break;
            default:
                # code...
                return '/themes/ewhaian/img/avatar.png';
                break;
        }
    }

    public function getBookmarkLimitAttribute()
    {
        $level = $this->level;
        $bookmarkLimit = 0;
        $limitConfigs = config('plugins.member.general.bookmark_limit');
        foreach ($limitConfigs as $key => $limitConfig) {
            if ($level < $key) {
                $bookmarkLimit = $limitConfig;
                break;
            }
        }

        return $bookmarkLimit;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MemberResetPassword($token));
    }

    /**
     * @return string
     */
    public function getAvatarAttribute()
    {
        if (!$this->attributes['avatar']) {
            return (new Gravatar())->image($this->attributes['email']);
        }
        return url($this->attributes['avatar']);
    }

    /**
     * Always capitalize the first name when we retrieve it
     * @param string $value
     * @return string
     * @author Sang Nguyen
     */
    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Always capitalize the last name when we retrieve it
     * @param string $value
     * @return string
     * @author Sang Nguyen
     */
    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * @return string
     * @author Sang Nguyen
     */
    public function getFullName()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * @return string
     * @author Sang Nguyen
     */
    public function getProfileImage()
    {
        return $this->getAvatarAttribute();
    }

    /**
     * @return MorphTo
     */
    public function posts()
    {
        return $this->morphMany('Botble\Blog\Models\Post', 'author');
    }
    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_member_id');
    }

    public function hasPermission($permission)
    {
        if ($this->roles == null) return false;
        foreach ($this->roles->permissions as $key => $item) {
            if ($key == $permission) {
                if ($item == true) return true;
                else return false;
            }
        }
    }

    public function memberToken()
    {
        return $this->hasMany(MemberToken::class, 'member_id', 'id');
    }
    public function memberSetting()
    {
        return $this->hasOne(MemberSetting::class, 'member_id', 'id');
    }

    public function events()
    {
        return $this->hasMany(Events::class, 'member_id');
    }

    public function contents()
    {
        return $this->hasMany(Contents::class, 'member_id');
    }

    public function schedule()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_share');
    }

    public static function isBlackList()
    {
        return [
            self::IS_BLACKLIST => 'Yes',
            self::IS_NOT_BLACKLIST => 'No',
        ];
    }

    public function roomJoined()
    {
        return $this->belongsToMany(Room::class, 'room_member')
            ->whereIn('room.status', [BaseStatusEnum::PUBLISH(), BaseStatusEnum::CAN_APPLY()])
            ->where('room_member.status', 'publish');
    }

    public function roomCreated()
    {
        return $this->hasMany(Room::class, 'member_id')
            ->whereIn('room.status', [BaseStatusEnum::PUBLISH(), BaseStatusEnum::CAN_APPLY()]);
    }

    public function memberNotify()
    {
        return $this->hasMany(MemberNotify::class, 'member_id');
    }

    public function getMemberToken()
    {
        return $this->memberToken;
    }

    public function setMemberToken($memberToken)
    {
        $this->memberToken = $memberToken;
    }

    public static function createMemberFromUser($userId)
    {
        $user = User::find($userId);

        // check member exist
        $member = Member::where('id_login', $user->username)
            ->where('is_active', 1)
            ->where('email_verified', 1)
            ->get()->first();

        if($member){
            return true;
        }

        // create member
        $dataInsert =  [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => $user->password,
            'avatar' => $user->profile_image,
            'dob' => $user->dob,
            'phone' => $user->phone,
            'confirmed_at' => today(),
            'description' => $user->description ?? null,
            'gender' => $user->gender ?? null,
            'passwd_garden' => null, // access garden
            'certification' =>  $user->super_user == 1 ? 'certification' : 'real_name_certification',
            'fullname' => $user->fullname ?? ($user->first_name . ' ' . $user->last_name),
            'namemail' => $user->namemail ?? null,
            'domainmail' => $user->domainmail ?? null,
            'nickname' => $user->nickname ?? $user->first_name,
            'id_login' => $user->username,
            'role_member_id' => $user->super_user == 1 ? 7 : 1,
            'verify_at' => today(),
            'is_active' => 1,
            'email_verified' => 1,
        ];

        Member::insert($dataInsert);
    }
}
