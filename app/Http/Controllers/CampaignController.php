<?php

namespace App\Http\Controllers;

use App\Mail\CampaignApprovalRequested;
use App\Mail\CampaignPaused;
use Illuminate\Http\Request;
use App\Http\Requests\CampaignStore;
use App\Http\Requests\CampaignUpdate;
use Illuminate\Support\Facades\Auth;
use App\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Mail;
use App\Mail\CampaignCreated;

class CampaignController extends Controller
{

    private $assetsFolderName;

    public function __construct()
    {

      $this->authorizeResource(Campaign::class, 'campaign');
      $this->middleware('hasBusiness')->only('create');
      $this->middleware('stripeConnected')->only('create');

        //asset folders will have the same name as this controller
        //for example "product"
      $this->assetsFolderName = strtolower(str_replace('Controller', '', class_basename($this)));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $campaigns = auth()->user()->campaigns()->latest()->paginate(12);
        $businesses = auth()->user()->businesses;

        return view(
          'admin.campaigns.index',
          compact('campaigns', 'businesses')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $campaign = new Campaign;
        $businesses = auth()->user()->businesses;
        $products = auth()->user()->products()->where('active', 1)->get();

        return view('admin.campaigns.create',
          compact('campaign','businesses', 'products')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampaignStore $request)
    {
      $campaign = new Campaign;

      removeNullTranslations($campaign, $campaign->requiredAttributes, $request->translations);

      $campaign->business_id = $request->business_id;
      $campaign->user_id = auth()->id();

      //fill only fillable fields from the request and translations
      $all_fillable = array_merge(
        $request->only($campaign->getFillable()),
        arrayRemoveNullValues($request->translations)
      );

      $campaign->fill( $all_fillable );

      //if campaign start/end dates are not set, make them start today and end after 1 year
      $campaign->start_date = $campaign->start_date ?: Carbon::today()->format('Y-m-d');
      $campaign->end_date = $campaign->end_date ?: Carbon::today()->addYears(1)->format('Y-m-d');

      $image = $request->file('image');
      if(isset($image)){
          $currentDate = Carbon::now()->toDateString();
          $imageName = $this->assetsFolderName.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

          if(!Storage::disk('public')->exists($this->assetsFolderName)){
              Storage::disk('public')->makeDirectory($this->assetsFolderName);
          }

          //max image width 1200px
          $campaign_image = Image::make($image)->resize(1200, null, function ($constraint) {
               $constraint->aspectRatio();
           })->stream();

          //TODO Optimize image controller by saving folder path on db and place static folder name without 'assetsFolderName'.
          $path_image = $this->assetsFolderName.'/'.$imageName;

          Storage::disk('public')->put($path_image, $campaign_image);

          $campaign->image = $imageName;
      }
      $campaign->save();

      if($request->has('products')) {
          $campaign->products()->attach($request->products);
      }

      Mail::to('gregory@gbrown.ch')->queue( new CampaignCreated($campaign) );

      return redirect()->route('campaign.product', $campaign->id)
      ->with('toast', [
        'type' => 'success',
        'body' => __('Campaign created! Create a product.')
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return view('admin.campaigns.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $campaign)
    {
        $businesses = auth()->user()->businesses;
        $products = auth()->user()->products()->where('active', 1)->latest()->get();

        return view('admin.campaigns.edit',
          compact( 'campaign','businesses', 'products' )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CampaignUpdate $request, Campaign $campaign)
    {

      removeNullTranslations($campaign, $campaign->requiredAttributes, $request->translations);

      //fill only fillable fields from the request and translations
      $all_fillable = array_merge(
        $request->only($campaign->getFillable()),
        arrayRemoveNullValues($request->translations)
      );

      //if campaign start/end dates are not set, make them start today and end after 1 year
      if( !isset($all_fillable['start_date']) || $all_fillable['start_date'] === null )
        $all_fillable['start_date'] = $request->start_date ?: Carbon::today()->format('Y-m-d');

      if( !isset($all_fillable['end_date']) || $all_fillable['end_date'] === null )
      $all_fillable['end_date'] = $request->end_date ?: Carbon::today()->addYears(1)->format('Y-m-d');

      $campaign->fill( $all_fillable );

      $image = $request->file('image');
      if(isset($image)){
          $currentDate = Carbon::now()->toDateString();
          $imageName = $this->assetsFolderName.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

          if(!Storage::disk('public')->exists($this->assetsFolderName)){
              Storage::disk('public')->makeDirectory($this->assetsFolderName);
          }
          if(Storage::disk('public')->exists($campaign->image)){
              Storage::disk('public')->delete($campaign->image);
          }
          $campaign_image = Image::make($image)->stream();
          $path_image = $this->assetsFolderName.'/'.$imageName;
          Storage::disk('public')->put($path_image, $campaign_image);

          $campaign->image = $imageName;
      }

      $campaign->save();

      $campaign->products()->sync($request->products);

      return back()->withInput()->with('toast', [
        'type' => 'success',
        'body' => __('Campaign Updated!')
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $campaign)
    {
      $campaign->products()->detach();

      $campaign->delete();

      return redirect()->route('campaign.index')
      ->with('toast', [
        'type' => 'info',
        'body' => __('Campaign Deleted!')
      ]);
    }


//    public function status(Request $request, Campaign $campaign)
//    {
//
//        $request->validate([
//            'status' => 'required|digits:1',
//        ]);
//        $this->authorize('status', $campaign);
//
//        $message = [
//            'type' => 'success',
//            'body' => __('Campaign status changed!')
//        ];
//
//        $s = (int)$request->get('status');
//
//        if (in_array($s, [2, 4])) { // user allowed actions are only 2(pause) and 4(finish)
//            $campaign->status = $s;
//            $campaign->save();
//        }
//
//        if ($s === 2) { // if paused
//            Mail::to(auth()->user()->email)->queue(new CampaignPaused($campaign));
//        } elseif ($s === 1) { // if active
//            Mail::to('gregory@gbrown.ch')->queue(new CampaignApprovalRequested($campaign));
//            $message['body'] = __('Your request has been sent. You will receive e-mail once the campaign is approved.');
//        }
//
//        return redirect()->route('campaign.index')
//            ->with('toast', $message);
//
//    }

  public function imageDelete(Request $request)
  {
    $out = $preview = $config = $errors = [];
    $campaign = Campaign::where('id', $request->get('key'))->first();
    if ($campaign) {
      $path_image = $this->assetsFolderName . '/' . $campaign->image;
      Storage::disk('public')->delete($path_image);
      $campaign->image = null;
      $campaign->save();
    }
    return response()->json($out);
  }
}
