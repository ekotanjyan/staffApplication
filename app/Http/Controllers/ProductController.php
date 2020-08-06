<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductStore;
use App\Http\Requests\ProductUpdate;
use App\Http\Requests\ProductImageStore;
use App\Product;
use App\ProductCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $assetsFolderName;

    public function __construct()
    {

      $this->authorizeResource(Product::class, 'product');

      $this->middleware('hasCampaign')->only('create');

        //asset folders will have the same name as this controller
        //for example "product"
      $this->assetsFolderName = strtolower(str_replace('Controller', '', class_basename($this)));
    }

    public function index()
    {
      $products = auth()->user()->products()->latest()->get();

      return view(
        'admin.products.index',
        compact('products')
      );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($campaign = null)
    {

      $product = new Product;

      //make new product active by default
      $product->active = true;

      //dont touch this, unless you want to break image upload!
      $imageName = old('image', $product->image);
      $thumb_url = Storage::url('product/'.$imageName);

      //prepare image data for Bootstrap Fileinput plugin
      $product->thumbConfig = collect([ (object)[] ]);
      $product->thumbURLs = json_encode([]);

      if($imageName!="" && Storage::disk('public')->exists($this->assetsFolderName.'/'.$imageName)){
        //for config, captions
        $img = [];
        $img['key'] = $product->id;
        $img['caption'] = $imageName ?? $product->title;
        $product->thumbConfig = collect([ (object) $img ]);
        //for image preview
        $thumb_urls = [];
        $thumb_urls[] = $thumb_url;
        $product->thumbURLs = json_encode($thumb_urls);
      }

      $productCategories = ProductCategory::all();

      return view('admin.products.create',
        compact('product','productCategories','campaign')
      );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStore $request)
    {
        $product = new Product;

        removeNullTranslations($product, $product->requiredAttributes, $request->translations);

        $product->user_id = auth()->id();

        //fill only fillable fields from the request and translations
        $all_fillable = array_merge(
          $request->only($product->getFillable()),
          arrayRemoveNullValues($request->translations)
        );

        //if end_date is not set, we set it 2 years after start_date
        if( !isset($all_fillable['end_date']) || $all_fillable['end_date'] === null )
          $all_fillable['end_date'] = $request->end_date ?: Carbon::parse($request->start_date)->addYears(2)->format('Y-m-d');

        $product->fill( $all_fillable );

        $product->featured  = $request->featured  ? true : false;
        $product->active    = $request->active    ? true : false;

        $product->save();

        if($request->filled('campaign')) {
            $product->campaigns()->attach($request->campaign);
        } else {
            $campaign = auth()->user()->campaigns()->latest()->whereStatus(1)->first();
            if(!$campaign){
                $campaign = auth()->user()->campaigns()->latest()->first();
            }
            $product->campaigns()->attach($campaign->id);
        }

        //Enable once .env mail configs are set.
        //Mail::to('admin@mail.com')->send(new BusinessSaved($business));

        return redirect()->route('product.index')
        ->with('toast', [
          'type' => 'success',
          'body' => __('Product created!')
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
        return view('admin.products.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
      $productCategories = ProductCategory::all();

      //dont touch this, unless you want to break image upload!
      $imageName = old('image', $product->image);
      $thumb_url = Storage::url('product/'.$imageName);

      //prepare image data for Bootstrap Fileinput plugin
      $product->thumbConfig = collect([ (object)[] ]);
      $product->thumbURLs = json_encode([]);

      if($imageName && Storage::disk('public')->exists($this->assetsFolderName.'/'.$imageName)){
        //for config, captions
        $img = [];
        $img['key'] = $product->id;
        $img['caption'] = $imageName ?? $product->title;
        $product->thumbConfig = collect([ (object) $img ]);
        //for image preview
        $thumb_urls = [];
        $thumb_urls[] = $thumb_url;
        $product->thumbURLs = json_encode($thumb_urls);
      }

      return view('admin.products.edit',
        compact( 'product','productCategories' )
      );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdate $request, Product $product)
    {
        removeNullTranslations($product, $product->requiredAttributes, $request->translations);

        //fill only fillable fields from the request and translations
        $all_fillable = array_merge(
          $request->only($product->getFillable()),
          arrayRemoveNullValues($request->translations)
        );

        //if end_date is not set, we set it 2 years after start_date
        if( !isset($all_fillable['end_date']) || $all_fillable['end_date'] === null )
          $all_fillable['end_date'] = $request->end_date ?: Carbon::parse($request->start_date)->addYears(2)->format('Y-m-d');

        $product->fill( $all_fillable );

        $product->featured  = $request->featured  ? true : false;
        $product->active    = $request->active    ? true : false;

        $product->save();

        return back()->withInput()
        ->with('toast', [
          'type' => 'success',
          'body' => __('Product Updated!')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
      $product->campaigns()->detach();

      $product->delete();

      return redirect()->route('product.index')
      ->with('toast', [
        'type' => 'danger',
        'body' => __('Product Deleted!')
      ]);
    }

  /**
   * Delete specified image from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
    public function imageUpload(ProductImageStore $request){
      $out = $preview = $config = $errors = [];

      $image = $request->file('image_input');
      if(isset($image)){
        $currentDate = Carbon::now()->toDateString();
        $imageName = $this->assetsFolderName.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

        if(!Storage::disk('public')->exists($this->assetsFolderName)){
           Storage::disk('public')->makeDirectory($this->assetsFolderName);
        }

        $product_image = Image::make($image)->stream();
        $path_image = $this->assetsFolderName.'/'.$imageName;
        Storage::disk('public')->put($path_image, $product_image);

        $newFileUrl = Storage::url($this->assetsFolderName.'/'.$imageName);
        $preview[] = $newFileUrl;

        $out = [
          'initialPreview' => $preview,
          'initialPreviewAsData' => true,
          'uploadedFilename' => $imageName
        ];

       }

       if($request->ajax()){
           return response()->json($out);
       }
     }

  public function imageDelete(Request $request)
  {
    $out = $preview = $config = $errors = [];
    $product = Product::where('id', $request->get('key'))->first();
    if ($product) {
      $path_image = $this->assetsFolderName . '/' . $product->image;
      Storage::disk('public')->delete($path_image);
      $product->image = null;
      $product->save();
    }
    return response()->json($out);
  }
}
