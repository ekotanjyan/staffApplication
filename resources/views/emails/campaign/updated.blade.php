@component('mail::message')


@component('mail::table')

@php $updated = $allproducts = 0; @endphp
| {{ __('Campaign Name') }} | {{ __('Campaign Owner') }} | {{ __('# of Products') }}  | {{__('BO Email')}} |
| ------------- |:-------------:| --------:|---------|
@foreach($campaigns as $campaign)
| {{$campaign->nme}} | {{$campaign->business->user->name}}  | {{$campaign->products->count()}} | {{$campaign->business->user->email}} |
@php $updated++;  $allproducts += $campaign->products->count() @endphp
@endforeach
| {{__('Total')}} | {{$updated}} | {{$allproducts}} | |
@endcomponent

{{ __('Best Regards,') }}<br>
{{ config('app.name') }}
@endcomponent
