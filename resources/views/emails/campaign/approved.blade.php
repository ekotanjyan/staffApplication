@component('mail::message')


{{__('Hi :bofirstname',['bofirstname'=>$campaign->business->user->name])}},

{!! __('Your campaign :name has been approved.',['name'=>'<b>'.$campaign->name.'</b>']) !!}

@component('mail::button', ['url' => route('campaigns.show',$campaign->id)])
{{__('View your campaign')}}
@endcomponent

{{__('Best Regards,')}}<br>
{{ config('app.name') }}
@endcomponent
