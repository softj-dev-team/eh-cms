<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class AccessGarden
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->is('garden/*') && !$request->is('egarden/*')) {
            Cookie::queue(Cookie::forget('password_garden'));
        };
        return $next($request);
    }
}
