@extends('backend.layouts.page_dashboard')

@section('title', __('Edit product') )

@push('styles')
@endpush

@section('content')

<div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
  <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{ __('Edit product') }}</h2>
  <div class="py-2 text-nowrap">
    <a tabindex="-1" href="javascript:void(0)" class="text-dark" data-container="body" data-toggle="popover" data-placement="top" title="{{ __('Product language') }}" data-content="{{ __('Select here in which language you want to create your product. You can also translate your campaign and products in multiple languages by selecting the desired languages here and compiling all the fields below accordingly.') }}" data-trigger="hover"><span class="czi-globe mr-1"></span></a>

    @include('backend.partials.form_locale_selector')
  </div>
</div>

<form method="post" id="locale-form-wrapper" action="{{ route('product.update', $product) }}" autocomplete="off" enctype="multipart/form-data" class="alert_before_leave">
  @csrf
  @method('PUT')

  @include('admin.products.partials.form',['product'=> $product])

  <button class="btn btn-info btn-lg float-right" type="submit">{{ __('Update your product') }}</button>
</form>

<form action="{{ route('product.destroy', $product) }}" method="post">
    @csrf
    @method('delete')
    <button type="button" class="btn btn-secondary btn-lg float-left" onclick="confirm('{{ __("Are you sure you want to delete this product?") }}') ? this.parentElement.submit() : ''">
        {{ __('Delete') }}
    </button>
</form>

@endsection

@push('scripts')
@endpush
