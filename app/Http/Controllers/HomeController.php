<?php

namespace App\Http\Controllers;

use App\Charge;
use App\Suborder;
use Spatie\Searchable\Search;
use Illuminate\Http\Request;
use Spatie\Searchable\ModelSearchAspect;
use Illuminate\Database\Eloquent\Builder;
use App\ProductCategory;
use App\Campaign;
use App\Product;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $campaigns = $this->getRaisedOrderedCampaigns();

        $products = Product::with('activeCampaigns.business')
                        ->has('activeCampaigns')
                        ->where('active', 1)
                        ->where('units','>',0)
                        ->hasBusinessConnected()
                        ->latest()
                        ->limit(10)
                        ->get();

        $searchedProducts = array();
        if($request->has('string') || $request->has('category')){
            $searchedProducts = $this->search($request);
        }

        return view('frontend.home',compact('campaigns','products', 'searchedProducts'));
    }

    /**
     * Search for Products
     * @param Request $request
     * @return mixed
     */
    private function search(Request $request)
    {
        $arrProducts = Product::select('products.id')->join('product_translations', 'product_translations.product_id', '=', 'products.id')
            ->join('campaign_product', 'product_translations.product_id', '=', 'campaign_product.product_id')
            ->join('campaigns', 'campaigns.id', '=', 'campaign_product.campaign_id')
            ->join('campaign_translations', 'campaigns.id', '=', 'campaign_translations.campaign_id')
            ->join('businesses', 'businesses.id', '=', 'campaigns.business_id')
            ->where('product_translations.title', 'like', "%{$request->string}%")
            ->orWhere('product_translations.description', 'like', "%{$request->string}%")
            ->orWhere('businesses.city', 'like', "%{$request->string}%")
            ->orWhere('businesses.name', 'like', "%{$request->string}%")
            ->orWhere('campaign_translations.name', 'like', "%{$request->string}%")
            ->orWhere('campaign_translations.description', 'like', "%{$request->string}%")->get();

        $arrProductIds = array();
        foreach($arrProducts as $product)
        {
            $arrProductIds[] = $product->id;
        }

        return Product::whereIn('id', $arrProductIds)
            ->where('active', 1)
            ->with('activeCampaigns.business')
            ->has('activeCampaigns')
            ->hasBusinessConnected()
            ->get();

    }

    /**
     * Get Campaigns ordered by Raised amount
     * @return mixed
     */
    private function getRaisedOrderedCampaigns()
    {
        $campaigns = Campaign::has('products')->addSelect(
            ['donation_total' => Charge::withoutGlobalScope('noDonations')->selectRaw('sum(amount) as total')
            ->whereColumn('campaign_id', 'campaigns.id')
            ->where('is_donation', 1)
            ->where('is_paid', 1)
            ->groupBy('campaign_id')
        ])->addSelect(
            ['orders_total' => Suborder::selectRaw('sum(price) as total')->has('charge')
            ->whereColumn('campaign_id', 'campaigns.id')
            ->groupBy('campaign_id')
        ])
            ->active()
            ->orderByRaw('(IFNULL(donation_total,0)/100 + IFNULL(orders_total,0))/campaigns.target DESC')
            ->hasBusinessConnected()
            ->limit(10)
            ->get()
            ->unique('business_id');
        return $campaigns;


    }



}
