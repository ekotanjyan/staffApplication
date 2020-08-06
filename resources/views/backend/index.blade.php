@extends('backend.layouts.app')

@section('title', 'Dashboard')

@push('styles')
@endpush

@section('content')
<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
  <div class="container">
    <h1 class="display-3">Hello, crowdliquidity!</h1>
    <p>This is the campaign center for crowdliquidity by LiUU</p>
    <p><a class="btn btn-primary btn-lg shadow-lg shadow-none outline-none" href="#" role="button">Learn more &raquo;</a></p>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-6">
      <h2>Support a campaign</h2>
      <p>Companies on LiUU need your support to guarantee their liquidity. You can benefit as a supporter too.</p>
      <p><a class="btn btn-sunflower shadow-none outline-none" href="/view-campaigns.php" role="button">View Campaigns &raquo;</a></p>
    </div>
    <div class="col-md-6">
      <h2>Open a campaign</h2>
      <p>Are you looking for a way to improve your current liquidity? Open a campaign today, low fees and a great opportunity.</p>
      <p><a class="btn btn-sunflower shadow-none outline-none" href="/campaign-open.php" role="button">Create a campaign &raquo;</a></p>
    </div>
  </div>
  <hr>
</div>
@endsection

@push('scripts')
@endpush
