<!-- resources/views/layouts/app.blade.php -->
@extends('frontend.layouts.master')


@section('app')

  @if( $includeHeader )
    @include('frontend.partials.header')
  @endif

  @include('shared.partials.toast')

  <div class="page-title-overlap bg-accent pt-4">
      <div class="container d-lg-flex justify-content-between pt-2">
          <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
              @yield('breadcrumbs')
          </div>
          <div class="order-lg-1 text-sm-left">
              <h3 class="text-dark">@isset($deleted) [{{$business ?? ''}}] - {{ $deleted }} @else {{$business ?? ''}} @endisset</h3>
              @isset($member_since) <div class="text-dark font-size-base">{{__('Member since')}} {{$member_since}}</div> @endisset
          </div>
      </div>
  </div>

  <div class="container mb-5 pb-3">
    <div class="bg-light box-shadow-lg rounded-lg overflow-hidden">
      @yield('content')
    </div>
  </div>

  @if( $includeFooter )
    @include('shared.partials.footer')
  @endif

@endsection

@prepend('scripts')
  <script src="{{ asset('js/vendor.min.js') }}"></script>
  <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>-->
  <!--<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>-->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

  <!--
  <script src="{{ asset('js/app.js') }}" defer></script>
  -->
  <script src="{{ asset('js/theme.min.js') }}"></script>
  <script src="{{ asset('js/custom.js') }}"></script>
  <script src="{{ asset('js/cart.js?v=4') }}"></script>
@endprepend
