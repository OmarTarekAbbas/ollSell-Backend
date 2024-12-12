<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\CoreData\Entities\Notification;
/**
 * @Target this file to make function to help for all system
 * @note can call it in all system
 */
/**
 * user login
 */
function user()
{
    if (Auth::guard('web')->check()) {
        return Auth::guard('web')->user();
    } elseif (Auth::guard('dropshipper')->check()) {
        return Auth::guard('dropshipper')->user();
    } elseif (Auth::guard('supplier')->check()) {
        return Auth::guard('supplier')->user();
    }
    return false;
}

function dropshipperAuth()
{
    if (Auth::guard('dropshipper')->check()) {
        return Auth::guard('dropshipper')->user();
    }
}

function dashboardAuth()
{
    if (Auth::guard('supplier')->check()) {
        return Auth::guard('supplier')->user();
    }
}

function supplierAuth()
{
    if (Auth::guard('web')->check()) {
        return Auth::guard('web')->user();
    }
}

function checkView($view)
{
    return view()->exists($view) ? $view : 'errors.500';
}

/**
 * to execution time for web
 */
function executionTime()
{
    ini_set('max_execution_time', 120000);
    ini_set('post_max_size', 120000);
    ini_set('upload_max_filesize', 100000);
}

function permissionShow($name): int
{
    return \Illuminate\Support\Facades\DB::table('permissions')
        ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id')
        ->where('permission_role.role_id', user()->role->role_id ?? 0)
        ->where('permissions.name', $name)
        ->count();
}


if (!function_exists('can')) {
    function can($permission)
    {
        if (!Auth::guard('web')->user()->can($permission)) {
            abort(401);
        }
        return true;
    }
}

if (!function_exists('updateSettings')) {
    function updateSettings($key, $value)
    {
        return Setting::updateOrCreate(
            [
                'key' => $key
            ],
            [
                'value' => $value,
            ]
        );
    }
}

//todo move from here
function getUnreadNotifications($limit = 10)
{
    $user = auth()->user();

    if ($user) {
        return $user->notifications()
            ->latest()
            ->limit($limit)
            ->get();
    }

    return collect();
}

/**
 * The function reads notifications for a specific user and marks them as seen.
 * 
 * @param id The parameter `` is not used in the code provided. It is passed to the function but not
 * utilized within the function body.
 * 
 * @return a collection of notifications for the authenticated user.
 */
function readNotifications($id)
{
    $user = auth()->user();
    if ($user) {
        $notification = Notification::find($id);
        $notification->seen = 1;
        $notification->seenAt = Carbon::now();
        $notification->save();
    }

    return collect();
}
function readSupplierNotifications($id)
{
    $user = auth()->user();
    if ($user) {
        $notification = Notification::find($id);
        $notification->seen = 1;
        $notification->seenAt = Carbon::now();
        $notification->save();
    }

    return collect();
}

function getUnreadNotificationsCount()
{
    $user = auth()->user();

    if ($user) {
        return $user->notifications()
            ->whereNull('seenAt')
            ->count();
    }

    return 0;
}

if (!function_exists('setActiveLink')) {
    function prefixActive($prefixName): string
    {
        return request()->segment(1)  == $prefixName ? 'here' : '';
    }
}


if (!function_exists('setActiveLinkDash')) {
    function checkCurrent($prefixName, $notName = null): string
    {
        if (!$notName) {
            return request()->is("*$prefixName*") ? 'active' : '';
        }

        if (request()->is("*$prefixName*") && !request()->is("*$notName*")) {
            return 'active';
        } else {
            return '';
        }
    }
}

if (!function_exists('setShowLinkDash')) {
    function checkCurrentMenu($arrayMenu): string
    {
        foreach ($arrayMenu as $row) {
            if (request()->is("*$row*")) {
                return 'show';
            }
        }
        return '';
    }
}


function setting1($key = null)
{
    return \Modules\Setting\Entities\Setting::get($key);
}