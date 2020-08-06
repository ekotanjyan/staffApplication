@extends('backend.layouts.page_dashboard')

@section('title', __('Create product') )

@push('styles')
@endpush

@section('content')

  <div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
    <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{ __('Create new product') }}</h2>
    <div class="py-2 text-nowrap">
      <a tabindex="-1" href="javascript:void(0)" class="text-dark" data-container="body" data-toggle="popover" data-placement="top" title="{{ __('Product language') }}" data-content="{{ __('Select here in which language you want to create your product. You can also translate your campaign and products in multiple languages by selecting the desired languages here and compiling all the fields below accordingly.') }}" data-trigger="hover"><span class="czi-globe mr-1"></span></a>

      @include('backend.partials.form_locale_selector')
    </div>
  </div>

  <form method="post" id="locale-form-wrapper" action="{{ route('product.store') }}" autocomplete="off" enctype="multipart/form-data" class="alert_before_leave">
    @csrf
    @include('admin.products.partials.form',['product'=> $product])
    <input type="hidden" name="campaign" value="{{$campaign}}">
    <button class="btn btn-info btn-lg btn-block" type="submit">{{ __('Create your product') }}</button>
  </form>

@endsection

@push('scripts')
  @if ( session('toast') )
  <script>
  $('.toast').toast('show');
  </script>
  @endif
@endpush
