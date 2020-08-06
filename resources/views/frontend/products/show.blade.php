@extends('frontend.layouts.app',[
'business'=>$product->title.' by '.$product->business()->name,
'member_since'=>$product->business()->created_at->format('d F, Y')
])

@section('title', $product->title)

@push('meta')
  <meta name="robots" content="index, follow"/>
  <meta name="locale" content="{{ supportedLocales()[app()->getLocale()]['regional'] }}"/>
  @if( Storage::disk('public')->exists('product/'.$product->image) )
  <meta name="image" content="{{Storage::url('product/'.$product->image)}}" />
  @endif
  <meta name="description" content="{{ strip_tags($product->description) }}"/>
  <meta name="x-default" content="{{ LaravelLocalization::getLocalizedURL( env('LOCALE'), null, [], true) }}"/>
  <link rel="canonical"  href="{{ LaravelLocalization::getLocalizedURL( env('LOCALE'), null, [], true) }}" />

  @foreach( supportedLocales() as $localeCode => $properties)
    @if( $product->hasTranslation($localeCode) )
    <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" />
    @endif
  @endforeach

  <meta itemprop="name" content="{{ $product->title }} | {{ config('app.name') }}"/>

  @if( Storage::disk('public')->exists('product/'.$product->image) )
  <meta itemprop="image" content="{{Storage::url('product/'.$product->image)}}" />
  @endif
@endpush

@section('breadcrumbs')
    @include('frontend.partials.breadcrumbs', ['breadcrumbs' => [
        $campaign->name => route('campaigns.show',$campaign->id),
        $product->title,
    ]])
@endsection

@section('content')
<div class="row">

    <section class="col-lg-8 pt-2 pt-lg-4 pb-4 mb-lg-3">
        <div class="pt-2 px-4 pr-lg-0 pl-xl-5">
            <!-- Image gallery -->
            <div class="cz-gallery">
                <a href="{{ Thumbnail::thumb('product/'.$product->image, 850, 565, 'background') }}" class="gallery-item rounded-lg" data-sub-html='<h6 class="font-size-sm text-light">Gallery image caption</h6>'>
                    <img src="{{ Thumbnail::thumb('product/'.$product->image, 850, 565, 'background') }}" alt="Gallery thumbnail">
                    <span class="gallery-item-caption">{{$product->title}}</span>
                </a>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center border-top pt-3">
                <div class="py-1"><a class="btn btn-outline-accent btn-sm" href="{{route('campaigns.show',$campaign->id)}}"><i class="czi-arrow-left mr-1 ml-n1"></i>{{__('Back to campaign')}}</a></div>
                <div class="py-2">
                  @if($product->business()->website)
                  <a class="social-btn sb-outline sb-globe sb-sm ml-2" target="_blank" href="{{$campaign->business->website ?? '#'}}">
                  <i class="czi-globe"></i></a>@endif
                  @if($product->business()->facebook)<a class="social-btn sb-outline sb-facebook sb-sm ml-2" target="_blank" href="{{$campaign->business->facebook ?? '#'}}">
                  <i class="czi-facebook"></i></a>@endif
                  @if($product->business()->twitter)<a class="social-btn sb-outline sb-twitter sb-sm ml-2" target="_blank" href="{{$campaign->business->twitter ?? '#'}}">
                  <i class="czi-twitter"></i></a>@endif
                  @if($product->business()->twitter)<a class="social-btn sb-outline sb-instagram sb-sm ml-2" target="_blank" href="{{$campaign->business->instagram ?? '#'}}">
                  <i class="czi-instagram"></i></a>@endif
                </div>
            </div>

            <!-- Tabs content -->
            <ul class="nav nav-tabs mt-2" role="tablist">
                <li class="nav-item">
                    <a href="#about" class="nav-link active" data-toggle="tab" role="tab">
                    {{__('Product details')}}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="about" role="tabpanel">
                    <p class="font-size-sm">{{$product->description}}</p>
                </div>

                <div class="sharethis-inline-share-buttons"></div>
            </div>
            <!-- Tabs content -->
        </div>
    </section>

    <!-- ASIDE -->
    <aside class="col-lg-4">
      <hr class="d-lg-none">
      <div class="cz-sidebar-static h-100 ml-auto border-left">
        <div class="accordion" id="licenses">
          <div class="card border-top-0 border-left-0 border-right-0">
            <div class="card-header d-flex justify-content-between align-items-center py-3 border-0">
              <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" name="license" id="license-std" checked="">
                <label class="custom-control-label font-weight-medium text-dark" for="license-std" data-toggle="collapse" data-target="#standard-license">{{$product->title}}</label>
              </div>
            </div>
            <div class="card-body py-0 pb-3">
              <h5 class="mb-0 text-accent font-weight-normal">@currency {{$product->formattedPrice}}</h5>
            </div>
            <div class="collapse show" id="standard-license" data-parent="#licenses">
              <div class="card-body py-0 pb-2">
                <ul class="list-unstyled font-size-sm">
                  <li class="d-flex align-items-center">
                  <i class="czi-check-circle text-success mr-1"></i><span class="font-size-ms">{!! __('This is a :productcategory',['productcategory'=>'<strong>'.$product->category->name.'</strong>']) !!} </span></li>
                  <li class="d-flex align-items-center"><i class="czi-check-circle text-success mr-1"></i><span class="font-size-ms">{{__('Available from')}}: {{ date('d F, Y',strtotime($product->start_date)) }}</span></li>
                  <li class="d-flex align-items-center"><i class="czi-check-circle text-success mr-1"></i><span class="font-size-ms">{{__('Use or collect by')}}: {{ date('d F, Y',strtotime($product->end_date)) }}</span></li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <hr>
        <button class="btn btn-primary btn-shadow btn-block mt-4 add-cart" type="button"
            data-toggle="toast"
            data-target="#cart-toast"
            data-product-id="{{$product->id}}"
            data-campaign-id="{{$campaign->id}}">
            <i class="czi-cart font-size-lg mr-2"></i>{{__('Add to Cart')}}</button>

        <div class="bg-secondary rounded p-3 mt-4 mb-2">
          <div class="media-body pl-2">
            <span class="font-size-ms text-muted">{{__('Sold by')}}</span>
            <a href="{{route('campaigns.show',$campaign->id)}}"><h6 class="font-size-sm font-weight-bold mb-0">{{$product->business()->name}}</h6></a>
            <span class="font-size-ms text-muted font-weight-bold">{{$product->business()->address}}, {{$product->business()->city}} ({{$product->business()->zip}}), {{$product->business()->country}}</span>
          </div>
        </div>

        <div class="bg-secondary rounded p-3 mb-4"><i class="czi-money-bag h5 text-muted align-middle mb-0 mt-n1 mr-2"></i>{{__('Only')}} <span class="d-inline-block h6 mb-0 mr-1"><b>{{$product->units}}</b></span><span class="font-size-sm">{{__('left')}}</span></div>

        <ul class="list-unstyled font-size-sm">
          <li class="d-flex justify-content-between mb-3 pb-3 border-bottom"><span class="text-dark font-weight-medium">{{__('Created on')}}:</span><span class="text-muted">{{ date('d F, Y',strtotime($product->created_at)) }}</span></li>
          <li class="d-flex justify-content-between mb-3 pb-3 border-bottom"><span class="text-dark font-weight-medium">{{__('Category')}}:</span><a class="product-meta" href="#">{{$product->category->name}}</a></li>
        </ul>
      </div>
    </aside>
    <!-- END ASIDE -->


    <section class="container mb-5 p-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-4 mb-4">
        <h2 class="h3 mb-0 pt-2">{{__('Other items from this campaign')}}</h2>
      </div>
        <!-- Card deck -->
        <div class="row">
            @foreach($campaign->products as $c_product)
                @if($c_product->id !== $product->id)
                <!-- Card -->
                <div class="card product-card-alt col-md-3">
                    <div class="product-thumb">
                        <!-- <button class="btn-wishlist btn-sm" type="button"><i class="czi-heart"></i></button>-->
                        <div class="product-card-actions">
                        <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{route('products.show',$c_product->id)}}"
                            title="View the product"><i class="czi-eye"></i></a>
                            <button class="btn btn-light btn-icon btn-shadow font-size-base mx-2 add-cart" type="button"
                            data-toggle="toast" data-target="#cart-toast" data-product-id="{{$c_product->id}}"
                            data-campaign-id="{{$campaign->id}}" title="{{__('Add this product to the cart')}}">
                            <i class="czi-cart"></i></button>

                        </div><a class="product-thumb-overlay" href="{{route('products.show',$c_product->id) }}"></a>
                        <img src="{{Thumbnail::thumb('product/'.$c_product->image, 300, 225, 'background')}}" alt="Product">
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-start pb-2">
                        <div class="text-muted font-size-xs mr-1">{{__('by')}} <a class="product-meta font-weight-medium" href="{{route('campaigns.show',$campaign->id)}}">{{$campaign->business->name}}</a></div>
                        </div>
                        <h3 class="product-title font-size-sm mb-2"><a href="{{route('products.show',$c_product->id)}}">{{$c_product->title}}</a></h3>
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="font-size-sm mr-2"><i class="czi-announcement text-muted mr-1"></i>{{ __('Only :units left',['units'=>$c_product->units]) }}</div>
                        <div class="bg-faded-accent text-accent rounded-sm py-1 px-2">@currency {{$c_product->formattedPrice}}</div>
                        </div>
                    </div>
                </div>
                @endif
                @break($loop->iteration == 5)
            @endforeach
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5f0a553f0e7bec0012bd794a&product=inline-share-buttons&cms=sop' async='async'></script>
@endpush
