@component('mail::message')
    {!! __('Hello :firstname', ['firstname' => $charge->user->name]) !!}
    <br>{!! __('Thank you for making a donation to') !!} {{$campaign->business->name.""}} {!! __('and its campaign') !!} <a href="'.route('campaigns.show',$campaign->id).'">{{$campaign->name}}</a>
    <br><br>{!! __('Information for your donation') !!}
    <br>{!! __('Placed on  :date',['date'=> date('Y-m-d H:i:s')]) !!}
    <br>{!! __('Donation amount: :currency :amount',['amount' => number_format($charge->amount/100,2,'.', "'"), 'currency' => $charge->currency->name]) !!}<br>
    <br><a href="{{route('campaigns.show',$campaign->id)}}"><h4>{{$campaign->business->name}}</h4></a>
    {!! __('Email')!!} : <u>{{$campaign->business->user->email.""}}</u><br>
    {{$campaign->business->address}}, {{$campaign->business->city}} ({{$campaign->business->zip}}), {{$campaign->business->country}}
    <br><br>{!!__('Best Regards,')!!}
    <br>{!! __('LiUU’s team') !!}
@endcomponent