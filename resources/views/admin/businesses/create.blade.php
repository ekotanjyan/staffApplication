@extends('backend.layouts.page_dashboard')

@section('title', __('Open business') )

@push('styles')
@endpush

@section('content')

    <div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
        <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{ __('Create new business') }}</h2>
    </div>

    <form method="post" action="{{ route('business.store') }}" autocomplete="off" enctype="multipart/form-data" class="alert_before_leave">
    @csrf

      @include('admin.businesses.partials.form',['business'=> $business])

      <div class="py-3">
          <div class="form-check">
              <input type="checkbox" class="form-check-input @error('accept_terms') is-invalid @enderror" id="terms"
              {{ (old('accept_terms', false) ? 'checked':'') }}
               name="accept_terms" value="1">
              <label class="form-check-label" for="terms">{{__('Yes, I agree to the')}} <a href="{{ config('custom.pages.terms_business.'.app()->getLocale(), config('custom.pages.terms_business.en')) }}" target="_blank">{{__('terms and condtions')}}</a></label>

                @error('accept_terms')
                <span class="invalid-feedback my-1" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
          </div>
      </div>

      <button class="btn btn-primary btn-lg btn-block" type="submit">{{__('Register your business')}}</button>

    </form>
@endsection

@push('scripts')
@endpush
