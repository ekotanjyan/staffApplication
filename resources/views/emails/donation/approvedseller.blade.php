@component('mail::message')
    {!!  __('Hello :firstname',['firstname'=>$campaign->business->user->name]) !!}
    <br>{!! __('A customer,  :emailaddr made a donation', ['emailaddr'=>$charge->user->email]) !!}
    <br><br> {!! __('Information for donation for campaign') !!}  <a href="{{route('campaigns.show',$campaign->id)}}">{{$campaign->name}}</a>
    <br><br>{!! __('Placed on  :date',['date'=> date('Y-m-d H:i:s')]) !!}
    <br>{!! __('Donation amount: :currency :amount',['amount' => number_format($charge->amount/100,2,'.', "'"), 'currency' => $charge->currency->name]) !!}
    <br><br>{!! __('Best Regards,') !!}
    {{--{!! __('<br>LiUU’s team') !!}--}}
@endcomponent