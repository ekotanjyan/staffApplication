@extends('backend.layouts.page_dashboard')

@section('title', __('Campaigns') )

@push('styles')

<!-- Vendor Styles including: Font Icons, Plugins, etc.-->
<link rel="stylesheet" media="screen" href="{{ asset('css/vendor.min.css') }}">
<!-- Main Theme Styles + Bootstrap-->
<link rel="stylesheet" media="screen" id="main-styles" href="{{ asset('css/theme.min.css') }}">

@endpush

@section('content')
  @if( $campaigns->isEmpty() )
    <div class="row d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
      <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{ __('Your Campaigns') }}
      <span class="badge badge-secondary font-size-sm text-body align-middle ml-2">{{$campaigns->count()}}</span></h2>
    </div>

    <div class="alert alert-info mt-4">
      {{ __('There are no campaigns yet') }}. <a href="{{ route('campaign.create') }}">{{ __('Create one now') }}</a>.
    </div>
  @else

  <div class="row d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
    <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{ __('Campaigns') }}
    <span class="badge badge-secondary font-size-sm text-body align-middle ml-2">{{$campaigns->total()}}</span></h2>

    <button type="button" class="btn btn-success float-right">
      <a href="{{ route('campaign.create') }}" class="text-white">{{__('Create new campaign')}}</a>
    </button>
  </div>

  {{-- $campaigns->links('vendor.pagination.campaigns-header') --}}

   <!-- Campaigns grid-->

   <div class="row pt-3 mx-n2">
       @foreach( $campaigns as $campaign )
       <!-- Product-->
       <div class="col-lg-4 col-md-4 col-sm-6 px-2 mb-grid-gutter">
         <div class="card product-card-alt">
           <div class="product-thumb">
             <div class="product-card-actions">
               <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" target="_blank" href="{{ route('campaigns.show', $campaign->id) }}"><i class="czi-eye"></i></a>
               <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ route('campaign.edit', $campaign->id) }}"><i class="czi-edit text-success"></i></a>
             </div>
             <a class="product-thumb-overlay" target="_blank" href="{{ route('campaigns.show', $campaign->id) }}"></a>

             <img src="{{Thumbnail::thumb('campaign/'.$campaign->image, 300, 225 )}}" />
            <a class="btn btn-secondary btn-icon float-right d-lg-none position-absolute" style="z-index: 2; top: 0; right: 0;" role="button" href="{{ route('campaign.edit', $campaign->id) }}">
             <i class="czi-edit text-info"></i>
            </a>
           </div>

           <div class="card-body">
             <div class="d-flex flex-wrap justify-content-between align-items-start pb-2">
               <div class="text-muted font-size-s mr-1"><a class="product-meta font-weight-medium" target="_blank" href="{{ route('campaigns.show', $campaign->id) }}">{{ $campaign->name }}</a></div>

             </div>
             <h3 class="product-title font-size-sm mb-2"><a href="{{ route('business.show', $campaign->business->id) }}">By {{ $campaign->business->name }}</a></h3>
             <div class="d-flex flex-wrap justify-content-between align-items-center">
                 <div class="font-size-sm mr-2"><i class="czi-money-bag text-muted mr-1"></i>@currency {{$campaign->raised()}} {{__('of')}} @currency {{$campaign->formattedTarget}} {{__('raised')}}</div>
             </div>
           </div>
         </div>
       </div>
       @endforeach
   </div>

   <!-- Pagination-->
   {{ $campaigns->links('vendor.pagination.campaigns-footer') }}

  @endif

@endsection

@push('scripts')
<!-- JavaScript libraries, plugins and custom scripts-->
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('js/theme.min.js') }}"></script>
@endpush
