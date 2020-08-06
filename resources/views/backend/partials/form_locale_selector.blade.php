<select id="form_language_selector" class="custom-select d-none" style="width:initial;">
  @foreach(supportedLocales() as $locale => $properties)
  <option value="{{ $locale }}"
  {{ ($locale==old('selected_lang', config('custom.locale'))) ? 'selected' : '' }}>
    {{ $properties['native'] }}
  </option>
  @endforeach
</select>

@push('styles')
@endpush

@push('scripts')
<script>
$(function () {
    var locale_tabs_selector = '#form_language_selector';
    var locale_form_selector = '#locale-form-wrapper';
    var locale_form_field = '#current_form_language';

    //set the selector value equal to current form locale and show it
    $(locale_tabs_selector).val( $(locale_form_field).val() );
    $(locale_tabs_selector).removeClass('d-none');

    $(locale_tabs_selector).on('change', function(evt){
      var locale = evt.target.value;

      $(locale_form_selector+' .locale-field').hide();
      $(locale_form_selector+' .locale-field-'+locale).removeClass('d-none').show();
      $(locale_form_field).val(locale);
    });

});
</script>
@endpush
