<?php

/**
 * Author: Nguyễn Phan Dũng
 * Email: dung.nguyenphan@hdtvietnam.com.vn
 * Company: HDT Information Technology
 */

namespace Botble\Member\Models;

use Illuminate\Database\Eloquent\Model;

class MemberToken extends Model
{
    const LOGIN = 'login';
    const LOGOUT = 'logout';

    protected $table = 'member_token';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id', 'token', 'location', 'device', 'status'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
