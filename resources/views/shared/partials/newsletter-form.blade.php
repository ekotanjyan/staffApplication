<div class="widget w-100 mb-4 pb-3 text-center mx-auto" style="max-width: 28rem;">
  <h3 class="widget-title text-light pb-1">{{__('Subscribe to the LiUU newsletter')}}</h3>
  <form class="validate" id="newsletter-form" action="{{ config('newsletter.'.app()->getLocale(), config('newsletter.en') )['list'] }}" method="post" name="mc-embedded-subscribe-form" id="mc-embedded-subscribe-form">
    <div class="input-group input-group-overlay flex-nowrap">
      <div class="input-group-prepend-overlay"><span class="input-group-text text-muted font-size-base"><i class="czi-mail"></i></span></div>
      <input class="form-control prepended-form-control" type="email" name="EMAIL" id="mce-EMAIL" value="" placeholder="Your email" required="">
      <div class="input-group-append">
        <button class="btn btn-primary" type="submit" name="subscribe" id="mc-embedded-subscribe">Subscribe*</button>
      </div>
    </div>
    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;" aria-hidden="true">
        <input type="text" class="cz-subscribe-form-antispam" name="{{ config('newsletter.'.app()->getLocale(), config('newsletter.en') )['antispam_key'] }}" tabindex="-1">
    </div><small class="form-text text-light opacity-50" id="mc-helper">*{{__('Receive updates and more information')}}.</small>
    <div class="subscribe-status" id="subscribe-result"></div>
  </form>
</div>

@push('scripts')
<script>
function newsletterRegister($form){
  $.ajax({
    type: "post",
    url: $form.attr('action'),
    data: $form.serialize(),
    cache: false,
    dataType: 'json',
    contentType: "application/json; charset=utf-8",
    error: function (err) {
      console.log('error')
    },
    success: function (data) {
      if (data.result != "success") {
        console.log('Error: ' + data.msg);
        $($form).find("div#subscribe-result").html(`<div class='alert alert-danger'><strong>{{ __('Error') }}:</strong> ${data.msg}</div>`).fadeIn();
      } else {
        console.log("Success");
         $($form).find("div#subscribe-result").html(`<div class='alert alert-success'><strong>{{ __('Success') }}:</strong> {{ __('Almost finished... We need to confirm your email address. To complete the subscription process, please click the link in the email we just sent you!') }}'</div>`).fadeIn();
      }
    }
  });
}

$(document).on('submit', '#newsletter-form', function (event) {
  try {
    var $form = jQuery(this);
    event.preventDefault();
    newsletterRegister($form);
  } catch (error) {}
});
</script>
@endpush
