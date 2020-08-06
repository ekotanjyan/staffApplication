@extends('frontend.layouts.master')
@prepend('styles')

<!-- Styles -->
<link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/theme.min.css') }}" rel="stylesheet">
<style>
.filter-select option { background:#fff; color:#4b566b}
</style>
@endprepend

@section('title', 'Home')


@section('app')

  @include('frontend.partials.header')

  @include('shared.partials.toast')

  <!-- HERO section -->
  <section class="bg-accent py-5 hero-home">
      <div class="pb-lg-5 mb-lg-3">
        <div class="container py-lg-5">
          <div class="row mb-4 mb-sm-5">
            <div class="col-lg-7 col-md-9 text-center text-sm-left">
              <h1 class="text-dark line-height-base">{{__('The pioneer of crowdliquidity')}}</h1>
              <h2 class="h5 text-dark font-weight-light">{{__('High quality products and services from your local businesses')}}</h2>
            </div>
          </div>
          <div class="row pb-lg-5 mb-4 mb-sm-5">
            <div class="col-lg-6 col-md-8">
            <form id="homefilterForm" name="homefilter" method="get" action="{{route('home')}}">
                    @csrf
              <div class="input-group input-group-overlay input-group-lg">
                      <div class="input-group-prepend-overlay"><span class="input-group-text"><i class="czi-search"></i></span></div>
                      <input class="form-control form-control-lg prepended-form-control rounded" name="string" id="string" type="text" value="{{ old('string',request('string')) }}" placeholder="{{__('Search for anything')}}...">
                      <div class="input-group-append d-none d-sm-block">

                        <!--<button class="btn btn-primary btn-lg dropdown-toggle font-size-base" type="button" data-toggle="dropdown">{{__('All categories')}}</button>
                        <div class="dropdown-menu dropdown-menu-right">
                          <a class="dropdown-item" href="#">{{__('Products')}}</a>
                          <a class="dropdown-item" href="#">{{__('Services')}}</a>
                          <a class="dropdown-item" href="#">{{__('Vouchers')}}</a>
                        </div>-->

                        {{--<select class="form-control form-control-lg rounded-left-0 bg-danger btn-primary filter-select" name="category" onchange="document.forms['homefilter'].submit();">--}}
                          {{--<option value="">{{__('All Categories')}}</option>--}}
                          {{--@foreach(\App\ProductCategory::all() as $category)--}}
                            {{--<option value="{{$category->id}}" {{ (old('category',request('category')) == $category->id ? 'selected':'') }}>{{$category->name}}</option>--}}
                          {{--@endforeach--}}
                        {{--</select>--}}
                    </div>
              </div>
{{--                <div class="input-group input-group-overlay input-group-lg d-block d-sm-none">--}}
{{--                    <select class="form-control form-control-lg rounded-0 bg-danger btn-primary filter-select" name="category" onchange="document.forms['homefilter'].submit();">--}}
{{--                        <option value="">{{__('All Categories')}}</option>--}}
{{--                        @foreach(\App\ProductCategory::all() as $category)--}}
{{--                            <option value="{{$category->id}}" {{ (old('category',request('category')) == $category->id ? 'selected':'') }}>{{$category->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- END HERO section -->

  @if(!empty($searchedProducts))
  <section id="search-products"  class="container position-relative py-5 pt-lg-0 pb-5 mt-lg-n12" style="z-index: 10;">
    <div class="card px-lg-2 border-0 box-shadow-lg">
      <div class="card-body px-4 pt-5 pb-4">
        <h2 class="h3 text-center">{{__('Search Results')}}</h2>
        <div class="card-deck">
          <!-- Multiple items + Static controls outside + No dots + Loop (Responsive) -->
          @forelse($searchedProducts as $product)
            <div class="col-md-4 col-sm-6 px-2 mb-grid-gutter">
              <div class="card border-0 product-card-alt mb-grid-gutter">

                <div class="product-thumb">
                  <div class="product-card-actions">
                    <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ route('products.show',$product->id) }}"><i class="czi-eye"></i></a>
                    <button class="btn btn-light btn-icon btn-shadow font-size-base mx-2 add-cart" type="button"
                            data-campaign-id="{{$product->campaign()->id ?? 0}}"
                            data-product-id="{{$product->id}}"><i class="czi-cart"></i></button>
                  </div>
                  <a class="product-thumb-overlay" href="{{ route('products.show',$product->id) }}"></a>
                  <img src="{{ Thumbnail::thumb('product/'.$product->image, 400, 225, 'fit') }}" alt="">
                </div>

                <div class="card-body">
                  <div class="d-flex flex-wrap justify-content-between align-items-start pb-2">
                    <div class="text-muted font-size-xs mr-1">{{__('by')}}
                        <a class="product-meta font-weight-medium" href="{{route('campaigns.show',$product->campaign()->id)}}">
                            {!! \Illuminate\Support\Str::of($product->campaign()->business->name ?? '')->replaceMatches('/'.request('string').'/i', function ($match) { return '<b class="text-warning">'.$match[0].'</b>'; }) !!}
                            </a>
                    </div>
                  </div>
                  <h3 class="product-title font-size-sm mb-2">
                    <a href="{{route('products.show',$product->id)}}">{!! \Illuminate\Support\Str::of($product->title)->replaceMatches('/'.request('string').'/i', function ($match) { return '<b class="text-warning">'.$match[0].'</b>'; }) !!}</a></h3>
                    <h3 class="product-title font-size-sm mb-2">
                    <a href="{{route('campaigns.show',$product->campaign()->id)}}">{!! \Illuminate\Support\Str::of($product->campaign()->name)->replaceMatches('/'.request('string').'/i', function ($match) { return '<b class="text-warning">'.$match[0].'</b>'; }) !!}</a></h3>
                  <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="font-size-sm mr-2"><i class="czi-download text-muted mr-1"></i>{{ __('Only :units left',['units'=>$product->units]) }}</div>
                    <div class="py-1"><i class="czi-money-bag text-muted mr-1"></i>@currency {{ $product->campaign()->raised() }} of @currency {{$product->campaign()->formattedTarget ?? ''}} {{__('raised')}}</div>
                  </div>
                </div>
              </div>

              @if( $loop->iteration % 2 == 0 )
                <div class="w-100"></div>
              @endif
            </div>

          @empty
            <div class="alert alert-warning mx-auto" role="alert">
              {{__('No results.')}}
            </div>

          @endforelse

        </div>
          <div class="text-center">
              <a class="btn btn-outline-accent" href="{{route('campaigns.index')}}">{{__('View more campaigns')}}<i class="czi-arrow-right font-size-ms ml-1"></i></a>
          </div>
      </div>
    </div>

    </div>
  </section>
  @endif

  <!-- Featured campaigns (Carousel)-->
  <section id="featured-campaigns" class="container position-relative pt-3 pt-lg-0 pb-5 mt-lg-n12" style="z-index: 10; @if(!empty($searchedProducts)) margin-top: 20px !important; @endif">
    <div class="card px-lg-2 border-0 box-shadow-lg">
      <div class="card-body px-4 pt-5 pb-4">

        <h2 class="h3 text-center">{{__('Discover featured campaigns')}}</h2>
        <p class="text-muted text-center">{{__('Every week we hand-pick some of the best items from our collection')}}</p>

          <!-- Multiple items + Static controls outside + No dots + Loop (Responsive) -->
          <div class="cz-carousel cz-controls-static cz-controls-outside">
            <div class="cz-carousel-inner" data-carousel-options='{"items": 3, "nav": true, "responsive": {"0":{"items":1,"gutter":20},"500":{"items":2, "gutter": 18},"768":{"items":3, "gutter": 20}, "1100":{"gutter": 24}}}'>

            @foreach($campaigns as $campaign)
            <div class="card border-0 card product-card-alt">
              <div class="product-thumb">
                <div class="product-card-actions">
                  <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ route('campaigns.show',$campaign->id) }}"><i class="czi-eye"></i></a>
                </div>
                <a class="product-thumb-overlay" href="{{ route('campaigns.show',$campaign->id) }}"></a>
                <img src="{{ Thumbnail::thumb('campaign/'.$campaign->image, 300, 225, 'fit') }}" alt="">
              </div>
              <div class="card-body">
                  <div class="d-flex flex-wrap justify-content-between align-items-start pb-2">
                    <div class="text-muted font-size-xs mr-1">{{__('by')}} <a class="product-meta font-weight-medium" href="{{ route('campaigns.show',$campaign->id) }}">{{$campaign->business->name}}</a></div>
                  </div>
                  <h3 class="product-title font-size-sm mb-2"><a href="{{route('campaigns.show',$campaign->id)}}">{{$campaign->name}}</a></h3>
                  <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="font-size-sm mr-2"><i class="czi-download text-muted mr-1"></i>{{$campaign->sales()}}<span class="font-size-xs ml-1">{{__('Sales')}}</span></div>
                    <div class="py-1"><i class="czi-money-bag text-muted mr-1"></i>@currency {{$campaign->raised()}} {{__('of')}} @currency {{$campaign->formattedTarget}} {{__('raised')}}</div>
                  </div>
              </div>
            </div>
            @endforeach

            </div>
          </div>

      </div>
    </div>
  </section>


  <section id="featured-products"  class="container position-relative py-5 pb-5 pt-lg-0" style="z-index: 10;">
    <div class="card px-lg-2 border-0 box-shadow-lg">
      <div class="card-body px-4 pt-5 pb-4">
        <h2 class="h3 text-center">{{__('Discover featured products')}}</h2>
        <p class="text-muted text-center">{{__('Every week we hand-pick some of the best items from our collection')}}</p>

        <!-- Multiple items + Static controls outside + No dots + Loop (Responsive) -->
        <div class="row">
          @forelse($products as $product)
            <div class="col-md-4 col-sm-6 px-2 mb-grid-gutter">
              <div class="card border-0 product-card-alt">
                <div class="product-thumb">
                  <div class="product-card-actions">
                    <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ route('products.show',$product->id) }}"><i class="czi-eye"></i></a>
                    <button class="btn btn-light btn-icon btn-shadow font-size-base mx-2 add-cart" type="button"
                            data-campaign-id="{{$product->campaign()->id ?? 0}}"
                            data-product-id="{{$product->id}}"><i class="czi-cart"></i></button>
                  </div>
                  <a class="product-thumb-overlay" href="{{ route('products.show',$product->id) }}"></a>
                  <img src="{{ Thumbnail::thumb('product/'.$product->image, 400, 225, 'fit') }}" alt="">
                </div>

                <div class="card-body">
                  <div class="d-flex flex-wrap justify-content-between align-items-start pb-2">
                    <div class="text-muted font-size-xs mr-1">{{__('by')}} <a class="product-meta font-weight-medium" href="{{route('campaigns.show',$product->campaign()->id)}}">{{$product->campaign()->business->name ?? ''}}</a></div>
                  </div>
                  <h3 class="product-title font-size-sm mb-2">
                    <a href="{{route('products.show',$product->id)}}">{!! \Illuminate\Support\Str::of($product->title)->replaceMatches('/'.request('string').'/i', function ($match) { return '<b class="text-warning">'.$match[0].'</b>'; }) !!}</a></h3>
                  <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="font-size-sm mr-2"><i class="czi-download text-muted mr-1"></i>{{ __('Only :units left',['units'=>$product->units]) }}</div>
                    <div class="py-1"><i class="czi-money-bag text-muted mr-1"></i>@currency {{ $product->campaign()->raised() }} of @currency {{$product->campaign()->formattedTarget ?? ''}} {{__('raised')}}</div>
                  </div>
                </div>
              </div>
            </div>
            {{--              @if( $loop->iteration % 3 == 0 )--}}
            {{--              <div class="w-100"></div>--}}
            {{--              @endif--}}

          @empty
            <div class="alert alert-warning mx-auto" role="alert">
              {{__('No results.')}}
            </div>

          @endforelse
        </div>
            </div>
        </div>

    <div class="text-center">
      <a class="btn btn-outline-accent" href="{{route('campaigns.index')}}">{{__('View more campaigns')}}<i class="czi-arrow-right font-size-ms ml-1"></i></a>
    </div>

  </section>


  @include('shared.partials.footer')
@endsection

@prepend('scripts')
  <script src="{{ asset('js/vendor.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script src="{{ asset('js/theme.min.js') }}"></script>
  <script src="{{ asset('js/custom.js') }}"></script>
  <script src="{{ asset('js/cart.js') }}?v=4"></script>

  <script>
  window.onload = function() {
    var url = new URL(window.location.href).searchParams;
    var string = url.get("string");
    var category = url.get("category");
    if(string || category){
      var element = document.getElementById("search-products");
      element.scrollIntoView();
    }
    $('#string').change(function() {
      $('#homefilterForm').submit();
    });
  }
  </script>
  <!-- JavaScript libraries, plugins and custom scripts-->
@endprepend
