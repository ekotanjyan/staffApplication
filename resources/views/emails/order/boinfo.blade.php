@component('mail::message')

{{ __('Hello :name',['name'=>$charge->seller->name]) }}

{{ __('Customer :email made a purchase.',['email'=>$charge->user->email]) }}

{{ __('Information for order  #:order',['order'=>$charge->order->id]) }}<br>
{{ __('Placed on: :date',['date'=>\Carbon\Carbon::parse($charge->created_at)->format('j F, Y')]) }}


@component('mail::table')
| {{__('Item Name')}}  | {{__('Quantity')}}  | {{__('Price')}} |
|:------------- |:-------------:|:--------|
@foreach($charge->order->suborders->whereIn('product_id',$charge->seller->products->pluck('id'))->where('order_id',$charge->order->id) as $suborder)
 | <div style="position:relative"><a href="{{route('products.show',$suborder->allproduct->id)}}">{{$suborder->allproduct->title}}</a> <br><br><div><b> {{__('Campaign:')}}</b><br><a href="{{route('campaigns.show',$suborder->allcampaign->id)}}">{{$suborder->allcampaign->name}}</a></div></div>| {{$suborder->quantity}}  | {{$suborder->allproduct->formattedPrice}}  |
@endforeach
| **{{__('Total')}}** | |{{$charge->amount / 100}} **{{$charge->currency->name}}** |
@endcomponent


<br>
{{__('Best Regards')}},<br>
{{ config('app.name') }}
@endcomponent
