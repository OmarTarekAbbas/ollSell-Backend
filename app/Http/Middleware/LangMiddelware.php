<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\App;


class LangMiddelware
{
    /**
     * Handle an incoming request.
     *
     * param  \Illuminate\Http\Request  $request
     * param  \Closure  $next
     */
    public function handle($request, Closure $next)
    {
        $lang = languageLocale();
        $auth = false;
        if ($request->header('lang')) {
            $auth = true;
            $lang = $request->header('lang');
        } elseif ($request->lang) {
            $auth = true;
            $lang = $request->lang;
        } elseif ($request->cookie('language') && !$request->expectsJson()) {
            $auth = true;
            $lang = $request->cookie('language');
        }
        if($auth)
        {
        if (user() && user()->lang != $lang) {
            user()->update(['lang' => $lang]);
        }
        }
        App::setlocale($lang);
        return $next($request);
    }
}