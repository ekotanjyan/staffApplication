@extends('backend.layouts.page_dashboard')

@section('title', __('Edit campaign') )

@push('styles')
<style>
/* right bounce */
@-webkit-keyframes bounceRight {
  0%,
  20%,
  50%,
  80%,
  100% {
    -webkit-transform: translateX(0);
    transform: translateX(0);
  }
  40% {
    -webkit-transform: translateX(-20px);
    transform: translateX(-20px);
  }
  60% {
    -webkit-transform: translateX(-5px);
    transform: translateX(-5px);
  }
}
@-moz-keyframes bounceRight {
  0%,
  20%,
  50%,
  80%,
  100% {
    transform: translateX(0);
  }
  40% {
    transform: translateX(-20px);
  }
  60% {
    transform: translateX(-5px);
  }
}
@keyframes bounceRight {
  0%,
  20%,
  50%,
  80%,
  100% {
    -ms-transform: translateX(0);
    transform: translateX(0);
  }
  40% {
    -ms-transform: translateX(-20px);
    transform: translateX(-20px);
  }
  60% {
    -ms-transform: translateX(-5px);
    transform: translateX(-5px);
  }
}
/* /right bounce */
.arrow-to-right {
  -webkit-animation: bounceRight 2s infinite;
  animation: bounceRight 2s infinite;
}
.hover-underline{
  text-decoration: none !important;
}
.hover-underline:hover{
  text-decoration: underline !important;
}
</style>
@endpush

@section('content')

  <div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
    <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{ __('Edit campaign') }}
    <small><span class="badge @if($campaign->status == 1) badge-success @else badge-secondary @endif ml-1 text-capitalize">{{ __($campaign->statusLabel) }}</span></small></h2>
    <div class="py-2 text-nowrap">
      <a tabindex="-1" href="javascript:void(0)" class="text-dark" data-container="body" data-toggle="popover" data-placement="top" title="{{ __('Campaign language') }}" data-content="{{ __('Select here in which language you want to create your campaign. You can also translate your campaign and products in multiple languages by selecting the desired languages here and compiling all the fields below accordingly.') }}" data-trigger="hover"><span class="czi-globe mr-1"></span></a>

      @include('backend.partials.form_locale_selector')
    </div>
  </div>

  <form method="post" id="locale-form-wrapper" action="{{ route('campaign.update', $campaign) }}" autocomplete="off" enctype="multipart/form-data" class="alert_before_leave">
    @csrf
    @method('PUT')

    @include('admin.campaigns.partials.form',[$campaign, $products])

    <button class="btn btn-success btn-lg float-right" type="submit">{{__('Update your campaign')}}</button>
  </form>

{{--  <form name="cancel" action="{{ route('campaign.destroy', $campaign) }}" method="post">--}}
{{--      @csrf--}}
{{--      @method('delete')--}}
{{--      <div class="btn-group dropup">--}}

{{--        <button type="button" class="btn btn-secondary btn-lg float-left">--}}
{{--            {{ __('Status') }}--}}
{{--        </button>--}}

{{--          <button type="button" class="btn btn-secondary btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--              <span class="sr-only">Toggle dropdown</span>--}}
{{--          </button>--}}
{{--          <div class="dropdown-menu">--}}
{{--              @if((int)$campaign->status !== 1) <a onclick="confirm('{{ __("Are you sure you want to request activation for this campaign?") }}') ? document.forms['activate'].submit() : ''" class="btn dropdown-item text-success">{{__('Active')}}</a> @endif--}}
{{--            <a onclick="confirm('{{ __("Are you sure you want to finish this campaign?") }}') ? document.forms['finish'].submit() : ''" class="btn dropdown-item">{{__('Finished')}}</a>--}}
{{--            <a onclick="confirm('{{ __("Are you sure you want to pause this campaign?") }}') ? document.forms['pause'].submit() : ''" class="btn dropdown-item">{{__('Pause')}}</a>--}}
{{--            <div class="dropdown-divider"></div>--}}
{{--            <a onclick="confirm('{{ __("Are you sure you want to cancel this campaign?") }}') ? document.forms['cancel'].submit() : ''" class="btn dropdown-item text-danger">{{__('Cancel')}}</a>--}}
{{--          </div>--}}
{{--      </div>--}}
{{--  </form>--}}
{{--  <form name="activate" action="{{ route('campaign.status', $campaign) }}" method="post">@csrf <input type="hidden" name="status" value="1" ></form>--}}
{{--  <form name="finish" action="{{ route('campaign.status', $campaign) }}" method="post">@csrf <input type="hidden" name="status" value="4" ></form>--}}
{{--  <form name="pause" action="{{ route('campaign.status', $campaign) }}" method="post">@csrf <input type="hidden" name="status" value="2" ></form>--}}
@endsection
