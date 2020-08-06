<!-- Campaigns-->
@foreach($campaigns as $campaign)
<div class="col-lg-3 col-md-4 col-sm-6 px-2 mb-grid-gutter">
    <div class="card product-card-alt">
        <div class="product-thumb">
            <div class="product-card-actions"><a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{route('campaigns.show',$campaign->id)}}"><i class="czi-eye"></i></a></div>
            <a class="product-thumb-overlay" href="{{route('campaigns.show',$campaign->id)}}"></a>
            <img src="{{Thumbnail::thumb('campaign/'.$campaign->image,600,400)}}" alt="{{$campaign->title}}">
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start pb-2">
                <div class="text-muted font-size-s mr-1">
                    <a class="product-meta font-weight-medium" href="{{route('campaigns.show',$campaign->id)}}">{{__('by')}} {{$campaign->business->name}}</a>
                </div>
            </div>
            <h3 class="product-title font-size-sm mb-2"><a href="{{route('campaigns.show',$campaign->id)}}">{!! \Illuminate\Support\Str::of($campaign->name)->replaceMatches('/'.request('string').'/i', function ($match) { return '<b class="text-warning">'.$match[0].'</b>'; }) !!}</a></h3>
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="font-size-sm mr-2"><i class="czi-money-bag text-muted mr-1"></i>@currency {{$campaign->raised()}} {{__('of')}} @currency {{$campaign->formattedTarget}} {{__('raised')}}</div>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
$(document).ready(function(){
    $('#search-result').html("<strong>{{$campaigns->count()}}</strong> Results");
})
</script>
@endpush
