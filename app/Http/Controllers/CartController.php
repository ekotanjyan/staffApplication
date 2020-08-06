<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Campaign;
use Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller {

    public function index(){
      $cart = Cart::content();

      $this->verifyCart($cart);

      return response()->view('frontend.cart.index', [
        'cart' => $cart
      ]);
    }


    public function add(Product $product, Campaign $campaign = null) {
      if (!$product->hasBusinessConnected()){
          return response()->json(['error' => __('Unfortunately this campaign cannot receive any payment at this time. Please check again later.')], 404);
      }
      $qtyInCard = Cart::content()->where('id', $product->id)->first()->qty ?? 0;
      if ($product->units > $qtyInCard) {
        Cart::add($product->id, $product->title, 1, $product->price, 0, [
          'units' => $product->units,
          'campaign' => $campaign])
          ->associate('\App\Product');

        return [
          'view' => view('frontend.partials.minicart', [
            'cart' => Cart::content()
          ])->render(),
          'message' => __('Product added to cart.')
        ];
      }
      return response()->json(['error' => __('Sorry, there are no more units available for this product.')], 404);
    }


    public function update(Request $request){
        $product = Cart::get($request->id);
        if($request->quantity <= $product->options->units){
            Cart::update($request->id, $request->quantity);
        }
        return Cart::subtotal();
    }


    public function remove(Request $request) {

        Cart::remove($request->id);
        return view('frontend.partials.minicart',[
            'cart' => Cart::content()
        ]);
    }


    public function checkout(Request $request){
      $user = Auth::user();
      $stripe_key = env('STRIPE_KEY');

      $cart = Cart::content();

      $this->verifyCart($cart);

      return response()->view('frontend.cart.checkout', [
        'cart' => $cart,
        'user' => $user,
        'stripe_key' => $stripe_key,
      ]);
    }

    public function destroy(){
        Cart::destroy();
        return redirect()->route('home')
          ->with('toast', [
          'type' => 'success',
          'body' => __('Cart cleared!')
        ]);
    }

    public function verifyCart ($cart){
      foreach ($cart as $item) {
        $product = Product::where(['id' => $item->id])->get()->first();
        if (!$product->hasBusinessConnected()){
            Cart::remove($item->rowId);
            $toastMsg = [
                'type' => 'warning',
                'body' => __('Sorry, some campaigns cannot receive any payment at this time. Your cart has been updated.')
            ];
            Session::now('toast', $toastMsg);
        } elseif ($item->qty > $product->units) {
          Cart::update($item->rowId, $product->units > 0 ? $product->units : 0); // update the card with max available units
          $toastMsg = [
            'type' => 'warning',
            'body' => __('Sorry, some items in your cart have already been sold. Your cart has been updated.')
          ];
          Session::now('toast', $toastMsg);
        }
      }
    }
}
