<style>
table th:first-of-type {
    width: 200px;
}
</style>

@component('mail::message')

{{ __('Hello') }} {{$charge->order->user->name}}

{{__('Thank your for your support.')}} 

{{__('Information for order')}}  #{{$charge->order->id}}<br>
{{__('Placed on')}}: {{ \Carbon\Carbon::parse($charge->order->created_at)->format('j F, Y') }}

<br>
{{__('Business')}}: {{$charge->seller->activeCampaign()->business->name ?? ''}}<br>
{{__('Email')}}: <u>{{$charge->seller->email}}</u><br>
{{$charge->seller->address}}

@component('mail::table')
| {{__('Item Name')}}  | {{__('Quantity')}}  | {{__('Price')}} |
|:------------- |:-------------:|:--------|
@foreach($charge->order->suborders->whereIn('product_id',$charge->seller->allproducts->pluck('id'))->where('order_id',$charge->order_id) as $suborder)
@php $deleted = ($suborder->allcampaign->deleted_at || $suborder->allproduct->deleted_at) ? "<br><b> | No longer available</b>" : ''; @endphp
| <div style="position:relative"><a href="{{route('products.show',$suborder->allproduct->id)}}">{{$suborder->allproduct->title}}</a> <br><br><div><b> {{__('Campaign:')}}</b><a href="{{route('campaigns.show',$suborder->allcampaign->id)}}">{{$suborder->allcampaign->name}} {!! $deleted !!}</a></div></div> | {{$suborder->quantity}}  | {{$suborder->allproduct->formattedPrice}}  |
@endforeach
| **{{__('Total')}}** | |{{$charge->amount / 100}} **{{$charge->currency->name}}** |
@endcomponent

{{__('You can find attached the vouchers for each product you have acquired. Please show this to the business managers when you decide to redeem the product you acquired')}}


<br>
{{__('Best Regards')}},<br>
{{ config('app.name') }}
@endcomponent
