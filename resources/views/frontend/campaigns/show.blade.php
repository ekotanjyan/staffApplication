@extends('frontend.layouts.app',[
'business'=>$campaign->business->name,
'member_since'=>$campaign->business->created_at->format('d F, yy'),
'deleted'=>$campaign->trashed() ? __('This campaign has been deleted') : null
])

@section('title', $campaign->name . ' | ' . $campaign->business->name)

@push('styles')
<style>
@media (max-width: 767px) {
  .btn.js-donate {
    display: block;
    width: 100%;
    margin: 20px 0px;
  }
}
.page-title-overlap+* {
    z-index: auto;
}
</style>
@endpush

@push('meta')
  <meta name="robots" content="index, follow"/>
  <meta name="locale" content="{{ supportedLocales()[app()->getLocale()]['regional'] }}"/>
  @if( Storage::disk('public')->exists('campaign/'.$campaign->image) )
  <meta name="image" content="{{Storage::url('campaign/'.$campaign->image)}}" />
  @endif
  <meta name="description" content="{{ strip_tags($campaign->description) }}"/>
  <meta name="x-default" content="{{ LaravelLocalization::getLocalizedURL( env('LOCALE'), null, [], true) }}"/>
  <link rel="canonical"  href="{{ LaravelLocalization::getLocalizedURL( env('LOCALE'), null, [], true) }}" />

  @foreach( supportedLocales() as $localeCode => $properties)
    @if( $campaign->hasTranslation($localeCode) )
    <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" />
    @endif
  @endforeach

  <meta itemprop="name" content="{{ $campaign->name }} | {{ config('app.name') }}"/>

  @if( Storage::disk('public')->exists('campaign/'.$campaign->image) )
  <meta itemprop="image" content="{{Storage::url('campaign/'.$campaign->image)}}" />
  @endif
@endpush

@section('breadcrumbs')
    @include('frontend.partials.breadcrumbs', ['breadcrumbs' => [
        $campaign->name,
    ]])
@endsection

@section('content')
<div class="row">
  <!-- Sidebar-->
  <aside class="col-lg-4 pb-3">
    <div class="cz-sidebar-static border-right">
      <h2 class="h3 pt-2 pb-4 mb-4 text-center text-sm-left border-bottom">{{__('About')}}</h2>
      <p>{{$campaign->business->name}}<br><small>{{$campaign->business->address}}, {{$campaign->business->city}} ({{$campaign->business->zip}}), {{$campaign->business->country}}</small></p>
      <img src="{{ Thumbnail::thumb('campaign/'.$campaign->image, 300, 225, 'background') }}" class="rounded-lg" alt="">

      <div id="campaign_description">
        <p class="collapse" id="text_crop" aria-expanded="false">
          {{$campaign->description}}
        </p>
        <a role="button" class="collapsed" data-toggle="collapse" href="#text_crop" aria-expanded="false" aria-controls="text_crop"></a>
      </div>

        @if($campaign->business->website)
        <ul class="list-unstyled font-size-sm card-text">
            <li><a class="nav-link-style d-flex align-items-center" target="_blank" href="{{$campaign->business->website}}">
                    <i class="czi-globe opacity-60 mr-2"></i>
                    {{ $campaign->business->website }}
                </a>
            </li>
        </ul>@endif

        @if($campaign->business->facebook)
        <a class="social-btn sb-facebook sb-outline sb-sm mr-2 mb-2" target="_blank" href="{{$campaign->business->facebook}}">
        <i class="czi-facebook"></i></a>@endif

        @if($campaign->business->twitter)
        <a class="social-btn sb-twitter sb-outline sb-sm mr-2 mb-2" target="_blank" href="{{$campaign->business->twitter}}">
        <i class="czi-twitter"></i></a>@endif

        @if($campaign->business->instagram)
        <a class="social-btn sb-instagram sb-outline sb-sm mr-2 mb-2" target="_blank" href="{{$campaign->business->instagram}}">
        <i class="czi-instagram"></i></a>@endif

      </div>
    </aside>
  <!-- End Sidebar -->

  <!-- Content-->
  <section class="col-lg-8 pt-lg-4 pb-md-4">

    <div class="pt-2 px-4 pl-lg-0 pr-xl-5">
      <h3>{{$campaign->name}}
            @if($campaign->user->isConnected() && !$campaign->trashed())
                <button class="btn btn-success btn-sm js-donate float-right"><i class="czi-heart-circle mr-2"></i>{{__('Donate')}}</button>
            @endif
      </h3>
      @currency {{ $campaign->raised() }} {{__('of')}} @currency {{ $campaign->formattedTarget }} {{__('raised')}}
      @php $percent = ( (int)str_replace(' ', '', str_replace("'", '', $campaign->raised())) / (int)$campaign->target ) * 100; @endphp
      <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-accent" role="progressbar"
          style="width: {{$percent}}%"
          aria-valuenow="{{$percent}}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>

      <div class="pt-2 px-4 pl-lg-0 pr-xl-5">
          @if($campaign->trashed())
              <div class="alert alert-danger alert-with-icon alert-dismissible fade show" role="alert">
                  <div class="alert-icon-box">
                      <i class="alert-icon czi-check-circle"></i>
                  </div>
                  {!! __('This campaign is deleted and is not accepting new orders. You can explore other campaigns by clicking :link', array('link'=>'<a href="'.route("campaigns.index").'">'.__("here").'</a>')) !!}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
          @else
              @switch((int)$campaign->status)
                  @case(4)
                  <div class="alert alert-success alert-with-icon alert-dismissible fade show" role="alert">
                      <div class="alert-icon-box">
                          <i class="alert-icon czi-check-circle"></i>
                      </div>
                      {!! __('This campaign is finished and is not accepting new orders. You can explore other campaigns by clicking :link', array('link'=>'<a href="'.route("campaigns.index").'">'.__("here").'</a>')) !!}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  @break
                  @case(3)
                  <div class="alert alert-danger alert-with-icon alert-dismissible fade show" role="alert">
                      <div class="alert-icon-box">
                          <i class="alert-icon czi-check-circle"></i>
                      </div>
                      {!! __('This campaign has been cancelled and is not accepting new orders. You can explore other campaigns by clicking :link', array('link'=>'<a href="'.route("campaigns.index").'">'.__("here").'</a>')) !!}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  @break
                  @case(2)
                  <div class="alert alert-warning alert-with-icon alert-dismissible fade show" role="alert">
                      <div class="alert-icon-box">
                          <i class="alert-icon czi-check-circle"></i>
                      </div>
                      {!! __('This campaign has been paused and is not accepting new orders. You can explore other campaigns by clicking :link', array('link'=>'<a href="'.route("campaigns.index").'">'.__("here").'</a>')) !!}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  @break
                  @case(0)
                  <div class="alert alert-warning alert-with-icon alert-dismissible fade show" role="alert">
                      <div class="alert-icon-box">
                          <i class="alert-icon czi-check-circle"></i>
                      </div>
                      {!! __('This campaign is pending approval and is not accepting new orders. You can explore other campaigns by clicking :link', array('link'=>'<a href="'.route("campaigns.index").'">'.__("here").'</a>')) !!}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  @break
                  @default
                  <h2 class="h3 pt-1 pb-4 mb-4 text-center text-sm-left border-bottom">{{__('Products and services')}}</h2>
                  <div class="row pt-2">

                  @foreach($campaign->products as $product)
                      <!-- Product-->
                          <div class="col-sm-6 mb-grid-gutter">
                              <div class="card product-card-alt">
                                  <div class="product-thumb">
                                      <div class="product-card-actions">
                                          <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ route('products.show',$product->id) }}"><i class="czi-eye"></i></a>
                                          <button class="btn btn-light btn-icon btn-shadow font-size-base mx-2 add-cart" type="button"
                                                  data-campaign-id="{{$product->campaigns->first()->id ?? 0}}"
                                                  data-product-id="{{$product->id}}"><i class="czi-cart"></i></button>
                                      </div>
                                      <a class="product-thumb-overlay" href="{{ route('products.show',$product->id) }}"></a>
                                      <img src="{{Thumbnail::thumb('product/'.$product->image, 300, 225, 'background')}}" alt="">
                                  </div>
                                  <div class="card-body">
                                      <div class="d-flex flex-wrap justify-content-between align-items-start pb-2">
                                          <div class="text-muted font-size-xs mr-1">by <a class="product-meta font-weight-medium" href="#">{{$campaign->business->name}}</a></div>
                                      </div>
                                      <h3 class="product-title font-size-sm mb-2"><a href="{{ route('products.show',$product->id) }}">{{$product->title}}</a></h3>
                                      <div class="d-flex flex-wrap justify-content-between align-items-center">
                                          <div class="font-size-sm mr-2"><i class="czi-announcement text-muted mr-1"></i>{{ __('Only :units left',['units'=>$product->units]) }}</div>
                                          <div class="bg-faded-accent text-accent rounded-sm py-1 px-2">@currency {{$product->formattedPrice}}</div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- End Product-->
                      @endforeach
                  </div>

                  <h2 class="h3 pt-2 pb-4 mb-4 text-center text-sm-left border-bottom">{{__('Share this campaign')}}</h2>
                  <div class="sharethis-inline-share-buttons"></div>

              @endswitch
          @endif
            </div>
          </section>

</div>


<div class="modal js-modal-donate" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        @if(Auth::user())
            @if(Auth::user()->hasCreditCard())
                <form method="post" action="{{ route('stripe.donate') }}" class="js-form-donate">
                    <div class="modal-header">
                    <h5 class="modal-title">{{__('Donation')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    @if (count($errors))
                      <div class="alert alert-danger" role="alert">
                        @foreach($errors->all() as $error)
                          <div>
                            {{$error}}
                          </div>
                        @endforeach
                      </div>
                    @endif
                    <p>
                      <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                      <input type="hidden" name="campaign_id" id="campaign_id" value="{{ $campaign->id }}"/>

                      <div class="form-group">
                        <label for="donate-amount">{{__('Donation amount in Euros')}}</label>
                        <input required type="number" class="form-control" name="donate-amount" id="donate-amount" placeholder="{{__('Amount in Euros')}}" value="{{old('donate-amount') ? old('donate-amount') : ''}}" min="1" max="50000" step="0.01">
                      </div>
                    <div class="py-3">
                        <div class="form-check">
                            <input type="checkbox" required  class="form-check-input @error('accept_terms') is-invalid @enderror" id="terms"
                                   {{ (old('accept_terms', false) ? 'checked':'') }}
                                   name="accept_terms" value="1">
                            <label class="form-check-label" for="terms">{{__('Yes, I agree to the')}} <a href="{{ config('custom.pages.terms_business.'.app()->getLocale(), config('custom.pages.terms_business.en')) }}" target="_blank">{{__('terms and condtions')}}</a></label>
                        </div>
                    </div>
                    </p>
                  </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success js-make-donation" value="{{__('Donate')}}"/>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </form>
            @else
                {{--no creditcard--}}
                <div class="modal-body">
                    <div class="text-right"><a href="{{ route('stripe.add-credit-card') }}" class="btn btn-warning btn-sm">{{__('Add Card')}}</a></div>
                    <div class="text-right text-warning font-size-sm">{{__('To make a donation you must have a credit card on file')}}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                </div>

            @endif
        @else
            {{--not logged in--}}
            <div class="modal-body">
                <div class="text-right"><a href="{{ route('login') }}" class="btn btn-primary btn-sm">{{__('Login')}}</a></div>
                <div class="text-right text-warning font-size-sm">{{__('To make a donation you must be logged in')}}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
            </div>

        @endif
    </div>
  </div>
</div>
@push('scripts')
<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5f0a553f0e7bec0012bd794a&product=inline-share-buttons&cms=sop' async='async'></script>
<script>
    $(document).ready(function() {
    	@if(old('donate-amount'))
		  $('.js-modal-donate').modal('show');
        @endif;

    $('.js-donate').on('click', function() {
    	$('.js-modal-donate').modal('show');
    });
    var allowSubmit = false;

    $('.js-form-donate').submit(function(e){
        if(!allowSubmit)
        {
            e.preventDefault();
            if( confirm("Are you sure you wish to donate " + $("#donate-amount").val() + " to <?php echo ($campaign->name)?>?"))
            {
                allowSubmit = true;
                $('.js-form-donate').submit();
            }
        }

     })
    });
</script>
@endpush


@endsection
