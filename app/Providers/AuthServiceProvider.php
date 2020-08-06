<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\CampaignPolicy;
use App\Policies\BusinessPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SubOrdersPolicy;
use App\Campaign;
use App\Business;
use App\Product;
use App\Suborder;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Campaign::class => CampaignPolicy::class,
        Product::class => ProductPolicy::class,
        Business::class => BusinessPolicy::class,
        Suborder::class => SubOrdersPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('vieworder', function ($user, $charge) {
            return $user->id === $charge->user_id || $user->id === $charge->seller_id;
        });

        Gate::before(function ($user, $ability) {
            if ($user->email == 'gregory@gbrown.ch') {
                return true;
            }
        });
        
    }
}
