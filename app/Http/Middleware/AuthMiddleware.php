<?php

/**
 * Author: Nguyễn Phan Dũng
 * Email: dung.nguyenphan@hdtvietnam.com.vn
 * Company: HDT Information Technology
 */

namespace App\Http\Middleware;

use App\Exceptions\TokenNotFoundException;
use Botble\Member\Models\Member;
use Botble\Member\Models\MemberToken;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class AuthMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next) {
        $token = $request->header('Authorization'); // get token from request header
        $token = str_replace('Bearer ', '', $token);

        if (!$token) {
            // Unauthorized response if token not there
            throw new TokenNotFoundException();
        }

        $memberToken = MemberToken::where('token', $token)
            ->where('status', MemberToken::LOGIN)
            ->first();

        if (!$memberToken) {
            throw new TokenNotFoundException();
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);

        } catch (ExpiredException $e) {
            throw new Exception('Token is expired');

        } catch (Exception $e) {
            throw $e;
        }

        $member = Member::find($credentials->sub);

        // Now let's put the user in the request class so that you can grab it from there
        if (!empty($member)) {
            $request->currentMember = $member;
            $request->currentMemberToken = $memberToken;

        } else {
            throw new TokenNotFoundException();
        }

        return $next($request);
    }
}
