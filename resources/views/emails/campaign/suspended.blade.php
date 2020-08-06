@component('mail::message')

{{__('Dear :boname',['boname'=>$campaign->user->name])}}

{{__('Your campaign  :name has been suspended. You probably didn’t comply with one of our rules for businesses: ',['name'=>$campaign->name])}}

{{$reason ?? ''}}

@component('mail::button', ['url' => 'https://liuu.world/en/rules-for-businesses/'])
{{__('View rules')}}
@endcomponent

{{__('Please contact us if you feel this could be a mistake.')}}

{{__('Best Regards,')}}<br>
{{ config('app.name') }}
@endcomponent
