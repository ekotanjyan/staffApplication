@extends('backend.layouts.page_dashboard')

@section('title', __('Businesses') )
@push('styles')
@endpush
@section('content')
    <div class="d-sm-flex flex-wrap justify-content-between align-items-center">
        <h2 class="h3 py-2 mx-2 text-center text-sm-left">{{ __('Donations made') }}</h2>
        <div class="py-2">
        </div>
    </div>
        <table class="table table-responsive">
            <thead>
            <tr>
                <th data-container="body" 
                    data-toggle="popover" 
                    data-placement="top" 
                    data-trigger="hover" 
                    data-content="{{__('This is the  ID of the transaction.')}}">{{__('ID*')}}</th>
                <th data-container="body"
                    data-toggle="popover"
                    data-placement="top"
                    data-trigger="hover"
                    data-content="{{__('This is the name of supporter ')}}">{{__('Supporter*')}}</th>
                <th data-container="body"
                    data-toggle="popover"
                    data-placement="top"
                    data-trigger="hover"
                    data-content="{{__('This is the campaign name ')}}">{{__('Campaign*')}}</th>

                <th data-container="body" 
                    data-toggle="popover" 
                    data-placement="top" 
                    data-trigger="hover" 
                    data-content="{{__('This is the donation amount).')}}">
                    {{__('Amount')}}</th>


                <th>{{__('Date')}}</th>
                <th data-container="body"
                    data-toggle="popover" 
                    data-placement="top" 
                    data-trigger="hover" 
                    data-content="{{__('If a user has initiated a transaction that was not completed, it will be listed here as failed.')}}">
                    {{__('Status')}}*</th>

                <th data-container="body" 
                    data-toggle="popover" 
                    data-placement="top" 
                    data-trigger="hover" 
                    data-content="{{__('It will indicate if a completed transaction has finally resulted in a payment.')}}">
                    {{__('Paid')}}*</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            {{--todo pagination--}}
			<?php
			use App\Charge;
			use App\StripeTool;
			?>
            <?php $totalAmount=0; ?>
            @if(count($charges) > 0)
                @foreach ($charges as $charge)
                    <?php $totalAmount += ($charge->amount / 100);?>
                    <tr>
                        <td>{{$charge['id']}}</td>
                        <td>{{$charge->user->name}}</td>
                        <td>{{$charge->campaign->getNameAttribute(null)}}</td>
                        <td><?php echo number_format($charge['amount'] / 100, 2, '.', ''); ?></td>
                        <td>@dateJFY($charge['created_at'])</td>
                        <td><?php echo Charge::itemAlias(Charge::STATUSES, $charge['status']); ?></td>
                        <td><?php echo Charge::itemAlias(Charge::PAID, $charge['is_paid']); ?></td>
                        <td><a href="{{route('campaigns.show',$charge->campaign->id )}}" class="btn btn-success btn-sm">View</a></td>
                    </tr>
                @endforeach
            @endif
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td>{{__('Totals:')}}</td>
                    <td>{{ number_format($totalAmount, 2, '.', '') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
@endsection
@push('scripts')
@endpush