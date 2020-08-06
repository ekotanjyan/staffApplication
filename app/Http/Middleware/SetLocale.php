<?php

namespace App\Http\Middleware;

use Closure;
use App;

class SetLocale
{

    public function handle($request, Closure $next)
    {

        //set locale from url
        if( isset($_GET['hl']) AND array_key_exists($_GET['hl'], supportedLocales() )){
          session()->put('locale', $_GET['hl']);
        }

        //set locale from session variable if it exists
        if (session()->has('locale') AND array_key_exists(session()->get('locale'), supportedLocales() )) {
          App::setLocale(session()->get('locale'));
        }

        return $next($request);
    }
}
