<?php

namespace App\Http\Middleware;

use Closure;
use App;

class LocaleToDB
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()){
            if (App::getLocale() != auth()->user()->language)
            {
                auth()->user()->update(['language' => App::getLocale()]);
            }
        }
        return $next($request);
    }
}
