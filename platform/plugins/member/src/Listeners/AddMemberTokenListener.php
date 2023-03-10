<?php

namespace Botble\Member\Listeners;

use Botble\Member\Models\Member;
use Botble\Member\Models\MemberToken;
use Illuminate\Auth\Events\Authenticated;

class AddMemberTokenListener
{
    /**
     * @param Authenticated $auth
     */
    public function handle($auth) {
        $token = session()->get('member_token');
        $memberToken = MemberToken::where('token', $token)->first();

        if (isset($memberToken) && method_exists($auth->user, 'setMemberToken')) {
            $auth->user->setMemberToken($memberToken);
        }
    }
}
