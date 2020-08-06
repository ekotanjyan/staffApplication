@extends('frontend.layouts.app',[
'business'=> __('Checkout')
])

@section('title', 'Checkout')

@section('breadcrumbs')
    @include('frontend.partials.breadcrumbs', ['breadcrumbs' => [
        __('Cart') => route('cart.index'),
        __('Checkout'),
    ]])
@endsection

@section('content')
  <div class="container">
    <div class="row">

      <section class="col-lg-8 p-sm-5 pt-5 pb-5">
        <form action="{{route('stripe.checkout')}}" method="post" id="checkout-form" onsubmit="$('.modal').modal('show')">
          <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
          <!-- Shipping address-->
          <h2 class="h6 pb-3 mb-3 border-bottom">{{__('Billing details')}}</h2>
          <p><small class="text-muted">{{__('Complete if the billing details are different than the user details.')}}</small></p>

          <div class="row mb-3">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="checkout-fn">{{__('Full name')}}</label>
                <input class="form-control" required type="text" name="name" id="checkout-fn">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="checkout-email">{{__('E-mail')}} <small class="text-muted">({{__('Optional')}})</small></label>
                <input class="form-control" type="email" name="email" id="checkout-email">
              </div>
            </div>
          </div>
          @if(empty($user['stripe_card_id']))
            <div class="w-100">
              <label for="card-element"></label>
              <div class="carddata" id="card-element"></div>
              <div id="card-errors" role="alert"></div>
            </div>
          @else
            <div class="alert alert-secondary" role="alert">
              {{__('You have already stored a credit card during your previous transaction. If you want to use a different one, please click')}} <a href="{{route('stripe.add-credit-card')}}" class='alert-link'>{{__('here')}}</a>.
            </div>
          @endif
{{--          <div class="custom-control custom-checkbox custom-control mt-2">--}}
{{--            <input class="custom-control-input" type="checkbox" name="immediate_charge" id="immediate_charge" checked>--}}
{{--            <label class="custom-control-label" for="immediate_charge">{{__('Immediate charge')}}</label>--}}
{{--          </div>--}}
          @if(empty($user['stripe_card_id']))
          <div class="custom-control custom-checkbox custom-control mt-2">
            <input class="custom-control-input" type="checkbox" name="store_cc" id="store_cc" checked>
            <label class="custom-control-label" for="store_cc">{{__('Store credit card details for future payments?')}}</label>
          </div>
          @endif
          <!-- Navigation (desktop)-->
          <div class="d-none d-lg-flex pt-4 mt-3">
            <div class="w-50 pr-3"><a class="btn btn-secondary btn-block" href="{{route('cart.index')}}">
                <i class="czi-arrow-left mt-sm-0 mr-1"></i>
                <span class="d-none d-sm-inline">{{__('Back to Cart')}}</span>
                <span class="d-inline d-sm-none">{{__('Back')}}</span></a>
            </div>
            <div class="w-50 pl-2">
              {{--todo submit form--}}
              <button class="btn btn-primary btn-block" href="{{route('stripe.checkout')}}">
                <span class="d-none d-sm-inline">{{__('Proceed to Payment')}}</span>
                <span class="d-inline d-sm-none">{{__('Next')}}</span>
                <i class="czi-arrow-right mt-sm-0 ml-1"></i></button>
            </div>
          </div>
          <!-- Navigation (mobile)-->
          <div class="d-lg-none pt-4 mt-3">
            <div class="w-100">
              <div class="w-100">
                {{--todo submit form--}}
                <button class="btn btn-primary btn-block" href="{{route('stripe.checkout')}}">
                  <span class="d-sm-inline">{{__('Proceed to Payment')}}</span>
                  <i class="czi-arrow-right mt-sm-0 ml-1"></i></button>
              </div>
              <div class="w-100"><a class="btn btn-secondary btn-block" href="{{route('cart.index')}}">
                  <i class="czi-arrow-left mt-sm-0 mr-1"></i>
                  <span class="d-sm-inline">{{__('Back to Cart')}}</span></a>
              </div>
            </div>
          </div>
        </form>
      </section>


      <aside class="col-lg-4 d-none d-lg-block">
        <hr class="d-lg-none">
        <div class="cz-sidebar-static h-100 ml-auto border-left">

          <div class="widget mb-3">
            <h2 class="widget-title text-center">{{__('Order summary')}}</h2>

            @foreach(Cart::content() as $item)
              <div class="media align-items-center py-2 border-bottom">
                <a class="d-block mr-2" href="{{route('products.show',[$item->model->id])}}">
                  <img class="rounded-sm" width="64" src="{{ getStorageImage($item->model, $item->model->image, 100, 100) }}" alt="{{$item->name}}"></a>
                <div class="media-body pl-1">
                  <h6 class="widget-product-title"><a href="{{route('products.show',[$item->model->id])}}">{{$item->name}}</a></h6>
                  <div class="widget-product-meta"><span class="text-accent border-right pr-2 mr-2">@currency {{$item->formattedPrice}} </span> x {{$item->qty}}</div>
                </div>
              </div>
            @endforeach

            <ul class="list-unstyled font-size-sm pt-3 pb-2 border-bottom">
              <li class="d-flex justify-content-between align-items-center"><span class="mr-2">{{__('Subtotal')}}:</span><span class="text-right">@currency {{Cart::subtotal()}}</span></li>
              <li class="d-flex justify-content-between align-items-center"><span class="mr-2">{{__('Taxes')}}:</span><span class="text-right">{{__('Included')}}</span></li>
            </ul>
            <h3 class="font-weight-normal text-center my-4">{{__('Total')}}: @currency {{Cart::priceTotal()}}</h3>
          </div>

        </div>
      </aside>


    </div>
   <div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered justify-content-center" role="document">
     <div class="modal-body text-center">
      <div class="spinner-border" style="width: 6rem; height: 6rem;" role="status"></div>
      <br>
      <span class="font-weight-bold font-size-lg text-primary">Please wait while your transaction is processing...</span>
     </div>
    </div>
   </div>
  </div>
@endsection
@push('scripts')
  @if(empty($user['stripe_card_id']))
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{$stripe_key}}');
        var elements = stripe.elements();
        var form = document.getElementById('checkout-form');

        var elements = stripe.elements({
            fonts: [
                {
                    cssSrc: "{{asset('css/general.css')}}"
                }
            ],
            // Stripe's examples are localized to specific languages, but if
            // you wish to have Elements automatically detect your user's locale,
            // use `locale: 'auto'` instead.
            locale: window.__exampleLocale
        });

        var card = elements.create('card', {
            hidePostalCode: true,
            'style': {
                'base': {
                    'fontFamily': 'Arial, sans-serif',
                    'fontSize': '18px',
                    'color': '#C1C7CD',
                },
                'invalid': {
                    'color': 'red',
                },
            }
        });

        card.mount('#card-element');
        var stripeResponseHandler = function (status, response) {
            var form = document.getElementById('checkout-form');
            if (response.error) {
                // Show the errors on the form:
            } else {
                var token = response.id;
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token);
                form.submit();
            }
        };

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Stripe.card.createToken(form, stripeResponseHandler);
        });

        function stripeTokenHandler(token) {
            var form = document.getElementById('checkout-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            form.submit();
        }

        function createToken() {
            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    stripeTokenHandler(result.token);
                }
            });
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            createToken();
        });
    </script>
  @endif
@endpush
