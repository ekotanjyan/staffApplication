@extends('backend.layouts.page_dashboard')

@section('title', __('Stripe add/update Credit Card') )

@push('styles')
@endpush

@section('content')

    <div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
        <h2 class="h3 py-2 mx-2 text-center text-sm-left">{{ __('Stripe add/update Credit Card') }}
            <span class="badge badge-secondary font-size-sm text-body align-middle ml-2"></span></h2>
        <div class="py-2">
        </div>
    </div>
    @if($user['stripe_card_id'])
        <div class="m-2 pb-4">
            <span>{{__('Card Number')}}: <b>**** **** **** {{$user['card_last_four']}}</b></span><br>
            <span>{{__('Card Brand')}}: <b>{{$user['card_brand']}}</b></span>
        </div>
    @endif
    <div class="m-2">
        <form action="{{route('stripe.add-credit-card')}}" method="post" id="payment-form" @if($user['stripe_card_id']) style="display: none" @endif class="alert_before_leave">
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
            <div class="form-row mb-3">
				<label for="card_name">{{__('Cardholder (optional)')}}</label>
				<input id="card_name" class="form-control mb-4" placeholder="{{__('CARDHOLDER')}}" />
                <label for="card-element"></label>
                <div id="card-element" style="width: 600px;"></div>
                <div id="card-errors" role="alert"></div>
            </div>
            <input type="submit" class="submit btn btn-success" value="{{__('Save card')}}">
        </form>
		@if($user['stripe_card_id'])
            <button class="btn btn-info mt-2"  id="edit_card">{{__('Edit Card')}}</button>
			<a class="btn btn-warning mt-2" id="delete_card">{{__('Delete credit card')}}</a>
		@endif
    </div>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
		var stripe = Stripe('{{$stripe_key}}');
		var elements = stripe.elements();
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
			var form = document.getElementById('payment-form');
			if (response.error) {
				// Show the errors on the form:
			} else {
				var token = response.id;
				var form = document.getElementById('payment-form');
				var hiddenInput = document.createElement('input');
				hiddenInput.setAttribute('type', 'hidden');
				hiddenInput.setAttribute('name', 'stripeToken');
				hiddenInput.setAttribute('value', token);
				form.appendChild(hiddenInput);
				form.submit();
			}
		};

		var form = document.getElementById('payment-form');
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			Stripe.card.createToken(form, stripeResponseHandler);
		});

		function stripeTokenHandler(token) {
			var form = document.getElementById('payment-form');
			var hiddenInput = document.createElement('input');
			hiddenInput.setAttribute('type', 'hidden');
			hiddenInput.setAttribute('name', 'stripeToken');
			hiddenInput.setAttribute('value', token.id);
			form.appendChild(hiddenInput);
			form.submit();
		}

		function createToken() {
			var card_name = document.getElementById('card_name').value;
			stripe.createToken(card, {name: card_name}).then(function (result) {
				if (result.error) {
					var errorElement = document.getElementById('card-errors');
					errorElement.textContent = result.error.message;
				} else {
					stripeTokenHandler(result.token);
				}
			});
		};
		var form = document.getElementById('payment-form');
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			createToken();
		});

		$(document).ready(function () {
		    //Edit Card
			$("#edit_card").click(function () {
				$("#payment-form").show();
				$("#edit_card").hide();
            });
			$('#delete_card').click(function(e){
			    e.preventDefault();
			    if(confirm("Are you sure you want to delete you credit card?"))
				{
				    window.location.href = '<?php echo route('stripe.delete-credit-card');?>';
				}
			})
		})

    </script>
@endpush
