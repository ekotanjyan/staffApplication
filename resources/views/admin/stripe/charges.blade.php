@extends('backend.layouts.page_dashboard')

@section('title', __('Businesses') )
@push('styles')
@endpush
@section('content')
    <div class="d-sm-flex flex-wrap justify-content-between align-items-center">
        <h2 class="h3 py-2 mx-2 text-center text-sm-left">{{ __('My purchases') }}</h2>
        <div class="py-2">
        </div>
    </div>
    <div class="mt-2 mb-2 ml-0 mr-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th data-container="body"
                        data-toggle="popover"
                        data-placement="top"
                        data-trigger="hover"
                        data-content="{{__('This is the order ID of the transaction.')}}">{{__('ID')}}*
                    </th>

                    <th data-container="body"
                        data-toggle="popover"
                        data-placement="top"
                        data-trigger="hover"
                        data-content="{{__('This is the total price paid by the client (incl. fees paid to Stripe and for the use of the platform).')}}">
                        {{__('Amount')}}*
                    </th>

                    <th>{{__('Currency')}}</th>
                    <th>{{__('Seller')}}</th>

                    <th data-container="body"
                        data-toggle="popover"
                        data-placement="top"
                        data-trigger="hover"
                        data-content="{{__('If a user has initiated a transaction that was not completed, it will be listed here as failed.')}}">
                        {{__('Status')}}*
                    </th>

                    <th data-container="body"
                        data-toggle="popover"
                        data-placement="top"
                        data-trigger="hover"
                        data-content="{{__('It will indicate if a completed transaction has finally resulted in a payment.')}}">
                        {{__('Paid')}}*
                    </th>

                    <th>{{__('Date')}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                {{--todo pagination--}}
                <?php
                use App\Charge;
                use App\StripeTool;
                ?>
                @if(isset($charges))
                    @foreach ($charges as $charge)
                        <tr>
                            <td>{{$charge['order_id']}}</td>
                            <td><?php echo StripeTool::int2double($charge['amount']); ?></td>
                            <td>{{$charge['currency']['name']}}</td>
                            <td>{{$charge->seller->activeCampaign()->business->name ?? ''}}</td>
                            <td><?php echo Charge::itemAlias(Charge::STATUSES, $charge['status']); ?></td>
                            <td><?php echo Charge::itemAlias(Charge::PAID, $charge['is_paid']); ?></td>
                            <td>{{\Carbon\Carbon::parse($charge['created_at'])->format('j F, Y')}}</td>
                            @php //$redirect = ($charge->status == Charge::STATUS_PAID_COMPLETED && $charge->is_paid) ? route('order.view',$charge) : route('order.failed',$charge); @endphp
                            <td><a href="{{route('order.view',$charge->id)}}" target="_blank" class="btn btn-sm btn-success">{{__('View')}}</a></td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
