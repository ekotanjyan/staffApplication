<div class="toast-container toast-top-center">
  <div class="toast" role="alert" data-delay="5500" aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-{{ $toastData['type'] }} text-white">
      <strong class="mr-auto toast-title">{{__('Alert!')}}</strong>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body text-{{ $toastData['type'] }}">
      {!! $toastData['body'] !!}
    </div>
  </div>
</div>

@if ( $toastData['body'] != '' )
  @push('scripts')
    <script>
    $('.toast').toast('show');
    </script>
  @endpush
@endif
