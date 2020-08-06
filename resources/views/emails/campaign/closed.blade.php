@component('mail::message')

{{__('Hello :name',['name'=>$campaign->user->name])}}

{{__('Your campaign  :name has been cancelled.',['name'=>$campaign->name])}}

{{$reason ?? ''}}

{{__('If this was a mistake, please reply to this email.')}}

{{__('Best Regards')}}<br>
{{ config('app.name') }}
@endcomponent
