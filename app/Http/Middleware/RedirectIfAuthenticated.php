<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * param  \Illuminate\Http\Request  $request
     * param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * param  string|null  ...$guards
     * return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    { //todo change
        $guards = empty($guards) ? ['supplier'] : $guards;
        foreach ($guards as $guard) {
            if($guard == 'supplier' && Auth::guard($guard)->check()){
                return redirect()->route('supplier.report.default');
            }
        }

        return $next($request);
    }
}
