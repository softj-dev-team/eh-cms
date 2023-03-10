@extends('plugins.member::layouts.skeleton')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Verify sms</div>
        <div class="card-body">
          <form method="POST" action="{{ route('public.member.verify.sms') }}">
            @csrf
            <div class="form-group row">
              <label for="phone" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.phone_number') }}</label>
              <div class="col-md-4">

                <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required>
                @if ($errors->has('phone'))
                <span class="invalid-feedback">
                  <strong>{{ $errors->first('phone') }}</strong>
                </span>
                @endif
              </div>
              <div class="col-md-2">
                <input type="hidden" name="recaptcha_response" id="recaptcha_response">
                <input type="hidden" name="sessionInfo" id="sessionInfo">
                <button id="send_sms" type="button" class="btn default-btn fw6" style="padding : 6px 8px">
                  {{ trans('plugins/member::dashboard.send_sms') }}
                </button>
              </div>

            </div>

            <div class="form-group row">
              <label for="verify_code" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.verify_code') }}</label>
              <div class="col-md-6">
                <input id="verify_code" type="text" class="form-control{{ $errors->has('verify_code') ? ' is-invalid' : '' }}" name="verify_code" value="{{ old('verify_code') }}" required>
                @if ($errors->has('verify_code'))
                <span class="invalid-feedback">
                  <strong>{{ $errors->first('verify_code') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn default-btn fw6">
                  Verify
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.2.10/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.10/firebase-auth.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/8.2.10/firebase-analytics.js"></script>

<script>
  // Your web app's Firebase configuration
  const firebaseConfig = {
    apiKey: "AIzaSyCHI1fPqoPqsDp98dOJcDKeJ5lFVX7GMlY",
    authDomain: "eh-notifications.firebaseapp.com",
    projectId: "eh-notifications",
    storageBucket: "eh-notifications.appspot.com",
    messagingSenderId: "764713474979",
    appId: "1:764713474979:web:448998667371ac4f921816",
    measurementId: "G-HCQL1XMPYP"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
</script>
<script>
  window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('send_sms', {
    'size': 'invisible'
  });
  $(function() {

    $('#send_sms').on('click', function(e) {

      e.preventDefault();
      var $input = $('#phone');
      var $this = $(this);
      if ($input.val() == '') {
        alert('Please enter phone number');
        $input.focus();
        return;
      }
      var phone = $input.val().replace($input.val().charAt(0), "+82");
      window.recaptchaVerifier.verify().then(function(response) {
        $('#recaptcha_response').val(response);
        if ($this.data('isRunAjax') == true) {
          return;
        }
        $this.css('pointer-events', 'none').data('isRunAjax', true);
        $.ajax({
          type: 'POST',
          url: '{{route('public.member.send.sms')}}',
          data: {
            _token: "{{ csrf_token() }}",
            'phone': phone,
            'recaptcha': $('#recaptcha_response').val()
          },
          success: function(data) {
            if (data.err != null) {
              alert('Send sms fail!!');
              console.log(data.err);
              return false;
            }
            $('#sessionInfo').val(data.msg);
            alert('send sms success');
            return true;

          }
        }).always(function() {
          $this.css('pointer-events', 'auto').data('isRunAjax', false);
        });
      });
      return false;

    })

  })
</script>
<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest(\Botble\Member\Http\Requests\RegisterRequest::class); !!}
@endpush
