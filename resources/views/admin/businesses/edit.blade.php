@extends('backend.layouts.page_dashboard')

@section('title', __('Edit business') )

@push('styles')
@endpush

@section('content')
  <div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
    <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{ __('Edit business') }}</h2>
  </div>

  <form method="post" id="business-form" action="{{ route('business.update',$business) }}" autocomplete="off" enctype="multipart/form-data" class="alert_before_leave">
    @csrf
    @method('PUT')

    @include('admin.businesses.partials.form',['business'=> $business])

    <button class="btn btn-primary btn-lg float-right button-mobile" type="submit">{{__('Update your business')}}</button>
  </form>

  <form action="{{ route('business.destroy', $business) }}" method="post">
      @csrf
      @method('delete')
      <button type="button" class="btn button-mobile btn-secondary btn-lg float-left" onclick="confirm('{{ __("Are you sure you want to delete this business along with related campaigns and products? ") }}') ? this.parentElement.submit() : ''">
          {{ __('Delete') }}
      </button>
  </form>

@endsection

@push('scripts')
@endpush
