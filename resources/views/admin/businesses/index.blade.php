@extends('backend.layouts.page_dashboard')

@section('title', __('Businesses') )

@push('styles')
@endpush

@section('content')

    <div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
      <h2 class="h3 py-2 mr-2 text-center text-sm-left">{{ __('Your Businesses') }}
      <span class="badge badge-secondary font-size-sm text-body align-middle ml-2">{{$businesses->count()}}</span></h2>
    </div>

    @if( $businesses->isEmpty() )
      <div class="alert alert-info mt-4">
        {{ __('There are no businesses yet') }}. <a href="{{ route('business.create') }}">{{ __('Create one now') }}</a>.
      </div>
    @else

    <ul class="list-group list-group-flush pb-4">
      @foreach($businesses as $business)
        <li class="list-group-item">
          <a href="{{ route('business.edit',$business->id) }}"> {{$business->name}} </a>
            <a class="nav-link-style mr-2 float-right" href="{{ route('business.edit',$business->id) }}" data-toggle="tooltip" title="" data-original-title="Edit"><i class="czi-edit"></i></a>
        </li>
      @endforeach
    </ul>

  @endif

  <div class="text-sm-right">
    @if( $businesses->count() )
    <a class="btn btn-success mr-3" href="{{ route('campaign.create') }}">{{__('Add new campaign')}}</a>
    @endif
    <a class="btn btn-primary" href="{{ route('business.create') }}">{{__('Add new business')}}</a>
  </div>
@endsection

@push('scripts')
@endpush
