<nav class="navbar navbar-expand-md navbar-light sticky-top {{ $navbarClass }}">
  <div class="container-xl">
  <a class="navbar-brand" href="/">
    <img src="{{ asset('images/logo-small.png') }}" width="180" height="60" alt="{{ config('app.name') }} Logo"/>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarGB" aria-controls="navbarGB" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarGB">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('campaign.create') }}">{{ __('Create your campaign') }}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('business.create') }}">{{ __('Add your business') }}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('campaigns.index') }}">{{ __('Support a Campaign') }}</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Account</a>
        <div class="dropdown-menu" aria-labelledby="dropdown09">
          <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
          </form>

          <a class="dropdown-item dropdown-toggle" href="#" id="dropdown10" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Campaigns</a>
          <div class="dropdown-menu" aria-labelledby="dropdown10">
            <a class="dropdown-item" href="{{ route('product.create') }}">{{ __('Add products') }}</a>
            <a class="dropdown-item" href="{{ route('campaign.index') }}">{{ __('Edit campaign') }}</a>
            <a class="dropdown-item" href="{{ route('product.index') }}">{{ __('Edit products') }}</a>
          </div>
        </div>
      </li>
      </ul>
    </div>
  </div>
</nav>
