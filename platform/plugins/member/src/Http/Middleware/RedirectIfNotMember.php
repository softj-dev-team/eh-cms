<?php

namespace Botble\Member\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use IlluminateAgnostic\Collection\Support\Arr;

class RedirectIfNotMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'member')
    {
        if (!Auth::guard($guard)->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }
            return redirect()->guest(route('public.member.login'));
        }
        else{
            $route = $request->route()->getAction();
            $flag = Arr::get($route, 'permission', Arr::get($route, 'as'));
            $member = auth()->guard('member')->user();

            if(strpos($flag, 'public.member.') !== false ) return $next($request);
            if( !$member->hasPermission($flag)) {
                return redirect('/')->with('permission', __('home.no_permisson'));
            }
        }

        return $next($request);
    }
}
