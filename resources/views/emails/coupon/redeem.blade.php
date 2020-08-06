@component('mail::message')

{{__('This product/service has just been redeemed. If you think there was a mistake, please get in contact with the vendor directly!')}}

{{__('Order')}} #: {{$suborder->order->id}} <br>
{{__('Product')}}: {{$product->title}}

{{__('Best Regards')}}<br>
{{ config('app.name') }}
@endcomponent