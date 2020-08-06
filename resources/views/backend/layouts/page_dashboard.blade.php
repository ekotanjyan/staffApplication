<!-- resources/views/layouts/app.blade.php -->
@extends('backend.layouts.master')

@prepend('styles')
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<!-- Styles -->

<link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/theme.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/general.css') }}?v=1" rel="stylesheet">

@endprepend

@push('styles')
<style>
.table th, .table td{
  vertical-align:middle
}
</style>
@endpush

@section('app')

  @include('frontend.partials.header')

  @include('shared.partials.toast')

  <div class="page-title-overlap bg-accent pt-4">
    <div class="container pt-2">
      <h2 class="text-dark mb-0">{{__('Dashboard')}}</h2>
    </div>
  </div>

  <div class="container mb-5 pb-3">
    <div class="bg-light box-shadow-lg rounded-lg overflow-hidden">
      <div class="row">
        @include('backend.partials.aside')

        <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
          <div class="pt-2 px-4 pl-lg-0 pr-xl-5">

            @if ($errors->any())
            <div class="alert alert-danger">
              <i class="czi-announcement mr-2"></i> {{ __('Whoops! There are some errors.') }}
              @error("translations")
              <br />
              <i class="czi-announcement mr-2"></i> {!! $message !!}
              @enderror
            </div>
            @endif

            <!-- dashboard page content -->
            @yield('content')
          </div>
        </section>
      </div> <!-- /container -->
    </div>
  </div>

  @include('shared.partials.footer', ['includeSubscribeForm' => false ])

@endsection

@prepend('scripts')
  <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>-->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script src="{{ asset('js/custom.js') }}"></script>
  <script src="{{ asset('js/cart.js') }}?v=4"></script>

  <!--
  <script src="{{ asset('js/app.js') }}" defer></script>
  -->
  <script>
  (function($){
  $("#start_date").datepicker({
    dateFormat: 'yy-mm-dd',
          showAnim: "slideDown",
          altFormat: "yyyy-mm-dd",
          minDate: '+0d'
      });

      $( "#end_date" ).datepicker({
        dateFormat: 'yy-mm-dd',
          showAnim: "slideDown",
          altFormat: "yyyy-mm-dd",
          minDate: '+1d'
      });

      $('[data-toggle="popover"]').popover();
      $('.dropdown-toggle').dropdown();


      var isOnIOS = navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i);
      var eventName = isOnIOS ? 'pagehide' : 'beforeunload';

      //prompt user to save the form before leaving
      $('form.alert_before_leave').on('change keyup paste', ':input', function(e) {
        console.log(eventName);
        $(window).bind(eventName, function(){ return false; });
      });

      //unbind prompt if form is submitted
      $('form.alert_before_leave').submit(function() {
         $(window).unbind(eventName);
      });
  })(jQuery);
  </script>
@endprepend
