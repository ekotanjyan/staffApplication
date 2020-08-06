<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Spatie\Searchable\Search;
use Spatie\Searchable\ModelSearchAspect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Campaign;

class CampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        $campaigns = Campaign::has('products')
                    ->with('business')
                    ->activeOrPaused()
                    ->dateValid()
                    ->hasBusinessConnected()
                    ->latest()
                    ->paginate(8);

        if($request->has('string')){
            $campaigns = $this->search($request);

        }

        return view('frontend.campaigns.index',compact('campaigns'));
    }

    public function show($id)
    {
        $campaign = Campaign::where('id', $id)->withTrashed()->first();
        return view('frontend.campaigns.show', compact('campaign'));
    }

    /**
     * Search for Campaigns
     * @param Request $request
     * @return mixed
     */
    private function search(Request $request){

        $arrCampaigns = Campaign::select('campaigns.id')
            ->join('campaign_translations', 'campaigns.id', '=', 'campaign_translations.campaign_id')
            ->join('campaign_product', 'campaigns.id', '=', 'campaign_product.campaign_id')
            ->join('products', 'products.id', '=', 'campaign_product.product_id')
            ->join('product_translations', 'product_translations.product_id', '=', 'products.id')
            ->join('businesses', 'businesses.id', '=', 'campaigns.business_id')
            ->where('product_translations.title', 'like', "%{$request->string}%")
            ->orWhere('product_translations.description', 'like', "%{$request->string}%")
            ->orWhere('businesses.city', 'like', "%{$request->string}%")
            ->orWhere('businesses.name', 'like', "%{$request->string}%")
            ->orWhere('campaign_translations.name', 'like', "%{$request->string}%")
            ->orWhere('campaign_translations.description', 'like', "%{$request->string}%")
            ->groupBy('campaigns.id')
            ->get();

        $arrCampaignsIds = array();
        foreach($arrCampaigns as $campaign)
        {
            $arrCampaignsIds[] = $campaign->id;
        }

        return Campaign::whereIn('id', $arrCampaignsIds)
            ->with('business')
            ->activeOrPaused()
            ->dateValid()
            ->hasBusinessConnected()
            ->latest()
            ->paginate(8);

    }

}
