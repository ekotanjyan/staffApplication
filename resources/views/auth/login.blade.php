@extends('frontend.layouts.master', [
  'includeHeader' => false,
  'includeFooter' => false
])

@section('title', __('Sign in') )

@push('styles')
<link href="{{ asset('bs/sign-in/signin.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('app')
<div class="container">
  <div class="row vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 box-shadow">
        <div class="card-body">
            <h2 class="h4 py-2">{{__('Sign in')}}

              <div class="navbar-toolbar d-flex align-items-center order-lg-3 float-right">
                @include('frontend.partials.language_switcher_new')
              </div>
            </h2>
            <form method="POST" action="{{ route('login') }}">
              @csrf
                <div class="input-group-overlay @if (!$errors->has('email')) form-group @endif">
                  <div class="input-group-prepend-overlay"><span class="input-group-text"><i class="czi-mail"></i></span></div>
                    <input id="inputEmail" type="email" class="form-control prepended-form-control @error('email') is-invalid @enderror"
                      name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" autofocus required>
                </div>
                @error('email')
                <div class="text-danger" role="alert">
                    {{ $message }}
                </div>
                @enderror
                <div class="input-group-overlay form-group">
                  <div class="input-group-prepend-overlay"><span class="input-group-text"><i class="czi-locked"></i></span></div>
                  <div class="password-toggle">
                    <input id="inputPassword"
                        type="password"
                        class="form-control prepended-form-control"
                        name="password"
                        placeholder="{{ __('Password') }}"
                        required
                        autocomplete="current-password"
                      />
                    <label class="password-toggle-btn">
                      <input class="custom-control-input" type="checkbox"><i class="czi-eye password-toggle-indicator"></i><span class="sr-only">{{__('Show password')}}</span>
                    </label>
                  </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="remember_me">{{ __('Remember Me') }}</label>
                  </div>
                  @if (Route::has('password.request'))
                      <a href="{{ route('password.request') }}" class="nav-link-inline">
                          <small>{{ __('Forgot password?') }}</small>
                      </a>
                  @endif
                </div>
                <hr class="mt-3">
                <div class="float-left pt-4">
                  <a href="{{ route('register') }}" class="btn btn-secondary">
                  <i class="czi-user mr-2 ml-n1"></i>{{ __('No account? Sign up') }}</a>
                </div>
                <div class="text-right pt-4 float-right">
                  <button class="btn btn-primary" type="submit"><i class="czi-sign-in mr-2 ml-n21"></i>{{ __('Sign In') }}</button>
                </div>
            </form>
        </div>
        </div>
    </div>
  </div>
</div>
@endsection
