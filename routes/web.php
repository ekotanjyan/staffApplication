<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function () {

	Route::get('/', 'HomeController@index')->name('home');
	Route::get('/stripe/connect', 'StripeController@connect')->name('stripe.connect');
	Route::get('/stripe/disconnect', 'StripeController@disconnect')->name('stripe.disconnect');

	Route::resource('products','Frontend\ProductsController');
	Route::resource('campaigns','Frontend\CampaignsController');
	Route::post('campaigns/search','Frontend\CampaignsController@search')->name('campaigns.search');

  Auth::routes(['verify' => true]);

  Route::prefix('cart')->group(function () {
    Route::get('/','CartController@index')->name('cart.index');
    Route::get('add/{product}/{campaign?}','CartController@add')->name('cart.add');
    Route::post('remove','CartController@remove')->name('cart.remove');
    Route::post('update','CartController@update')->name('cart.update');
    Route::get('items','CartController@items')->name('cart.items');
    Route::get('destroy','CartController@destroy')->name('cart.destroy');
    Route::get('checkout','CartController@checkout')->name('cart.checkout')->middleware('auth');
  });

  Route::middleware(['auth','localetodb'])->group(function () {

    Route::get('stripe/seller-charges','StripeController@sellerCharges')->name('stripe.seller-charges');
    Route::get('stripe/charges','StripeController@charges')->name('stripe.charges');
    Route::post('stripe/checkout','StripeController@checkout')->name('stripe.checkout');
    Route::get('stripe/add-credit-card','StripeController@addCreditCard')->name('stripe.add-credit-card');
    Route::post('stripe/add-credit-card','StripeController@addCreditCard')->name('stripe.add-credit-card');
    Route::post('stripe/donate','StripeController@donate')->name('stripe.donate');
    Route::post('stripe/donate','StripeController@donate')->name('stripe.donate');
    Route::get('stripe/delete-credit-card','StripeController@deleteCreditCard')->name('stripe.delete-credit-card');
    Route::get('donations_made','StripeController@donations_made')->name('stripe.donations_made');
    Route::get('donations_received','StripeController@donations_received')->name('stripe.donations_received');

    Route::get('stripe/view/{id}','StripeController@view')->name('stripe.view')->where('id', '[0-9]{0,10}');
    Route::post('stripe/view/{id}','StripeController@view')->name('stripe.view')->where('id', '[0-9]{0,10}');
    Route::get('stripe/mailview/{charge}/{bo?}','StripeController@mailView')->name('order.view');
    //Route::get('stripe/mail/fail/{charge}','StripeController@chargeFail')->name('order.failed');
    Route::resource('stripe', 'StripeController');

    Route::prefix('app')->middleware('verified')->group(function () {
        Route::get('qrcode/{qr}/redeem','QrCodeController@product')->name('qr.product');
        Route::post('qrcode/{qr}/redeem','QrCodeController@update')->name('qr.update');
        Route::resource('business','BusinessController');
        Route::resource('campaign','CampaignController');
        Route::post('campaign/image/delete','CampaignController@imageDelete')->name('campaign.imageDelete');
//        Route::post('campaign/status/{campaign}','CampaignController@status')->name('campaign.status');
        Route::resource('product','ProductController');
        Route::get('product/create/{id}','ProductController@create')->name('campaign.product');
        Route::post('product/image','ProductController@imageUpload')->name('campaign.product.imageUpload');
        Route::post('product/image/delete','ProductController@imageDelete')->name('campaign.product.imageDelete');
        Route::resource('user','UserController');
    });

  });

});
