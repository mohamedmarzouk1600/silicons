<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Language
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
        if (request()->routeIs('api.*')) {
            if ($language = $request->header('language')) {
                $language = in_array($language, config('app.locales')) ? $language : 'en';
                app()->setLocale($language);
            }
        }

        if(request()->routeIs('admin.*') || request()->routeIs('portal.*')) {
            if (isset($GLOBALS['Local'])) {
                session(['locale'=>$GLOBALS['Local']]);
            }
            app()->setLocale(session('locale', auth()->user()->language ?? config('app.fallback_locale')));
        }

        return $next($request);
    }
}
