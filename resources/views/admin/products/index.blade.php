@extends('backend.layouts.page_dashboard')

@section('title', __('Products') )

@push('styles')
@endpush

@section('content')

  <div class="row d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
    <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{__('Your Products')}}
    <span class="badge badge-secondary font-size-sm text-body align-middle ml-2">{{$products->count()}}</span></h2>

    <button type="button" class="btn btn-info float-right">
      <a href="{{ route('product.create') }}" class="text-white">{{__('New Product')}}</a>
    </button>
  </div>

  @if( $products->isEmpty() )
    <div class="alert alert-info mt-4">
      {{ __('There are no products yet') }}. <a href="{{ route('product.create') }}">{{ __('Create one now') }}</a>.
    </div>
  @else

  <div class="row pt-3 mx-n2">
      @foreach( $products as $product )

      @php  $product_link = $product->campaign() ? route('products.show', $product->id) : route('product.edit', $product->id); @endphp
      <!-- Product-->
      <div class="col-lg-4 col-md-4 col-sm-6 px-2 mb-grid-gutter">
        <div class="card product-card-alt">
            <div>
                <div class="product-thumb">
                    <div class="product-card-actions">
                        <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{$product_link}}"><i class="czi-eye"></i></a>
                        <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ route('product.edit', $product->id) }}"><i class="czi-edit text-info"></i></a>
                    </div>

                    <a class="product-thumb-overlay" href="{{$product_link}}"></a>
                    <img src="{{Thumbnail::thumb('product/'.$product->image, 300, 225 )}}" />

                </div>
                <a class="btn btn-secondary btn-icon float-right d-lg-none position-absolute" style="z-index: 2; top: 0; right: 0;" role="button" href="{{ route('product.edit', $product->id) }}">
                    <i class="czi-edit text-info"></i>
                </a>
            </div>

          <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start pb-2">
              <div class="text-muted font-size-s mr-1"><a class="product-meta font-weight-medium" href="{{$product_link}}">{{ $product->title }}
              @if(!$product->campaign()) <br><small class="text-warning">{{__('Campaign non active')}}</small>@endif</a></div>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center">
              <div class="font-size-sm mr-2"><i class="czi-money-bag text-muted mr-1"></i>@currency{{ $product->formattedPrice }}</div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
  </div>

  @endif

@endsection

@push('scripts')
@endpush
