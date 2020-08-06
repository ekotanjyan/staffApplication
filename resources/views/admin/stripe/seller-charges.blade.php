@extends('backend.layouts.page_dashboard')

@section('title', __('Businesses') )
@push('styles')
@endpush
@section('content')
    <div class="d-sm-flex flex-wrap justify-content-between align-items-center">
        <h2 class="h3 py-2 mx-2 text-center text-sm-left">{{ __('Orders') }}</h2>
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
                    data-content="{{__('This is the unique ID of the transaction.')}}">ID*</th>

                <th data-container="body" 
                    data-toggle="popover" 
                    data-placement="top" 
                    data-trigger="hover" 
                    data-content="{{__('This is the total price paid by the client (incl. fees paid to Stripe and for the use of the platform).')}}">
                    {{__('Amount')}}*</th>

                <th data-container="body" 
                    data-toggle="popover" 
                    data-placement="top" 
                    data-trigger="hover" 
                    data-content="{{__('These are the total fees, sum of fees paid to Stripe (which provides the payment platform) and the fees paid to LiUU for the use of the crowdliquidity platform. You will have received on your Stripe account only the amount net of these fees.')}}">
                    {{__('Fee')}}*</th>

                <th>{{__('Currency')}}</th>
                <th>{{__('Date')}}</th>
                <th>{{__('Order')}}</th>
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
                <th></th>
            </tr>
            </thead>
            <tbody>
            {{--todo pagination--}}
			<?php
			use App\Charge;
			use App\StripeTool;
			$totalAmount = 0;
			$totalFee = 0;
			?>
            @if(isset($charges))
                @foreach ($charges as $charge)
                    <?php
                        $totalAmount+= $charge['amount'];
                    $totalFee+= $charge['amount_fee'];
                    ?>
                    <tr>
                        <td>{{$charge['id']}}</td>
                        <td><?php echo StripeTool::int2double($charge['amount']); ?></td>
                        <td><?php echo StripeTool::int2double($charge['amount_fee']); ?></td>
                        <td>{{$charge['currency']['name']}}</td>
                        <td>@dateJFY($charge['created_at'])</td>
                        <td>{{$charge['order_id']}}</td>
                        <td><?php echo Charge::itemAlias(Charge::STATUSES, $charge['status']); ?></td>
                        <td><?php echo Charge::itemAlias(Charge::PAID, $charge['is_paid']); ?></td>
                        <td><a href="#colps{{$loop->iteration}}" class="btn btn-secondary btn-sm" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample"><i class="czi-arrow-down"></i></a></td>
                        <td><a href="{{route('stripe.view',$charge->id)}}" class="btn btn-success btn-sm">{{__('View')}}</a></td>
                    </tr>
                    <tr>
                    <!-- Collapse -->
                    <td colspan="10" class="p-0">
                        <div class="collapse" id="colps{{$loop->iteration}}">

                            <table class="table table-hover mb-0 border">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Quantity')}}</th>
                                        <th>{{__('Qr ID')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($charge->suborders as $suborder)
                                    <tr>
                                        <td class="py-3">{{$suborder->allproduct->title}}
                                            @if($suborder->allproduct->deleted_at) <br><span class="badge badge-danger">{{__('Deleted')}}</span> @endif
                                        </td>
                                        <td class="py-3">{{$suborder->quantity}}</td>
                                        <td class="p-0"><table class="w-100 border-left">
                                        @foreach($suborder->qrcodes as $qr)
                                            <tr>
                                                <td>{{$qr->id}}</td>
                                                <td>@if($qr->price == $qr->used) {{__('Used')}} 
                                                    @elseif($qr->used) {{__('Partially used')}}<br>
                                                    {{__('Left to use:')}} <b>{{$qr->amountLeft()}}</b> 
                                                    @else {{__('Not used')}} @endif</td>
                                            </tr>
                                        @endforeach
                                        </table></td>
                                    </tr>
                                @endforeach    
                                </tbody>
                            </table>


                        </div>
                    </td>
                    </tr>
                @endforeach
                <tr>
                    <td>{{__('Totals:')}}</td>
                    <td>{{StripeTool::int2double($totalAmount)}}</td>
                    <td>{{StripeTool::int2double($totalFee)}}</td>
                    <td colspan="7"></td>

                </tr>
            @endif
            </tbody>
        </table>
@endsection
@push('scripts')
@endpush