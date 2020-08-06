<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Cart;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);

        LengthAwarePaginator::defaultView('vendor.paginator.campaigns-header');
        LengthAwarePaginator::defaultView('vendor.paginator.campaigns-footer');

        if (! $this->app->runningInConsole()) {

          //template default variables
          view()->composer([
            'frontend.layouts.app',
            'backend.layouts.app'
          ], function($view) {
            //include header & footer by default
            $view->with( 'includeHeader', ($view->includeHeader || $view->includeHeader === null) ? true : false );
            $view->with( 'includeFooter', ($view->includeFooter || $view->includeFooter === null) ? true : false );
          });

          view()->composer([
            'shared.partials.footer'
          ], function($view) {
            //include subscribe form by default
            $view->with( 'includeSubscribeForm', ($view->includeSubscribeForm || $view->includeSubscribeForm === null) ? true : false );
          });

          //toast defaults
          view()->composer([
            'shared.partials.toast'
          ], function($view) {
              $toastDefaults = [
                'type'=>'info',
                'body'=>'',
              ];
              $toastData = session('toast');
            $view->with( 'toastData', $toastData ?: $toastDefaults );
          });

          view()->composer([
            'backend.partials.header'
          ], function($view) {
            $view->with( 'navbarClass', $view->navbarClass ?: 'bg-white' );
          });

          view()->composer([
            'admin.products.partials.form',
            'admin.campaigns.partials.form'
          ], function($view) {
            $view->with('formLocale', old('selected_lang', app()->getLocale()) );
          });



          view()->composer(['frontend.partials.minicart'],function($view){
              $view->with('cart', Cart::content());
          });

          Blade::directive('currency', function () {
            return \Auth::user()->currency ?? '€';
          });

          Blade::directive('dateJFY', function ($date) {
            return "<?php echo date('j F, Y',strtotime($date)) ?>"; //Date format: 20 May, 2020
          });

        }
    }
}
