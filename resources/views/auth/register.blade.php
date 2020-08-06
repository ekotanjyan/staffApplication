@extends('frontend.layouts.master', [
  'includeHeader' => false,
  'includeFooter' => false
])

@section('title', __('Sign up') )

@section('app')
<div class="container">
    <div class="row vh-100 d-flex align-items-center justify-content-center">


    <div class="col-md-8 p-4 box-shadow">
          <h2 class="h4 mb-3">{{__('Sign up')}}

            <div class="navbar-toolbar d-flex align-items-center order-lg-3 float-right">
              @include('frontend.partials.language_switcher_new')
            </div>
          </h2>
          <p class="font-size-sm text-muted mb-4">{{__('Registration takes less than a minute but gives you full control over your orders.')}}</p>
          <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">{{__('First Name')}}</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                    <label for="last_name">{{__('Surname')}}</label>
                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                    <label for="email">{{__('E-mail Address')}}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                    <label for="password">{{__('Password')}}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="password-confirm">{{__('Confirm Password')}}</label>
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                @error('g-recaptcha-response')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="d-flex flex-wrap justify-content-between">
                  <div class="custom-control custom-checkbox @error('terms') is-invalid @enderror">
                    <input class="custom-control-input" type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="terms">
                      {!! __('I accept the :terms_html_link.', [
                      'terms_html_link' => '<a target="_blank" href=":terms_url">'. __('terms and conditions') .'</a>',
                      'terms_url' => config('custom.pages.terms.'.app()->getLocale(), config('custom.pages.terms.en')),
                      ]) !!}
                    </label>
                  </div>
                  @error('terms')
                  <span class="invalid-feedback my-1" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12 text-right align-self-center">
                <button class="btn btn-primary" type="submit"><i class="czi-user mr-2 ml-n1"></i>{{__('Sign Up')}}</button>
              </div>
            </div>
          </form>
        </div>


    </div>
</div>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
