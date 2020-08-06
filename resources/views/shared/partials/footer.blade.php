<footer class="bg-dark pt-5">
  <div class="container pt-2 pb-3">
    <div class="row">
      <div class="col-md-6 text-center text-md-left mb-4">
        <div class="text-nowrap mb-3"><a class="d-inline-block align-middle mt-n2 mr-2" href="#">
        <img class="d-block" width="117" src="{{ asset('images/logo-md.png') }}" alt="LiUU Logo"></a>
        <span class="align-middle font-weight-light text-white mb-0 d-inline-block d-sm-none">crowdliquidity by LiUU</span>
        <span class="align-middle font-weight-light text-white mb-0 h5 d-none d-sm-inline-block">crowdliquidity by LiUU</span>
        </div>
        <!--<p class="font-size-sm text-white opacity-70 pb-1">crowdliquidity</p>
        <h6 class="d-inline-block pr-3 mr-3 border-right border-light"><span class="text-primary">65,478 </span><span class="font-weight-normal text-white">Products</span></h6>
        <h6 class="d-inline-block pr-3 mr-3 border-right border-light"><span class="text-primary">2,521 </span><span class="font-weight-normal text-white">Members</span></h6>
        <h6 class="d-inline-block mr-3"><span class="text-primary">897 </span><span class="font-weight-normal text-white">Vendors</span></h6>
        <div class="widget mt-4 text-md-nowrap text-center text-md-left"><a class="social-btn sb-light sb-twitter mr-2 mb-2" href="#"><i class="czi-twitter"></i></a><a class="social-btn sb-light sb-facebook mr-2 mb-2" href="#"><i class="czi-facebook"></i></a><a class="social-btn sb-light sb-dribbble mr-2 mb-2" href="#"><i class="czi-dribbble"></i></a><a class="social-btn sb-light sb-behance mr-2 mb-2" href="#"><i class="czi-behance"></i></a><a class="social-btn sb-light sb-pinterest mr-2 mb-2" href="#"><i class="czi-pinterest"></i></a></div>-->
      </div>

      <!-- Desktop menu (visible on screens above md)-->
      <div class="col-md-3 d-none d-md-block text-center text-md-left mb-4">
        <div class="widget widget-links widget-light pb-2">
          <h3 class="widget-title text-light">{{__('Campaigns')}}</h3>
          <ul class="widget-list">
            <li class="widget-list-item"><a class="widget-list-link" href="{{route('campaign.index')}}">{{__('View all campaigns')}}</a></li>
            <li class="widget-list-item"><a class="widget-list-link" href="{{route('campaign.create')}}">{{__('Create your campaign')}}</a></li>
          </ul>
        </div>
      </div>
      <div class="col-md-3 d-none d-md-block text-center text-md-left mb-4">
        <div class="widget widget-links widget-light pb-2">
          <h3 class="widget-title text-light">{{__('For members')}}</h3>
          <ul class="widget-list">
            <li class="widget-list-item"><a class="widget-list-link" href="{{route('login')}}">{{__('My account')}}</a></li>
            <li class="widget-list-item"><a class="widget-list-link" href="{{route('register')}}">{{__('Register')}}</a></li>
            <!--<li class="widget-list-item"><a class="widget-list-link" href="#">Become a vendor</a></li>
            <li class="widget-list-item"><a class="widget-list-link" href="#">Become an affiliate</a></li>
            <li class="widget-list-item"><a class="widget-list-link" href="#">Marketplace benefits</a></li>-->
          </ul>
        </div>
      </div>
    </div>
    </div>

      <!-- Second row-->
    <div class="pt-5 bg-darker">
    <div class="container">


      @if( $includeSubscribeForm )
        @include('shared.partials/newsletter-form')
        <hr class="hr-light pb-4 mb-3">
      @endif

      <div class="navbar navbar-expand-lg navbar-light">
        <div class="collapse navbar-collapse mr-auto order-lg-2" id="navbarCollapse">
          <!-- Search-->
          <div class="input-group-overlay d-lg-none my-3">
            <div class="input-group-prepend-overlay"><span class="input-group-text"><i class="czi-search"></i></span></div>
            <input class="form-control prepended-form-control" type="text" placeholder="Search for products">
          </div>
          <!-- Primary menu-->
          <ul class="navbar-nav">
            <li class="nav-item p-3 font-size-xs text-light opacity-50 text-center text-md-left">
              © {{__('All rights reserved')}}. <a class="text-light" href="https://liuu.world/" target="_blank" rel="noopener">crowdliquidity by LiUU</a>
            </li>
          </ul>
        </div>

        <div class="navbar-toolbar d-flex align-items-center order-lg-3">
          @include('frontend.partials.language_switcher_new', ['dropup' => true])
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link font-size-ms p-3 text-light opacity-50 text-center text-md-left" target="_blank" href="https://liuu.world/en/imprint">{{__('Imprint')}}</a></li>
            <li class="nav-item"><a class="nav-link font-size-ms p-3 text-light opacity-50 text-center text-md-left" target="_blank" href="{{ config('custom.pages.privacy.'.app()->getLocale(), config('custom.pages.privacy.en')) }}">{{__('Privacy')}}</a></li>
            <li class="nav-item"><a class="nav-link font-size-ms p-3 text-light opacity-50 text-center text-md-left" target="_blank" href="{{ config('custom.pages.rules_for_businesses.'.app()->getLocale(), config('custom.pages.rules_for_businesses.en')) }}">{{__('Rules for businesses')}}</a></li>
            <li class="nav-item"><a class="nav-link font-size-ms p-3 text-light opacity-50 text-center text-md-left" target="_blank" href="{{ config('custom.pages.your_security.'.app()->getLocale(), config('custom.pages.your_security.en')) }}">{{__('Your security')}}</a></li>
            <li class="nav-item">
              <a target="_blank" class="nav-link font-size-ms p-3 text-light opacity-50 text-center text-md-left" href="{{ config('custom.pages.terms_business.'.app()->getLocale(), config('custom.pages.terms_business.en')) }}">{{ __('Terms and Conditions') }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

</footer>
