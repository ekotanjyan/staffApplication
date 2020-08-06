@component('mail::message')
{{ __('Hello :name',['name'=> $name]) }},<br>

{{ __('We have disconnected your Stripe account from your campaign on LiUU as requested, and as such you are not able to receive payments anymore.') }}<br>
{{ __('Until you connect a new account, your open campaign(s) will be paused and your clients will not be able to buy products/donate to your campaign.') }}

{{ __('If you believe this to be a mistake, please contact us.') }}

{{ __('Best Regards') }},<br>
{{ config('app.name') }}

@endcomponent
