@component('mail::message')

{{__('Dear :boname',['boname'=>$campaign->user->name])}}

{{__('Your campaign :name has been paused.',['name'=>$campaign->name])}}

{{$reason ?? ''}}

{{__('Please contact us if you feel this could be a mistake.')}}

{{__('Best Regards,')}}<br>
{{ config('app.name') }}
@endcomponent
