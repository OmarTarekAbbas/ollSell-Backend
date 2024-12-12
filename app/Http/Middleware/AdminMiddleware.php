<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * param  \Illuminate\Http\Request  $request
     * param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (user() && user()->status == 1) {
            return $next($request);
        }
        Auth::logout();
        return redirect(route('auth.login.form'))->with('message_false', 'Message_Support');
    }
}
