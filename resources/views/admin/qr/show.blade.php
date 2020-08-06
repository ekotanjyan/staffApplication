@extends('backend.layouts.page_dashboard')

@section('title', __('Qr Codes') )

@section('content')

@push('styles')
<style>
@media (max-width: 576px) { 
    .btn-sm{
        width:100%
    }
}
</style>
@endpush

@php $disabled = '' @endphp
<div class="d-sm-flex flex-wrap justify-content-between align-items-center">
    <h2 class="h3 py-2 mx-2 text-center">{{ __('QR Code') }}
        @if( $qr->passValidation() ) <span class="badge badge-success">{{__('Valid')}}</span>@endif
    </h2>

    @if ( session('notvalid') || !$qr->passValidation() )
        @php $disabled = 'disabled' @endphp
        @if($qr->scanned())
        <div class="alert alert-danger w-100 text-center" role="alert">
            {{__('Used')}}
        </div>
        @else
        <div class="alert alert-danger" role="alert">
            {{ __('This voucher is not valid: either it has already been used, or it\'s outside the period in which it could be redeemed. If you believe this is a mistake, please contact us at support@liuu.world') }}
        </div> 
        @endif  
    @endif

</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title border-bottom pb-3">{{$suborder->product->title}}
    <span class="badge badge-secondary ml-2">{{$suborder->product->category->name}}</span><br>
    <span class="text-muted font-size-sm">{{$suborder->product->description}}</span>
    </h5>

        @if($qr->scanned_at)
        <p class="card-text m-0"> 
        <strong>{{__('First used at: ')}}</strong> {{\Carbon\Carbon::parse($qr->scanned_at)->format('j F, Y - H:i:s')}}</p>
        @endif
        
        <p class="card-text m-0"><strong>{{__('QR ID')}}#:</strong> {{$qr->id}} </p>

        <p class="card-text m-0"><strong>{{__('Scans')}}#:</strong> {{$qr->scans}} </p>
        
        <p class="card-text m-0"><strong>{{__('Order')}}#:</strong> {{$suborder->order_id}} </p>
        
        <p class="card-text m-0">
            <strong>{{__('Redeemable dates')}}:</strong> @dateJFY($suborder->product->start_date) - @dateJFY($suborder->product->end_date)
        </p>
        <p class="card-text m-0">
            <strong>{{__('Purchase date')}}:</strong> @dateJFY($suborder->created_at) 
            @if(!$qr->chargePaid())<span class="badge badge-danger">{{__('Not paid yet')}}</span>@endif
        </p>
        <p class="card-text">
            <strong>{{__('Client')}}:</strong> {{$suborder->order->user->name}}
        </p>

        <form method="post" action="{{ route('qr.update',[$qr->id]) }}" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-sm-4 form-group">
                    <label for="unp-standard-price">{{__('Value')}}:</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="czi-euro"></i></span></div>
                        <input class="form-control" type="text" id="unp-standard-price" value="{{$qr->price}}" disabled>
                    </div>
                </div>
                @if($suborder->product->category->usage == 'multiple')
                <div class="col-sm-4 form-group">
                    <label for="unp-extended-price">{{__('Amount left')}}: </label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="czi-euro"></i></span></div>
                        <input class="form-control" type="number" step="0.01" value="{{$qr->amountLeft()}}" min="0" max="{{$suborder->price}}" id="unp-extended-price" disabled>
                    </div>
                </div>
                <div class="col-sm-4 form-group">
                    <label for="unp-extended-price">{{__('Use')}}: </label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="czi-euro"></i></span></div>
                        <input class="form-control" type="number" step="0.01" value="" name="usage" min="0" max="{{$suborder->price}}" {{$disabled}} id="unp-extended-price">
                    </div>
                </div>
                @endif
            </div>

            <button href="#" type="submit" class="btn btn-sm btn-primary" {{$disabled}}>{{__('Confirm use of voucher')}}</button>
            <a href="{{route('campaign.index')}}" class="btn btn-sm btn-secondary float-right">{{__('Do not use voucher')}}</a>
        </form>
  </div>
</div>

@endsection