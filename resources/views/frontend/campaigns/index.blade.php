@extends('frontend.layouts.app',[
'business'=> __('crowdliquidity Campaigns on LiUU.world')
])

@section('title', 'Campaigns')

@push('styles')
@endpush

@section('content')

<div class="d-flex align-items-center pl-2 box-shadow">
    <!-- Search-->
    <div class="input-group-overlay">
        <div class="input-group-prepend-overlay"><span class="input-group-text"><i class="czi-search"></i></span></div>
        <form method="get" action="{{route('campaigns.index')}}">
            @csrf
            <input class="form-control prepended-form-control border-0 box-shadow-0" name="string" id="search" type="text" placeholder="Search for a campaign...'">
        </form>
    </div>

    <!-- Pagination-->
    {{-- $campaigns->links('vendor.pagination.campaigns-header') --}}
    
</div>
<small class="form-text text-muted px-5 py-1 position-absolute" id="search-result"></small>


<div class="row p-5" id="campaign-cards">
    
    @include('frontend.partials.campaign-card')

</div>

{{-- $campaigns->links('vendor.pagination.bootstrap-4') --}}

@endsection

@push('scripts')
@endpush