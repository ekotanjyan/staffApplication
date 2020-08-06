<?php

namespace App\Http\Middleware;

use Closure;

class CampaignHasBusiness
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
        if( !auth()->user()->businesses()->exists() ){
            return redirect()->route('business.create')
                ->with('toast', [
                    'type'=>'info',
                    'body'=>__('Please create a business before proceeding with campaigns or products!')
                ]);
        }
        return $next($request);
    }
}
