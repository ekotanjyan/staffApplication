@component('mail::message')

{{__('Dear :name',['name'=>$charge->user->name])}},

{{__('Your payment was declined. ')}}<br>
{{__('Order # :order',['order'=>$charge->order->id])}}<br>
{{__('Payment Id:')}} {{$charge->id}}<br>
{{__('Placed on')}}: {{ \Carbon\Carbon::parse($charge->order->created_at)->format('j F, Y') }}

{{__('Please visit this link for more information.')}}

@component('mail::button', ['url' => route('stripe.charges')])
{{__('View Order')}}
@endcomponent

<br>
{{__('Business')}}: {{$charge->seller->activeCampaign()->business->name ?? ''}}<br>
{{__('Email')}}: <u>{{$charge->seller->email}}</u><br>
{{$charge->seller->address}}

@component('mail::table')
| {{__('Item Name')}}  | {{__('Quantity')}}  | {{__('Price')}} | {{__('Qr Code')}} |
|:------------- |:-------------:|:--------|------------:|
@foreach($charge->order->suborders->whereIn('product_id',$charge->seller->products->pluck('id'))->where('order_id',$charge->order->id) as $suborder)
@php $deleted = ($suborder->allcampaign->deleted_at || $suborder->allproduct->deleted_at) ? "<br><b> | No longer available</b>" : ''; @endphp
| <a href="{{route('products.show',$suborder->allproduct->id)}}">{{$suborder->allproduct->title}}</a> <br><br><b> {{__('Campaign:')}}</b><a href="{{route('campaigns.show',$suborder->allcampaign->id)}}">{{$suborder->allcampaign->name}} {!! $deleted !!}</a> | {{$suborder->quantity}}  | {{$suborder->allproduct->formattedPrice}}  | ![alt text]({{asset('storage/'.$suborder->qr_path)}} "{{$suborder->allproduct->title}} {!! $deleted !!}") |
@endforeach
| **{{__('Total')}}** | |{{$charge->amount / 100}} **{{$charge->currency->name}}** | |
@endcomponent

{{__('Please contact us if you need help.')}}

{{__('Best Regards,')}}<br>
{{ config('app.name') }}
@endcomponent