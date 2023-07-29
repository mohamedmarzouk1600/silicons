<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class SetMobileHeaders
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
        if (request()->routeIs('api.*') && auth('patient')->check()) {

            auth('patient')->user()->update(array_filter([
                'platform'=>$request->hasHeader('platform') ? $request->header('platform') : null,
                'language'=>$request->hasHeader('language') ? $request->header('language') : null,
                'version'=>$request->hasHeader('app-version') ? $request->header('app-version') : "1.0.8+38",
            ]));
        }

        return $next($request);
    }
}
