@extends('frontend.layouts.app',[
'business'=> __('Cart')
])

@section('title', 'Cart')

@push('scripts')
 <script>
  function checkoutFormValidity(i, max, e){
      if (e.value > max) {
          document.getElementById("validity" + i).style.display="block";
          document.getElementById("checkoutBtn").classList.add('disabled');
      } else {
          document.getElementById("validity" + i).style.display="none";
          document.getElementById("checkoutBtn").classList.remove('disabled');
      }
  }
 </script>
@endpush

@section('breadcrumbs')
    @include('frontend.partials.breadcrumbs', ['breadcrumbs' => [
        __('Cart'),
    ]])
@endsection

@section('content')
<div class="container pb-5 mb-2 mb-md-4">
<div class="row">
  @if($cart->count() > 0)

  <section class="col-lg-8 px-5 pt-4">

  <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-3">
    <div class="py-1"><a class="btn btn-outline-accent btn-sm" href="{{ url()->previous() }}">
      <i class="czi-arrow-left mr-1 ml-n1"></i>{{__('Back to shopping')}}</a>
    </div>
    <div class="d-none d-sm-block py-1 font-size-ms">{{__('Items')}} {{$cart->count()}}</div>
    <div class="py-1"><a class="btn btn-outline-danger btn-sm" href="{{route('cart.destroy')}}">
      <i class="czi-close font-size-xs mr-1 ml-n1"></i>{{__('Clear cart')}}</a>
    </div>
  </div>

    @foreach($cart as $item)
    <!-- Item-->
    <div class="d-sm-flex justify-content-between my-4 pb-4 border-bottom cart_item">

      <div class="media media-ie-fix d-block d-sm-flex align-items-center text-center text-sm-left">
        <a class="d-inline-block mx-auto mr-sm-4" href="{{route('products.show',[$item->model->id])}}" style="width: 10rem;">
          <img class="rounded-lg" src="{{ getStorageImage($item->model, $item->model->image, 200, 200) }}" alt="Product">
        </a>
        {{-- Desktop and medium screens display --}}
        <div class="media-body d-none d-sm-block">
          <h3 class="product-title font-size-base mb-2"><a href="{{route('products.show',[$item->model->id])}}">{{$item->name}}</a>
            <p><small>{{$item->options->campaign->name}}</small></p>
          </h3>
          <div class="font-size-sm"><span class="text-muted mr-2">{{__('Available from')}}:</span> @dateJFY($item->model->start_date) </div>
          <div class="font-size-sm"><span class="text-muted mr-2">{{__('Use or collect by')}}:</span> @dateJFY($item->model->end_date) </div>
          <div class="font-size-lg text-accent pt-2">@currency {{$item->price}}</div>
        </div>
        {{-- Mobile display(xs) --}}
          <div class="media-body d-block d-sm-none">
              <h3 class="product-title font-size-base mt-2"><a href="{{route('products.show',[$item->model->id])}}">{{$item->name}}</a>
                  <p><small>{{$item->options->campaign->name}}</small></p>
              </h3>
              <div class="font-size-sm"><span class="text-muted">{{__('Available from')}}:</span></div>
              <div class="font-size-sm">@dateJFY($item->model->start_date)</div>
              <div class="font-size-sm"><span class="text-muted">{{__('Use or collect by')}}:</span></div>
              <div class="font-size-sm">@dateJFY($item->model->end_date)</div>
              <div class="font-size-lg text-accent pt-2">@currency {{$item->price}}</div>
          </div>
      </div>

      <div class="pt-2 pt-sm-0 pl-sm-3 mx-auto mx-sm-0 text-center text-sm-left" style="max-width: 9rem;">
        <div class="form-group mb-0">
          <label class="font-weight-medium" for="quantity{{$loop->iteration}}">{{__('Quantity')}}</label>
          <input class="form-control update-cart" type="number" data-product-price="{{$item->price}}" data-product-id="{{$item->rowId}}" id="quantity{{$loop->iteration}}" value="{{$item->qty}}" min="1" max="{{$item->model->units}}" oninput="checkoutFormValidity({{$loop->iteration}}, {{$item->model->units}}, this)">
          <span id="validity{{$loop->iteration}}" class="validity" style="font-size:11px; display: none">{{__('The requested units exceed available amount')}} {{$item->model->units}}</span>
        </div>
        <button class="btn btn-link px-0 text-danger remove-cart" data-product-id="{{$item->rowId}}" type="button"><i class="czi-close-circle mr-2"></i>
          <span class="font-size-sm">{{__('Remove')}}</span>
        </button>
      </div>

    </div>
    <!-- end item -->
    @endforeach

  </section>

  <!-- SIDEBAR -->
  <aside class="col-lg-4 ">
      <hr class="d-lg-none">
      <div class="cz-sidebar-static h-100 ml-auto">
        <div class="text-center mb-4 pb-3 border-bottom">
            <h2 class="h6 mb-3 pb-1">{{__('Subtotal')}}</h2>
            <h3 class="font-weight-normal">@currency <span id='subtotal-cart'>{{Cart::subtotal()}}</span></h3>
        </div>
        <a class="btn btn-primary btn-shadow btn-block mt-4" id="checkoutBtn" href="{{route('cart.checkout')}}">
        <i class="czi-locked font-size-lg mr-2"></i>{{__('Secure Checkout')}}</a>
      </div>
  </aside>
  <!-- END SIDEBAR -->

  @else
  <div class="alert alert-info m-5" role="alert">
    {{__('Cart is empty')}}
  </div>
  @endif
</div>
</div>
@endsection
