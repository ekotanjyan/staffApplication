
<style>
.custom-control-label::before,
.custom-control-label::after {
  width:1.5rem;
  height:1.5rem;
}
</style>

<div class="alert alert-success" role="alert">
  <i class="czi-arrow-right arrow-to-right opacity-60 text-success"></i>&nbsp;
  <a target="_blank" class="hover-underline text-success" href="{{ config('campaign.tips.'.app()->getLocale(), config('campaign.tips.en') ) }}">
    {{ __('Tips for successful campaign') }}
  </a>
</div>

<input type="hidden" id="current_form_language" name="selected_lang" value="{{ $formLocale }}" />

<div class="mb-3">
  @foreach(supportedLocales() as $locale => $properties)
  <div class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
    <label for="{{$locale}}_name">{{ __('Campaign name') }} ({{ $properties['native'] }}): </label>
    <input id="{{$locale}}_name"
      type="text"
      class='form-control shadow-none @error("translations.{$locale}.name") is-invalid @enderror'
      name="translations[{{$locale}}][name]"
      value='{{ old("translations.{$locale}.name", $campaign->translate($locale)->name ?? "") }}'
      placeholder="{{ __('Campaign name') }}"
    />
    @error("translations.{$locale}.name")
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
  @endforeach
</div>

<div class="row" id="start">
  <div class="col-md-6 mb-3">
    @foreach(supportedLocales() as $locale => $properties)
    <label for="business" class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
      {{ __('Business') }}
    </label>
    @endforeach
    <select class="form-control @error('business_id') is-invalid @enderror" id="business" required name="business_id">
    <option value="">{{ __('Choose...') }}</option>
    @foreach( $businesses as $business )
        <option value="{{ $business->id }}" {{ (old('business_id',$campaign->business_id) == $business->id ? 'selected':'') }}>{{ $business->name }}</option>
    @endforeach
    </select>

  </div>

  <div class="col-md-6 mb-3">
    @foreach(supportedLocales() as $locale => $properties)
    <label for="campaign_target" class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
      <a tabindex="-1" href="javascript:void(0)" class="" data-container="body" data-toggle="popover" data-placement="top" title="{{ __('Liquidity Funding Target') }}" data-content="{{ __('Try to make this realistic. If you need help to calculate your liquidity needs, try our liquidity calculator.') }}" data-trigger="hover" >
        {{ __('Liquidity Funding Target in') }} &#8364;
      </a>
    </label>
    @endforeach
    <input type="number" max="100000" min="100" class="form-control @error('target') is-invalid @enderror" id="campaign_target" placeholder="{{ __('Funding needed') }}" value="{{ old('target', $campaign->target) }}" required name="target">
  </div>
</div>

<div class="mb-3">
  @foreach(supportedLocales() as $locale => $properties)
  <div class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
    <label for="{{$locale}}_description">
      <a tabindex="-1" href="javascript:void(0)" class="" data-container="body" data-toggle="popover" data-placement="top" title="{{ __('Campaign Description') }}" data-content="{{ __('Try to make this as descriptive as possible') }}" data-trigger="hover">
        {{ __('Campaign Description') }} ({{ $properties['native'] }}):
      </a>
    </label>
    <textarea id="{{$locale}}_description"
      class='form-control shadow-none @error("translations.{$locale}.description") is-invalid @enderror'
      name="translations[{{$locale}}][description]"
      placeholder="{{ __('Enter your campaign description here') }}"
    >{{ old("translations.{$locale}.description", $campaign->translate($locale)->description ?? "") }}</textarea>
    @error("translations.{$locale}.description")
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
  @endforeach
</div>

<div class="row">
  <div class="col-md-6 mb-3">
    @foreach(supportedLocales() as $locale => $properties)
    <label for="start_date" class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
      {{ __('Start date') }} <span class="text-muted">({{ __('Optional') }})</span>
    </label>
    @endforeach
    <input type="text" class="form-control @error('start_date') is-invalid @enderror" id="start_date" placeholder="dd/mm/yyyy" value="{{ old('start_date', $campaign->start_date) }}" name="start_date">
    @error("start_date")
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
  <div class="col-md-6 mb-3">
    @foreach(supportedLocales() as $locale => $properties)
    <label for="end_date" class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
      {{ __('End date') }} <span class="text-muted">({{ __('Optional') }})</span>
    </label>
    @endforeach
    <input type="text" class="form-control @error('end_date') is-invalid @enderror" id="end_date" placeholder="dd/mm/yyyy" value="{{ old('end_date', $campaign->end_date) }}" name="end_date">
    @error("end_date")
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
</div>

<div class="mb-3">
  @foreach(supportedLocales() as $locale => $properties)
  <div class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
    <label for="{{$locale}}_yourtnc">
      <a tabindex="-1" href="javascript:void(0)" class="" data-container="body" data-toggle="popover" data-placement="top"
      title="{{ __('Campaign Terms and conditions') }}"
      data-content="{{ __('This is where you enter your terms and conditions for your clients. You can also enter a link in the following way: https://yourcompany.com/terms') }}" data-trigger="hover">{{ __('Campaign terms and conditions') }}</a>
      <span class="text-muted">({{ $properties['native'] }} - {{ __('Optional') }})</span></label>

      <textarea
        class='form-control @error("translations.{$locale}.tnc") is-invalid @enderror'
        id="{{$locale}}_yourtnc"
        rows="3"
        name="translations[{{$locale}}][tnc]"
        placeholder="{{ __('Enter your terms and conditions or a link to your terms and conditions here') }}"
        >{{ old("translations.{$locale}.tnc", $campaign->translate($locale)->tnc ?? "") }}</textarea>

      @error("translations.{$locale}.description")
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
  </div>
  @endforeach
</div>

<div class="row">    <!-- Drag and drop file upload -->
  @if( $campaign->image )
  <div class="col-6 mb-5" id="currentImage">
    @foreach(supportedLocales() as $locale => $properties)
    <div class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
      <label>{{__('Current image')}}</label>
    </div>
    @endforeach
    <div class="position-absolute">
      <img src="{{ Thumbnail::thumb('campaign/'.$campaign->image, 300, 225) }}" id="currentImage" class="img-thumbnail" style="height:180px;"/>
      <a class="btn btn-xs btn-secondary btn-icon float-right position-absolute" style="z-index: 2; top: 0; right: 0;" role="button" onclick="deleteCurrentImage({{$campaign->id}})">
        <i class="czi-trash text-primary"></i>
      </a>
    </div>
  </div>
  @endif

  <div class="col">
    @foreach(supportedLocales() as $locale => $properties)
    <div class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
      <label>{{__('New image')}}</label>
    </div>
    @endforeach

    <div class="cz-file-drop-area @error('image') is-invalid @enderror">
      <div class="cz-file-drop-icon czi-cloud-upload"></div>

      @foreach(supportedLocales() as $locale => $properties)
        <span class="cz-file-drop-message locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
          {{__('Drag and drop here to upload image.')}}
        </span>
      @endforeach

      <input type="file" id="fileInputFieldImage" name="image" class="cz-file-drop-input" value="{{ $campaign->image ?? '' }}">
      <div class="btn btn-danger btn-sm" id="removeImage" style="display: none">
        <span>{{__('Remove')}}</span>
      </div>
      <button type="button" class="cz-file-drop-btn btn btn-success btn-sm">
        @foreach(supportedLocales() as $locale => $properties)
          <span class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
            {{__('Or select file')}}
          </span>
        @endforeach
      </button>
    </div>
  </div>
</div>

<div class="row mb-5">
  <div class="col">
    @error("image")
    <span class="form-control d-none @error('image') is-invalid @enderror"></span>
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
</div>

<!-- Card deck -->
  @if( ! $products->isEmpty() )
  <p>{{ __('Associate products with campaign.') }}</p>
  <div class="row mb-5 py-3 px-2 rounded border bg-secondary">
    @foreach( $products as $product )
    <div class="col-lg-4 col-md-4 col-sm-6 px-2 mb-grid-gutter">
      <!-- Card -->
      <div class="card" for="check-product-{{ $product->id }}">
        <div class="custom-control custom-checkbox" style="position:absolute;left:3px">
          <input class="custom-control-input" name="products[]" value="{{$product->id}}" type="checkbox" id="check-product-{{ $product->id }}" {{ in_array($product->id,old('products',$campaign->products->pluck('id')->toArray())) ? 'checked':''}}>
          <label class="custom-control-label" for="check-product-{{ $product->id }}"></label>
        </div>

        <img src="{{Thumbnail::thumb('product/'.$product->image, 300, 225 )}}" class="card-img-top" alt="Card image"/>

        <div class="card-body">
          <h5 class="card-title">{{ $product->title }}</h5>
          <p class="card-text font-size-sm text-muted">{{ strlimit($product->description, 30, '...') }}</p>
        </div>
      </div>
      <!-- End Card -->
    </div>
    @endforeach
  </div>
  @endif


@push('scripts')
<script type="text/javascript">
(function($){
  jQuery( document ).ready(function($) {

    $("#start_date").datepicker({
      showAnim: "slideDown",
      dateFormat: "yy-mm-dd",
      minDate: '+0d'
    });

    $("#end_date").datepicker({
      showAnim: "slideDown",
      dateFormat: "yy-mm-dd",
      minDate: '+1d'
    });
    $('[data-toggle="popover"]').popover();

    $("#fileInputFieldImage").change(function () {
      // $(".cz-file-drop-icon.czi-cloud-upload").hide();
      $(".cz-file-drop-preview.img-thumbnail.rounded").show();
      $("#removeImage").show();
    });

    $("#removeImage").click(function (e) {
      e.preventDefault();
      $(".cz-file-drop-preview.img-thumbnail.rounded").hide();
      let dropAreaSelector = ".cz-file-drop-area"
      if (!$(dropAreaSelector).find(".cz-file-drop-icon.czi-cloud-upload").length) {
        $(dropAreaSelector).prepend('<div class="cz-file-drop-icon czi-cloud-upload"></div>');
      }
      $("#fileInputFieldImage").val(null);
      $("#removeImage").toggle();
    });
  });

})(jQuery)

function deleteCurrentImage(id) {
  if (confirm("{{__('Are you sure you want to delete this image?')}}")) {
    $.post("{{ route('campaign.imageDelete') }}",
            {
              key: id
            },
            function(data, status){
              if (status === "success") {
                $("#currentImage").hide();
              }
            });
  }
}
</script>
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('js/theme.min.js') }}"></script>
@endpush
