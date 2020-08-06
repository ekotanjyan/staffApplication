<!-- Widget: Shopping cart -->
<div class="widget widget-cart px-3 pt-2 pb-3" id="mini-cart">

@if((count($cart) ?? []) > 0)
  <!-- Scrollable area -->
  <div style="max-height: 15rem;" data-simplebar data-simplebar-auto-hide="false">

    @foreach($cart as $item)
    <!-- Item -->
    <div class="widget-cart-item py-2 border-bottom">
      <button class="close text-danger remove-cart" data-product-id="{{$item->rowId}}" type="button" aria-label="Remove">
        <span aria-hidden="true">&times;</span>
      </button>
      <div class="media align-items-center">
        <a class="d-block mr-2" href="#">
          <img width="64" src="{{ getStorageImage($item->model, isset($item->model->image) ? $item->model->image : null, 100, 100) }}" alt="Product"/>
        </a>
        <div class="media-body">
          <h6 class="widget-product-title"><a href="#">{{$item->name}}</a></h6>
          <div class="widget-product-meta">
            <span class="text-accent mr-2">@currency {{$item->price}}</span>
            <span class="text-muted">x {{$item->qty}}</span>
          </div>
        </div>
      </div>
    </div>
    <!-- End Item -->
    @endforeach

  </div>

  <!-- Footer -->
  <div class="d-flex flex-wrap justify-content-between align-items-center py-3">
    <div class="font-size-sm mr-2 py-2">
      <span class="text-muted">{{__('Subtotal')}}:</span>
      <span class="text-accent font-size-base ml-1">@currency {{Cart::subtotal()}}</span>
    </div>
    <a class="btn btn-outline-secondary btn-sm" href="{{route('cart.index')}}">
      {{__('View cart')}}
      <i class="czi-arrow-right ml-1 mr-n1"></i>
    </a>
  </div>
  <a class="btn btn-primary btn-sm btn-block" href="{{ route('cart.checkout') }}">
    <i class="czi-card mr-2 font-size-base align-middle"></i>
    {{__('Checkout')}}
  </a>

  <script> document.getElementById('cart-badge-number').innerHTML = {{Cart::content()->count()}}; </script>

@else

    {{ __('Your cart is empty.') }}

@endif

</div>
