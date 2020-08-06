<header class="navbar navbar-expand-lg navbar-light {{ $navbarClass ?? ''}}">
    <div class="container">
        <a class="navbar-brand d-none d-lg-block mr-3 order-lg-1" href="{{route('home')}}" style="min-width: 7rem;">
            <img width="142" src="{{ asset('images/logo-small.png') }}" alt="crowdliquidity by LiUU">
        </a>
        <a class="navbar-brand d-lg-none order-lg-1" href="{{route('home')}}" style="min-width: 2.125rem; margin-right: 0 !important;">
            <img width="100" src="{{ asset('images/logo-small.png') }}" alt="crowdliquidity by LiUU"/>
        </a>

        <div class="navbar-toolbar d-flex align-items-center order-lg-3">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="dropdown">

                <a class="navbar-tool ml-1 mr-n1" data-toggle="dropdown" href="#">
                    <div class="navbar-tool-icon-box">
                        <i class="navbar-tool-icon czi-user"></i>
                    </div>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdown09">
                    @guest
                        <a class="dropdown-item" href="{{ route('login') }}">{{__('Login')}}</a>
                        <a class="dropdown-item" href="{{ route('register') }}">{{__('Register')}}</a>
                    @endguest

                    @auth
                        <a class="dropdown-item" href="{{ route('business.index') }}">{{__('Dashboard')}}</a>
                        <a class="dropdown-item" href="{{ route('stripe.charges') }}">{{__('My Purchases')}}</a>
                        <a class="dropdown-item" href="{{ route('stripe.seller-charges') }}">{{__('My Sales')}}</a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    @endauth
                </div>
            </div>
            <!-- cart dropdown here -->
            <div class="navbar-tool dropdown ml-3">
                <a class="navbar-tool-icon-box bg-secondary dropdown-toggle parent-clicked" role="button" data-toggle="dropdown" data-target="#" href="{{ route('cart.index') }}" onclick="location.href = this.href">
                    <span class="navbar-tool-label" id="cart-badge-number">{{Cart::content()->count()}}</span><i class="navbar-tool-icon czi-cart"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" id="cart_popup" style="width: 20rem;">
                    @include('frontend.partials.minicart')
                </div>
            </div>
            @include('frontend.partials.language_switcher_new')
        </div>

        <div class="collapse navbar-collapse mr-auto order-lg-2" id="navbarCollapse">
            <!-- Primary menu-->
            <ul class="navbar-nav">
                <li class="nav-item dropdown"><a class="nav-link" href="{{route('home')}}#featured-campaigns">{{__('Support a campaign')}}</a></li>
                @if(auth()->guest() || !auth()->user()->campaigns()->exists() )
                    <li class="nav-item"><a class="nav-link" href="{{route('campaign.create')}}">{{__('Create a campaign')}}</a></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{route('campaign.index')}}">{{__('Manage a campaign')}}</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" target="_blank" href="https://liuu.world">{{__('About us')}}</a></li>
            </ul>
        </div>

    </div>
</header>
