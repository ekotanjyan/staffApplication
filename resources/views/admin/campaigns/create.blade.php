@extends('backend.layouts.page_dashboard')

@section('title', __('Create campaign') )

@push('styles')
@endpush

@section('content')

  <div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
    <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{ __('Create new campaign') }}</h2>
    <div class="py-2 text-nowrap">
      <a tabindex="-1" href="javascript:void(0)" class="text-dark" data-container="body" data-toggle="popover" data-placement="top" title="{{ __('Campaign language') }}" data-content="{{ __('Select here in which language you want to create your campaign. You can also translate your campaign and products in multiple languages by selecting the desired languages here and compiling all the fields below accordingly.') }}" data-trigger="hover"><span class="czi-globe mr-1"></span></a>

      @include('backend.partials.form_locale_selector')
    </div>
  </div>

  <form method="post" id="locale-form-wrapper" action="{{ route('campaign.store') }}" autocomplete="off" enctype="multipart/form-data" class="alert_before_leave">
    @csrf
    @include('admin.campaigns.partials.form',['campaign'=> $campaign])

    <button class="btn btn-success btn-lg btn-block" type="submit">{{ __('Create your campaign') }}</button>
  </form>

@endsection
