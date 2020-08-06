@push('styles')
<style>
.language-selector:hover .dropdown-menu{ display:initial !important; }
</style>
@endpush

<!-- Language switcher -->
<div class="ml-lang language-selector dropdown {{ isset($dropup) && $dropup ? 'dropup' : '' }}">
  <a class="dropdown-item dropdown-toggle lang-dropdown {{ isset($dropup) && $dropup ? 'text-light opacity-50' : ''}}" data-toggle="dropdown" href="#">
    <img src="{{asset('backend/images/flags/'.app()->getLocale().'.png')}}" />
    &nbsp;{{ ucfirst( supportedLocales()[app()->getLocale()]['native'] ) }}
  </a>

  <div class="dropdown-menu" style="right:0;left:initial;{{ isset($dropup) && $dropup ? 'bottom:90%;' : '' }}">
    @foreach( supportedLocales() as $localeCode => $properties)
    @if( $localeCode != app()->getLocale() )
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
      @endif
    @endforeach
  </div>
</div>
