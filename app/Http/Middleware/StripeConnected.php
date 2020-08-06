<?php

namespace App\Http\Middleware;

use Closure;

class StripeConnected
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
        if(!auth()->user()->isConnected()){
            return redirect()->route('stripe.index')->with('toast',[
                'type'=>'warning',
                'body'=>__('Please connect your stripe account before proceeding with campaign!')
            ]);
        }
        return $next($request);
    }
}
