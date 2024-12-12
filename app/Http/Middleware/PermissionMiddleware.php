<?php

namespace App\Http\Middleware;

use Closure;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
        return $next($request);
        if(
            ($request->url() == route('user.edit', \auth()->user()->id)) ||
            ($request->url() == route('user.update', \auth()->user()->id))
        )
        {
            return $next($request);
        }elseif(\auth()->user()->suspended)
        {
            return redirect(route('user.edit', \auth()->user()->id));
        }
        return permissionShow($permission) ? $next($request) : abort(403);
    }
}
