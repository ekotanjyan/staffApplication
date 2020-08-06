@extends('backend.layouts.page_dashboard')

@section('title', __('Businesses') )
@push('styles')
@endpush
@section('content')
    <div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
        <h2 class="h3 py-2 mx-2 text-center text-sm-left">{{ __('Order') }}</h2>
        <div class="py-2">
        </div>
    </div>
    <div class="m-2">
        <div class="box-body">
            <div>
                <div id="bc_w0" class="box box-primary"><div class="box-header">
                        <h3 class="box-title"><i class="fa fa-eys"></i> Charge Id: {{$charge['id']}} </h3>
                    </div>
                    <div class="box-body">
                        <table id="w1" class="table table-striped table-bordered detail-view">
                            <tbody>
                            <?php use App\Charge; ?>
                            <tr>
                                <th>ID</th>
                                <td>{{ $charge['id'] }}</td>
                            </tr>                            <tr>
                                <th>Stripe Charge Id</th>
                                <td>{{ $charge['stripe_charge_id'] }}</td>
                            </tr>                            <tr>
                                <th>Stripe Refund Id</th>
                                <td>{{ $charge['stripe_refund_id'] }}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>{{ $charge['user']['name'] }}</td>
                            </tr>
                            <tr>
                                <th>Seller</th>
                                <td>{{ $charge['seller']['name'] }}</td>
                            </tr>
                            <tr>
                                <th>Order Id</th>
                                <td>{{ $charge['order_id'] }}</td>
                            </tr>

                            <tr>
                                <th>Is Paid</th>
                                <td><?php echo Charge::itemAlias(Charge::PAID, $charge['is_paid']); ?></td>
                            </tr>

                            <tr>
                                <th>Is Refunded</th>
                                <td><?php echo Charge::itemAlias(Charge::REFUNDED, $charge['is_refunded']); ?></td>
                            </tr>


                            <tr>
                                <th>Status</th>
                                <td><?php echo Charge::itemAlias(Charge::STATUSES, $charge['status']); ?></td>
                            </tr>


                            <tr>
                                <th>Amount</th>
                                <td>{{ $charge['amount'] / 100 }}</td>
                            </tr>

                            <tr>
                                <th>Amount Fee</th>
                                <td>{{ $charge['amount_fee'] / 100 }}</td>
                            </tr>

                            <tr>
                                <th>Amount Refund</th>
                                <td>{{ $charge['amount_refund'] / 100 }}</td>
                            </tr>

                            <tr>
                                <th>Currency</th>
                                <td>{{ $charge['currency']['name'] }}</td>
                            </tr>

                            <tr>
                                <th>Created at</th>
                                <td>{{ $charge['created_at'] }}</td>
                            </tr>
                            <tr>
                                <th>Failed at</th>
                                <td>{{ $charge['failed_at'] }}</td>
                            </tr>

                            <tr>
                                <th>Charged at</th>
                                <td>{{ $charge['charged_at'] }}</td>
                            </tr>

                            <tr>
                                <th>Refunded at</th>
                                <td>{{ $charge['refunded_at'] }}</td>
                            </tr>

                            <tr>
                                <th>Updated at</th>
                                <td>{{ $charge['updated_at'] }}</td>
                            </tr>
                            <tr>
                                <th>Actions</th>
                                <td>
                                    <?php if (Charge::refundAllowed($charge) && $charge->seller->id == auth()->user()->id) { ?>
                                    <form method="post" action="{{route('stripe.view', ['id' => $charge['id']])}}">
                                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                        <div class="form-group field-charge-amount">
                                            <label class="control-label" for="user-name">{{__('Amount Refund in')}} <b>cents</b></label>
                                            <input type="text" id="charge-amount" class="form-control @if($errors->has('amount')) has-error @endif" name="amount" value="{{ old('amount') ? old('amount') : '' }}" maxlength="7" required>
                                            <div class="help-block">@if ($errors->has('amount')){{ $errors->first('amount') }} @endif</div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">{{__('Refund')}}</button>
                                        </div>
                                    </form>
                                    <?php } ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

@endpush