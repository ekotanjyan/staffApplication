@component('mail::message')

{{ __('The business :name has requested approval for it\'s campaign.',['name'=>$campaign->business->name]) }}

@component('mail::button', ['url' => url("nova/resources/campaigns/{$campaign->id}"),'color'=>'blue'])
{{ __('View Campaign') }}
@endcomponent

{{__('Please review the campaign and either approve, disapprove or suggest changes.')}}

{{ __('The business owner email is: ') }} {{$campaign->business->user->email}}

{{ __('Best Regards') }}<br>
{{ config('app.name') }}

@endcomponent
