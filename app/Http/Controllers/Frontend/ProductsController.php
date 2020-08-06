<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Business;
use App\Campaign;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function show($id){

        $product = Product::with('activeCampaigns.business')
                    ->where('id',$id)
                    ->first();

        if(isset($product->campaign()->id))
        {
            $campaign = Campaign::with('products')
                ->where('id',$product->campaign()->id)
                ->first();
                           
            /* Business related */
            /*$related = Business::with('campaigns.products')
                        ->where('id',$product->campaigns->first()->business->id)
                        ->first();*/

            return view('frontend.products.show',compact('product','campaign'));
        }
        else{
            abort(404);
        }

    }


}
