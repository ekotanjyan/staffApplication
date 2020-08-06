<!-- resources/views/layouts/master.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <link rel='dns-prefetch' href='//fonts.googleapis.com' />
    <link rel='dns-prefetch' href='//cdnjs.cloudflare.com' />
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon/favicon.ico') }}" />
    <link href="{{ asset('images/favicon/apple-icon-60x60.png') }}" rel="apple-touch-icon-precomposed" sizes="60x60" />

    @stack('meta')

    <title>{{ config('app.name', 'LiUU') }} - @yield('title')</title>

    @stack('styles')
  </head>
  <body class="frontend">

    @yield('app')

    @stack('scripts')
  </body>
</html>
