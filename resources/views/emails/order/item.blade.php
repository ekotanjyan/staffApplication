<style>
table td:first-of-type {
    width: 200px;
}
.table table{
    margin:0px auto !important;
}
.table th{
    border-bottom: 0px !important;
}
</style>
@component('mail::message')
@component('mail::table')
| | |
|---------|:--------------|
| {{__('Shop')}}: |  {{$suborder->campaign->business->name}} |
| {{__('Campaign')}}: |    <a href="{{route('campaigns.show',$suborder->campaign->id)}}">{{$suborder->campaign->name}}</a>   |
| {{__('Redeemable')}}: | @dateJFY($suborder->product->start_date) - @dateJFY($suborder->product->end_date) |
| {{__('Order ID')}}: | {{$suborder->order_id}} |
| {{__('Ordered')}}: | @dateJFY($suborder->order->created_at) |
| {{__('Email')}}: | {{$suborder->order->user->email}} |
| {{__('Product')}}: | {{$suborder->product->title}} <u><small>{{$suborder->product->category->name}}</small></u> |
| | {{\Illuminate\Support\Str::limit($suborder->product->description,125) }} |
| {{__('Unit price')}}: | {{$suborder->product->price}} {{$suborder->charge->currency->name }}|
| {{__('QR ID')}}: |  {{$qr->id}} |
| {{__('QR')}}: | <img style="max-width:150px" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->generate( route('qr.product',$qr) )) !!} "> |
@endcomponent



<br>
{{__('Best Regards')}},<br>
{{ config('app.name') }}
@endcomponent