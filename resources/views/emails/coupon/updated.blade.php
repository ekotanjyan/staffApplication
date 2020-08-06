@component('mail::message')

{{__('The amount of :used :currency has just been used from this voucher. 
The amount left on your voucher is :left. 
If you think there was a mistake, please get in contact with the vendor directly.',
['currency'=>$suborder->charge->currency->name,'used'=>$used,'left'=>$suborder->price - $suborder->qr_used])}}

{{__('Order')}} #: {{$suborder->order->id}} <br>
{{__('Product')}}: {{$product->title}}

{{__('Best Regards')}}<br>
{{ config('app.name') }}
@endcomponent