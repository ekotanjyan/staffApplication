<ul id="content-locale-tabs" class="nav_ nav-tabs_">
    @foreach(supportedLocales() as $locale => $properties)
    <li class="nav-item_">
        <a href="#"
           data-locale={{ $locale }}
           class="nav-link language-tab language-tab-{{ $locale }} {{ ($locale=='en' ? 'bg-indigo' : '') }}">
           <img src="{{asset('backend/images/flags/'.$locale.'.png')}}" />
           {{ $properties['native'] }}
        </a>
    </li>
    @endforeach
</ul>

@push('styles')
<style>
#content-locale-tabs.nav_{list-style-type:none; overflow:hidden;margin-top:10px;margin-bottom:15px;padding-left:10px;}
#content-locale-tabs .nav-item_{display:inline-block; float:left;}
#content-locale-tabs .nav-item_ a{padding:0px 10px 2px;font-size:13px;display:inline-block;border-radius:3px;overflow:hidden;text-decoration:none;}
#content-locale-tabs .nav-item_ a.bg-indigo{background:#00BCD4 !important;color:#fff;text-decoration:none;}
</style>
@endpush

@push('scripts')
<script>
$(function () {
  var locale_tabs_selector = '#content-locale-tabs';
  var locale_form_selector = '#locale-form-wrapper';

  $(locale_tabs_selector).on('click', '.language-tab', function(b){
    b.preventDefault();
    b.stopPropagation();

    var locale = $(this).data('locale');

    $(locale_tabs_selector+' .language-tab').each( function(){
      $(this).removeClass('bg-indigo');
    });

    $('.language-tab'+'-'+locale).addClass('bg-indigo').removeClass('d-none');

    $(locale_form_selector+' .locale-field').hide();
    $(locale_form_selector+' .locale-field-'+locale).removeClass('d-none').show();

  });
});
</script>
@endpush
