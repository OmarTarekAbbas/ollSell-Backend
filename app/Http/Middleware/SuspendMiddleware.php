<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuspendMiddleware
{ //todo change
    /**
     * Handle an incoming request.
     *
     * param  \Illuminate\Http\Request  $request
     * param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(user()->suspended)return redirect(route('user.edit',\auth()->user()->id));
        return $next($request);
    }
}
