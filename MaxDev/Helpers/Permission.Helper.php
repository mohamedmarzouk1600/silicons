<?php

function BypassPermissions()
{
    return [
        'admin.logout',
        'admin.login',
        'admin.login.submit',
        'admin.profile',
        'admin.no-access',
        'admin.set.user.status',
    ];
}


function AdminCan($routeName, $AdminId = null)
{
    $userObj = ((isset($AdminId)) ? \MaxDev\Models\Admin::where('id', $AdminId)->first() : auth('admin')->user());
    if (!$userObj) {
        return false;
    }
    static $UserPermissions;
    if (is_null($UserPermissions)) {
        $permissions = optional(optional(\Illuminate\Support\Facades\Cache::get('adminGroup_'.$userObj->admin_group_id))->pluck('route_name'))->toArray();
        if (!$permissions) {
            $permissions = \Illuminate\Support\Facades\Cache::rememberForever('adminGroup_'.$userObj->adminGroup->id, function () use ($userObj) {
                return $userObj->adminGroup->permissions;
            })->pluck('route_name')->toArray();
        }
        $UserPermissions = array_merge($permissions ?? $userObj->permissions->pluck('route_name')->toArray(), BypassPermissions());
    }
    if (is_array($routeName)) {
        $arr = array_diff($routeName, $UserPermissions);
        return (!$arr) ? true : ((count($arr) == count($routeName)) ? false : true);
    } else {
        return (in_array($routeName, $UserPermissions)) ? true : false;
    }
}

function GetRoutes()
{
    $routes = collect(\Route::getRoutes())->map(function ($route) {
        if (strpos($route->getName(), 'admin.')===0) {
            return $route->getName();
        }
    })->toArray();
    return array_filter($routes);
}
