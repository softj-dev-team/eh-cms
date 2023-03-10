<?php

namespace Botble\Base\Http\Middleware;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Closure;
use Illuminate\Contracts\Foundation\Application;

class DisableInDemoModeMiddleware
{

    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    protected $app;

    /**
     * @var BaseHttpResponse
     */
    protected $httpResponse;

    /**
     * AdminBarMiddleware constructor.
     * @param Application $application
     * @param BaseHttpResponse $response
     */
    public function __construct(Application $application, BaseHttpResponse $response)
    {
        $this->app = $application;
        $this->httpResponse = $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    public function handle($request, Closure $next)
    {
        if (app()->environment('demo')) {
            return $this->httpResponse
                ->setError()
                ->withInput()
                ->setMessage(trans('core/base::system.disabled_in_demo_mode'))
                ->toResponse($request);
        }

        return $next($request);
    }
}
