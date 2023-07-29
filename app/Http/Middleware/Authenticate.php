<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $prefix = trim($request->route()->action['prefix'],'/');
        if(request()->routeIs('admin.*')) {
            $routeName = 'admin.login';
        }

        if(request()->routeIs('portal.*')) {
            $routeName = 'portal.login';
        }

        if(request()->routeIs('api.*')) {
            return '';
        }
        return route($routeName);
    }
}
