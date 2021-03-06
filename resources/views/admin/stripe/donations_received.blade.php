@extends('backend.layouts.page_dashboard')

@section('title', __('Businesses') )
@push('styles')
@endpush
@section('content')
    <div class="d-sm-flex flex-wrap justify-content-between align-items-center">
        <h2 class="h3 py-2 mx-2 text-center text-sm-left">{{ __('Donations received') }}</h2>
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
                    data-content="{{__('This is the supporter\'s email ')}}">{{__('Email*')}}</th>

                <th data-container="body" 
                    data-toggle="popover" 
                    data-placement="top" 
                    data-trigger="hover" 
                    data-content="{{__('This is the donation amount).')}}">
                    {{__('Amount')}}</th>

                <th data-container="body" 
                    data-toggle="popover" 
                    data-placement="top" 
                    data-trigger="hover" 
                    data-content="{{__('These are the total fees, sum of fees paid to Stripe (which provides the payment platform) and the fees paid to LiUU for the use of the crowdliquidity platform. You will have received on your Stripe account only the amount net of these fees.')}}">
                    {{__('Fee')}}*</th>

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
            </tr>
            </thead>
            <tbody>
            {{--todo pagination--}}
			<?php
			use App\Charge;
			use App\StripeTool;
			?>
            <?php $totalAmount=0; $totalFee =0; ?>
            @if(count($charges) > 0)
                @foreach ($charges as $charge)
                    <?php $totalAmount += $charge->amount / 100; $totalFee += $charge->amount_fee/100;?>
                    <tr>
                        <td>{{$charge['id']}}</td>
                        <td>{{$charge->user->name}}</td>
                        <td>{{$charge->user->email}}</td>
                        <td><?php echo number_format($charge['amount'] / 100, 2, '.', ''); ?></td>
                        <td><?php echo number_format($charge['amount_fee']/100, 2, '.', ''); ?></td>
                        <td>@dateJFY($charge['created_at'])</td>
                        <td><?php echo Charge::itemAlias(Charge::STATUSES, $charge['status']); ?></td>
                        <td><?php echo Charge::itemAlias(Charge::PAID, $charge['is_paid']); ?></td>
                    </tr>
                @endforeach
            @endif
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td>{{__('Totals:')}}</td>
                    <td>{{ number_format($totalAmount , 2, '.', '') }}</td>
                    <td>{{ number_format($totalFee, 2, '.', '') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
@endsection
@push('scripts')
@endpush