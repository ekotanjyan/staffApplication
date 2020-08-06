{{--
@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/intl-tel-input/css/intlTelInput.min.css') }}">
<style>
.iti{
  width:100%;
}
</style>
@endpush
@push('scripts')
<script src="{{ asset('vendor/intl-tel-input/js/intlTelInput.js') }}"></script>
<script>
  var input = document.querySelector("#telephone_selector");
  window.intlTelInput(input, {
    // any initialisation options go here
    separateDialCode:false,
    utilsScript: "{{ asset('vendor/intl-tel-input/js/utils.js') }}",
  });


var tel_selector = $('#telephone_selector');
var tel_input = $('#telephone');
var intlTel = intlTelInput(tel_selector.get(0))

// listen to the telephone input for changes
tel_selector.on('countrychange', function(e) {
  tel_input.val(`+${intlTel.getSelectedCountryData().dialCode}${intlTel.getNumber()}`);
});
</script>
@endpush
--}}
@push('scripts')

@endpush

<div class="mb-3">
    <label for="business">{{__('Business name')}}</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="business" required name="name" value="{{ old('name',$business->name) }}">
    @error('name')
    <span class="invalid-feedback my-1" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="telephone">{{__('Phone Number')}}</label>
        <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" value="{{ old('telephone',$business->telephone) }}" name="telephone">
        @error('telephone')
        <span class="invalid-feedback my-1" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="vatID">{{__('Vat ID')}}</label>
        <input type="text" class="form-control @error('vatid') is-invalid @enderror" id="vatID" value="{{ old('vatid',$business->vatid) }}" required name="vatid">
        @error('vatid')
        <span class="invalid-feedback my-1" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="description">{{__('Business category')}}</label>
    <select class="custom-select d-block w-100" id="category" required name="category_id">
    <option value="">{{ __('Choose...') }}</option>
    @foreach( $businessCategories as $category )
        <option value="{{ $category->id }}" {{ (old('category_id',$business->category_id) == $category->id ? 'selected':'') }}>{{ $category->name }}</option>
    @endforeach
    </select>
    @error('category')
    <span class="invalid-feedback my-1" role="alert">
    <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="mb-3">
    <label for="description">{{__('Description')}}</label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="3" name="description" placeholder="{{ __('Please enter your business description') }}">{{ old('description',$business->description) }}</textarea>
    @error('description')
    <span class="invalid-feedback my-1" role="alert">
    <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="address">{{__('Address')}}</label>
        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" placeholder="{{ __('1234 Main St') }}" required name="address" value="{{ old('address',$business->address) }}">
        @error('address')
        <span class="invalid-feedback my-1" role="alert">
        <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="address2">{{__('Address 2')}} <span class="text-muted">(Optional)</span></label>
        <input type="text" class="form-control @error('address2') is-invalid @enderror" id="address2" placeholder="{{ __('Apartment or suite') }}" name="address2" value="{{ old('address2',$business->address2) }}">
        @error('address2')
        <span class="invalid-feedback my-1" role="alert">
        <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="City">{{__('City')}}</label>
        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" value="{{ old('city',$business->city) }}" name="city" required>
        @error('city')
        <span class="invalid-feedback my-1" role="alert">
        <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="zip">{{__('Zip')}}</label>
        <input type="text" class="form-control @error('zip') is-invalid @enderror" id="zip" required value="{{ old('zip',$business->zip) }}" placeholder="{{ __('State or Province') }}" name="zip">
        @error('zip')
        <span class="invalid-feedback my-1" role="alert">
        <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    </div>

<div class="row">
    <div class="col-md-6 mb-3">
    <label for="country">{{__('Country')}}</label>
    @php $countries = app(\PragmaRX\Countries\Package\Countries::class)->all()->pluck('name.common')->toArray(); @endphp
    <select class="custom-select d-block w-100" id="country" required="" name="country">
        <option></option>
        @foreach($countries as $key => $country)
            <option {{old('country',$business->country)==$country ? 'selected':''}} value="{{$country}}">{{$country}}</option>
        @endforeach
    </select>

    </div>

    <div class="col-md-6 mb-3">
    <label for="state">{{__('State or Province')}} <span class="text-muted">({{__('Optional')}})</span></label>
    <input class="form-control @error('province') is-invalid @enderror" id="state" type="text" value="{{ old('province',$business->province) }}" placeholder="{{ __('State or Province') }}" name="province">
    @error('province')
    <span class="invalid-feedback my-1" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    </div>
</div>

<!-- Accordion made of cards -->
<div class="accordion pb-3" id="socialAccordion">

  <!-- Card -->
  <div class="card">
    <div class="card-header" id="headingOne">
      <h3 class="accordion-heading">
        <a href="#socialMedia" role="button" data-toggle="collapse" aria-expanded="true" aria-controls="socialMedia">
          {{__('Social Media')}}
          <span class="accordion-indicator">
            <i data-feather="chevron-up"></i>
          </span>
        </a>
      </h3>
    </div>
    <div class="collapse show" id="socialMedia" aria-labelledby="headingOne" data-parent="#socialAccordion">
      <div class="card-body">

      <div class="input-group pb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                <i class="czi-globe"></i>
                </span>
            </div>
            <input type="text" class="form-control @error('website') is-invalid @enderror" name="website" value="{{ old('website',$business->website) }}" placeholder="{{__('http://example.com')}}">
            @error('website')
            <span class="invalid-feedback my-1" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group pb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="czi-facebook"></i>
                </span>
            </div>
            <input type="text" class="form-control @error('facebook') is-invalid @enderror" name="facebook" value="{{ old('facebook',$business->facebook) }}" placeholder="{{__('http://facebook.com/page')}}">
            @error('facebook')
            <span class="invalid-feedback my-1" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group pb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                <i class="czi-instagram"></i>
                </span>
            </div>
            <input type="text" class="form-control @error('instagram') is-invalid @enderror" name="instagram" value="{{ old('instagram',$business->instagram) }}" placeholder="{{__('http://instagram.com/page')}}">
            @error('instagram')
            <span class="invalid-feedback my-1" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                <i class="czi-twitter"></i>
                </span>
            </div>
            <input type="text" class="form-control @error('twitter') is-invalid @enderror" name="twitter" value="{{ old('twitter',$business->twitter) }}" placeholder="{{__('http://twitter.com/page')}}">
            @error('twitter')
            <span class="invalid-feedback my-1" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>


      </div>
    </div>
  </div>

</div>
<!-- End Accordion made of cards -->
