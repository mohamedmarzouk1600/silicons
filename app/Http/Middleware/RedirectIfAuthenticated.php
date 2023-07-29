<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check() && $request->route()->getName() == 'admin.login.submit') {
//            return redirect(RouteServiceProvider::HOME);
            $route = AdminHomeUrl($guard);
            if ($route) {
                if (count($route) > 1) {
                    return redirect()->route($route[0], $route[1]);
                } else {
                    return redirect()->route($route[0]);
                }
            } else {
                return redirect()->route(AdminCan('admin.dashboard') ? 'admin.dashboard' : 'admin.no-access');
            }
        }
        return $next($request);
    }
}
