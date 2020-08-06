@component('mail::message')

{{__('Congratulations :bofirstname',['bofirstname'=>$campaign->business->user->name])}}

{{__('Your campaign  :name was successful, you have reached your campaign target goal.',['name'=>$campaign->name])}}

@component('mail::button', ['url' => route('campaign.show',$campaign->id)])
{{__('View your campaign')}}
@endcomponent

{{__('As an extra bonus, we will be returning 18% of the campaign fees that you paid for the use of the LiUU crowdliquidity platform to you in the next few days.')}}

{{__('Best Regards,')}}<br>
{{ config('app.name') }}
@endcomponent
