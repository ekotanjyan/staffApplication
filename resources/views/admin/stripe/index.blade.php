@extends('backend.layouts.page_dashboard')
@section('title', __('Stripe Connect') )
@push('styles')
@endpush
@section('content')

    <div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
      <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{ __('Stripe') }}
      <span class="badge badge-secondary font-size-sm text-body align-middle ml-2"></span></h2>
      <div class="py-2">
      </div>
    </div>

    @if($user->isConnected())
        <h5 class="nav-link-style d-flex align-items-center px-4 py-3">{{__('Your stripe account is connected!')}}</h5>
        <a href="{{ route('stripe.disconnect') }}" class="btn btn-primary" onclick="return confirm('{{__('Are you sure you want to disconnect your stripe account ?')}}');">{{ __('Disconnect') }}</a>
    @else
        <a class="btn btn-accent mt-4" href="https://connect.stripe.com/oauth/authorize?client_id={{ $client_id }}&state={{ $state }}&scope=read_write&response_type=code&stripe_user[email]={{$user['email']}}&redirect_uri={{$redirect_uri}}" class="nav-link-style d-flex align-items-center px-4 py-3">{{__('Connect your stripe account')}}</a>
    @endif

@endsection
@push('scripts')
@endpush