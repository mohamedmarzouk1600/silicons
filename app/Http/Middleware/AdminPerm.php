<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 03 ON 16 Oct 2018
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace App\Http\Middleware;

use Closure;

class AdminPerm
{
    public function handle($request, Closure $next){
        if(request()->routeIs('admin.*')) {
            if (auth('admin')->check()) {
                $role = request()->route()->getName();

                if (auth('admin')->user()->adminGroup->status == 0) {
                    auth('admin')->logout();
                    return redirect()->route('admin.login')->with('msg', __('Admin group disabled'));
                }

                if (!AdminCan($role)) {
                    return redirect()->route('admin.dashboard')->with(['msg' => __('No permission to access this page')]);
                }
            }
        }
        return $next($request);
    }

}
