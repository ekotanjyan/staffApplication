<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BusinessStore;
use App\Http\Requests\BusinessUpdate;
use App\Business;
use App\BusinessCategory;
use Auth;


class BusinessController extends Controller
{

    public function __construct(){

        $this->authorizeResource(Business::class, 'business');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $businesses = auth()->user()->businesses()->latest()->get();
        return view('admin.businesses.index',
          compact('businesses')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $businessCategories = BusinessCategory::all();
        $business = new Business;

        return view('admin.businesses.create',
          compact( 'businessCategories', 'business')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BusinessStore $request)
    {
        $business = new Business;
        $business->user_id = Auth::user()->id;

        //filter only fillable fields from the request and fill them
        $business->fill( $request->only($business->getFillable()) );

        $business->save();

        return redirect()->route('business.index')
        ->with('toast', [
          'type' => 'success',
          'body' => __('Business created!')
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
        return view('admin.businesses.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Business $business)
    {
        $businessCategories = BusinessCategory::all();

        return view('admin.businesses.edit',
            compact( 'businessCategories', 'business' )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BusinessUpdate $request, Business $business)
    {
      //filter only fillable fields from the request and fill them
      $business->fill( $request->only($business->getFillable()) );
      $business->save();

      return back()->withInput()
      ->with('toast', [
        'type' => 'success',
        'body' => __('Business Updated!')
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        /**
         * To be deleted by admin approval after request.
         */

        $business->delete();

        return redirect()->route('business.index')
        ->with('toast', [
          'type' => 'danger',
          'body' => __('Business Deleted!')
        ]);
    }
}
