<!-- Language switcher -->
<li class="{{ $liClass ?: 'nav-item dropdown ' }}">
  <a class="{{ $linkClass ?: 'nav-link' }} dropdown-toggle" href="#" id="language-switcher-{{ $target ?? 'main' }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <img src="{{asset('backend/images/flags/'.app()->getLocale().'.png')}}" />
    {{ ucfirst( supportedLocales()[app()->getLocale()]['native'] ) }}
  </a>
  <div class="dropdown-menu" aria-labelledby="language-switcher-{{ $target ?? 'main' }}">
    @foreach( supportedLocales() as $localeCode => $properties)

      {{--
      <!-- if we are on single campaign page and it has no translation, just dont show it in the language selector -->
      --}}

      <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true)
      }}">
        <div class="locale_flag_and_name">
          <img src="{{asset('backend/images/flags/'.$localeCode.'.png')}}" style="margin-right:3px;" />
          {{ $properties['native'] }}
        </div>

        @if( Route::currentRouteName() == 'campaigns.show' && isset($campaign) )
          <div class="locale_availability_icon">
            @if( $campaign->hasTranslation($localeCode) )
              <i class="fa fa-check-square-o text-primary"></i>
            @else
              <i class="fa fa-minus text-muted" style="position:relative;left:-2px;"></i>
            @endif
          </div>
        @endif

      </a>
    @endforeach
  </div>
</li>
