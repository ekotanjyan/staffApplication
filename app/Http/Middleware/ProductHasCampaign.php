<?php

namespace App\Http\Middleware;

use Closure;

class ProductHasCampaign
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

        if (!auth()->user()->campaigns()->exists()) {
            return redirect()->route('campaign.create')
            ->with('toast', [
                'type'=>'info',
                'body'=>__('Please add a campaign before proceeding with products!')
            ]);
        }

        return $next($request);
    }
}
