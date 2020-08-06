@extends('backend.layouts.page_dashboard')

@section('title', __('User Account') )


@section('content')
<section class="col-lg-10">

    <div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom mb-4">
        <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{__('Account')}}</h2>
    </div>

    <!-- Profile form-->
    <form action="{{route('user.store')}}" method="post" class="alert_before_leave">
        @csrf
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="account-fn">{{__('First Name')}}</label>
                    <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="account-fn" value="{{ old('name',$user->name) }}">
                    @error('name')
                    <span class="invalid-feedback my-1" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="account-ln">{{__('Last Name')}}</label>
                    <input class="form-control @error('last_name') is-invalid @enderror" name="last_name" type="text" id="account-ln" value="{{ old('last_name',$user->last_name) }}">
                    @error('last_name')
                    <span class="invalid-feedback my-1" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="account-email">{{__('Email')}}</label>
                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" id="account-email" value="{{ old('email',$user->email) }}" disabled>
                    @error('email')
                    <span class="invalid-feedback my-1" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
            <div class="form-group">
                <label for="account-pass">{{__('New Password')}}</label>
                <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" id="account-pass">
                @error('password')
                <span class="invalid-feedback my-1" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            </div>
            <div class="col-sm-6">
            <div class="form-group">
                <label for="account-confirm-pass">{{__('Confirm Password')}}</label>
                <input class="form-control" type="password" name="password_confirmation" id="account-confirm-pass">
            </div>
            </div>
            <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <button class="btn btn-success mt-3 mt-sm-0" type="submit">{{__('Update')}}</button>
            </div>
            </div>
        </div>
    </form>

</section>

@endsection
