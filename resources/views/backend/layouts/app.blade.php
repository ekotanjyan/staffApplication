<!-- resources/views/layouts/app.blade.php -->
@extends('backend.layouts.master')

@prepend('styles')
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<!-- Styles -->

<link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/theme.min.css') }}" rel="stylesheet">

@endprepend

@section('app')

  @if( $includeHeader )
    @include('frontend.partials.header')
  @endif

  @yield('content')

  @if( $includeFooter )
    @include('shared.partials.footer')
  @endif

@endsection

@prepend('scripts')
  <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>-->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

  <!--
  <script src="{{ asset('js/app.js') }}" defer></script>
  -->
  <script>
  jQuery(document).ready(function($){

  });
  </script>
@endprepend
