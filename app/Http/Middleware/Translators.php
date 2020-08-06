<?php

namespace App\Http\Middleware;

use Closure;
use App;

class Translators
{

    public function handle($request, Closure $next)
    {

      $allowed_translators = config('translators');

      if( ! in_array( auth()->user()->email, $allowed_translators ) ){
        die('No access here!');
      }

      return $next($request);
    }
}
