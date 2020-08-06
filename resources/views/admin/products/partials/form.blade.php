@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

<link href="{{ asset('vendor/bootstrap-fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendor/bootstrap-fileinput/themes/explorer-fa/theme.min.css') }}" media="all" rel="stylesheet" type="text/css" />

<style>
.fa-caret-down{
  -webkit-transition: -webkit-transform .4s ease;
  transition: -webkit-transform .4s ease;
  transition: transform .4s ease;
  transition: transform .4s ease,-webkit-transform .4s ease;
}
[aria-expanded=false] .fa-caret-down{
  -webkit-transform: rotate(-180deg);
  transform: rotate(-180deg);
}
.file-preview{
  padding: 0px;
  border: 0;
}
.file-drop-zone{
  margin:0;
}
.file-drag-handle.drag-handle-init,
.close.fileinput-remove{
   display:none;
}
</style>
@endpush

<input type="hidden" id="current_form_language" name="selected_lang" value="{{ $formLocale }}" />

<div class="row">
  <div class="col-md-6 mb-3">
    @foreach(supportedLocales() as $locale => $properties)
    <div class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
      <label for="{{$locale}}_title">{{ __('Title') }} ({{ $properties['native'] }})</label>
      <input id="{{$locale}}_title"
        type="text"
        class='form-control shadow-none @error("translations.{$locale}.title") is-invalid @enderror'
        name="translations[{{$locale}}][title]"
        value='{{ old("translations.{$locale}.title", $product->translate($locale)->title ?? "") }}'
        placeholder="{{ __('Name') }}"
        @if(isset($product->translate($locale)->title)) readonly @endif
      />
      @error("translations.{$locale}.title")
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>
    @endforeach
  </div>
  <div class="col-md-6 mb-3">
      @foreach(supportedLocales() as $locale => $properties)
      <label for="description" class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
        {{ __('Product category') }}
      </label>
      @endforeach

      <select class="custom-select d-block w-100  @error('category_id') is-invalid @enderror" id="category" required name="category_id"  @if(isset($product->category_id)) style="background-color: #f6f9fc;" @endif>

          @if(!isset($product->category_id))
            <option value="">{{ __('Select product category') }}</option>
            @foreach( $productCategories as $category )
                <option value="{{ $category->id }}"> {{ $category->name }} </option>
            @endforeach
          @else
              <option value="" disabled>{{ __('Select product category') }}</option>
              @foreach( $productCategories as $category )
                <option value="{{ $category->id }}" {{ (old('category_id',$product->category_id) == $category->id ? 'selected':'disabled') }} >
                 {{ $category->name }}
                </option>
              @endforeach
          @endif
      </select>
      @error('category_id')
      <span class="invalid-feedback my-1" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
  </div>
</div>

<div class="mb-3">
  @foreach(supportedLocales() as $locale => $properties)
  <div class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
    <label for="{{$locale}}_description">
        @if(!isset($product->translate($locale)->description))
      <a tabindex="-1" href="javascript:void(0)" class="" data-container="body" data-toggle="popover" data-placement="top" data-content="{{ __('Please include here any terms and conditions specific to this product (e.g. specific dates in which it can be redeemed)') }}" data-trigger="hover"> {{ __('Product Description') }} ({{ $properties['native'] }}):</a>
          @else
              {{ __('Product Description') }} ({{ $properties['native'] }}):
          @endif

    </label>
    <textarea id="{{$locale}}_description"
      type="text"
      maxlength="10000" minlength="10"
      class='form-control shadow-none @error("translations.{$locale}.description") is-invalid @enderror'
      name="translations[{{$locale}}][description]"
      placeholder="{{ __('Enter your product description here') }}"
      @if(isset($product->translate($locale)->description)) readonly @endif>{{ old("translations.{$locale}.description", $product->translate($locale)->description ?? "") }}</textarea>
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
          @if(!isset($product->start_date))
            <a tabindex="-1"
               href="javascript:void(0)"
               data-container="body"
               data-toggle="popover"
               data-placement="top"
               data-content="{{ __('Indicate here the future (or current) date from which your customer can collect the goods / use the service you are offering') }}"
               data-trigger="hover">
                {{__('Redeemable from')}}:
            </a>
          @else
            {{__('Redeemable from')}}:
          @endif
      </label>
      @endforeach

      <input type="text" class="form-control @error('start_date') is-invalid @enderror" id="start_date" placeholder="dd/mm/yyyy" name="start_date" value="{{ old('start_date', $product->start_date) }}" required>
      @error('start_date')
      <span class="invalid-feedback my-1" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="col-md-6 mb-3">

      @foreach(supportedLocales() as $locale => $properties)
      <label for="end_date" class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
          @if(!isset($product->end_date))
            <a tabindex="-1"
               href="javascript:void(0)"
               class=""
               data-container="body"
               data-toggle="popover"
               data-placement="top"
               data-title="{{ __('Indicate here until when the product/service can be redeemed.') }}"
               data-content="{{ __('If you do not indicate anything, our standard terms and conditions apply (the customer will have xxxxx months to redeem what he/she paid for)') }}"
               data-trigger="hover">
                {{__('Expiry date')}}:
            </a>
            <span class="text-muted">({{ __('Optional') }})</span>
          @else
            {{__('Expiry date')}}:
          @endif
      </label>
      @endforeach

      <input type="text" class="form-control @error('end_date') is-invalid @enderror" id="end_date" placeholder="dd/mm/yyyy" name="end_date" value="{{ old('end_date', $product->end_date) }}">
      @error('end_date')
      <span class="invalid-feedback my-1" role="alert">
          <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>
  </div>

<div class="row">
  <div class="col-md-6 mb-3">
    @foreach(supportedLocales() as $locale => $properties)
    <label for="product_price" class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
      {{ __('Price') }}
    </label>
    @endforeach

    <div class="input-group">
        <div class="input-group-prepend"><span class="input-group-text">@currency</span></div>
        <input type="number" step=".01" class="form-control @error('price') is-invalid @enderror" id="product_price" placeholder="10" value="{{ old('price', $product->price) }}" required name="price" min="1" max="100000">
    </div>
    @error('price')
      <span class="invalid-feedback my-1" role="alert">
          <strong>{{ $message }}</strong>
      </span>
      @enderror
  </div>

  <div class="col-md-6 mb-3">
    @foreach(supportedLocales() as $locale => $properties)
    <label for="units" class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
      {{ __('Units available') }}
    </label>
    @endforeach

    <input class="form-control @error('units') is-invalid @enderror" id="units" type="number" placeholder="10" name="units" value="{{ old('units', $product->units) }}" required min="1">
      @error('units')
      <span class="invalid-feedback my-1" role="alert">
          <strong>{{ $message }}</strong>
      </span>
      @enderror
  </div>
</div>

<div class="row">
  <div class="col input-field">
    @foreach(supportedLocales() as $locale => $properties)
    <div class="locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}">
      <label>
        {{ __('Product image') }}
      </label>
    </div>
    @endforeach

      <input id="fileInputFieldImage" type="file" name="image_input" class="fileImage {{ $errors->has('image') ? 'invalid' : '' }}" data-preview-file-type="text">
      <span class="invalid-feedback my-1" id="imgUploadError" role="alert"></span>
  </div>
</div>

<div class="row mt-5">
  <div class="col input-field">
    <input type="hidden" name="image" value="{{ old('image', $product->image) }}" id="fileInputHiddenImage" class="{{ $errors->has('image') ? 'invalid' : '' }}"/>
    @error('image')
    <span class="invalid-feedback my-1" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
</div>

<div class="row mb-4">
  <div class="col-12 mb-3">
    <div class="custom-control custom-switch custom-control-inline">

      <input type="checkbox" class="custom-control-input" name="active" id="product-active-{{ $product->id }}" value="1" {{ old('active', $product->active) ? 'checked' : '' }}/>

      @foreach(supportedLocales() as $locale => $properties)
        <label for="product-active-{{ $product->id }}" class="custom-control-label locale-field locale-field-{{ $locale }} {{ $locale==$formLocale ?: 'd-none' }}" tabindex="-1" href="javascript:void(0)" class="" data-container="body" data-toggle="popover" data-placement="top" title="{{ __('This product will be listed as available on your page.') }}" data-content="{{ __('If you want to temporarily remove it from your offering, mark it as Non Active') }}" data-trigger="hover">
          {{ __('Active') }}
        </label>
      @endforeach

    </div>
  </div>
</div>

@push('scripts')
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('js/theme.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-fileinput/themes/explorer-fa/theme.min.js') }}"></script>

<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
var $parentForm = $("#locale-form-wrapper");
var $elHidden = $("#fileInputHiddenImage");
var $el1 = $("#fileInputFieldImage");
$el1.fileinput({
  theme: "explorer-fa",
  showUpload: false,
  showRemove: false,
  showDownload: false,
  showDrag: false,
  dropZoneEnabled: false,
  uploadAsync: true,
  showUploadStats: false,
  allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg'],
  uploadUrl: "{{ route('campaign.product.imageUpload') }}",
  deleteUrl: "{{ route('campaign.product.imageDelete') }}",
  browseOnZoneClick: true,
  overwriteInitial: true, //replace file
  initialPreviewShowDelete: true,
  initialPreview: {!! $product->thumbURLs !!},
  initialPreviewAsData: true,
  initialPreviewConfig: {!! $product->thumbConfig->toJson() !!}
}).on('fileuploaded', function(event, previewId, index, fileId) {
  //store the new uploaded filename in our "image" hidden field
  $elHidden.val(previewId.response.uploadedFilename);
  //enable submit buttons
  $parentForm.find('[type=submit]').prop('disabled', false);
}).on('fileerror', function(event, data, msg) {
  //enable submit buttons
  $parentForm.find('[type=submit]').prop('disabled', false);
}).on("filebatchselected", function(event, files) {
  //disable submit buttons
  $parentForm.find('[type=submit]').prop('disabled', true);
  $el1.fileinput("upload");
}).on('fileuploaderror', function(event, data, msg) {
  document.getElementById('imgUploadError').innerHTML = `{{__('No valid data available for upload.')}}`;
  document.getElementById('imgUploadError').style.display = 'block';
  setTimeout(function() { document.getElementById('imgUploadError').style.display = 'none'; }, 5000);
  $parentForm.find('[type=submit]').prop('disabled', false);
}).on("filepredelete", function(jqXHR) {
  let abort = true;
  if (confirm("{{__('Are you sure you want to delete this image?')}}")) {
    abort = false;
  }
  return abort;
});
</script>
@endpush
