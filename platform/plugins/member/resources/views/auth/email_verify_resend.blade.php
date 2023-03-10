@extends('plugins.member::layouts.skeleton')
@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">{{ trans('plugins/member::dashboard.send_email') }}</div>
          <div class="card-body">
            <form method="POST" action="{{ route('public.member.verify.email.resend.post') }}">
              @csrf
              <div class="form-group row">
                <label for="id_login" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.id_login') }}</label>
                <div class="col-md-6">
                  <input id="id_login" type="text" class="form-control{{ $errors->has('id_login') ? ' is-invalid' : '' }}" name="id_login" value="{{ old('id_login') }}" required autofocus>
                  @if ($errors->has('id_login'))
                    <span class="invalid-feedback">
                  <strong>{{ $errors->first('id_login') }}</strong>
                </span>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.email') }}</label>
                <div class="col-md-6">

                  <input id="namemail" type="text" class="form-control{{ $errors->has('namemail') ? ' is-invalid' : '' }}" name="namemail" value="{{ old('namemail') }}" required>
                  @
                  <input id="domainmail" type="text" class="form-control{{ $errors->has('domainmail') ? ' is-invalid' : '' }}" name="domainmail" value="ewhain.net" >
                  <input type="hidden" name="email" value="" id="email">

                  @if ($errors->has('email'))
                  <span class="invalid-feedback">
                  <strong>{{ $errors->first('email') }}</strong>
                  </span>
                  @endif
                </div>
              </div>
              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn default-btn fw6">
                    보내기
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
  <script>
    $('#domainmail').change(function() {
      //Get
      var namemail = $('#namemail').val();
      var domainmail = $('#domainmail').val();

      //Set
      $('#email').val(namemail + '@' + domainmail);
    })

    $('#namemail').change(function() {
      //Get
      var namemail = $('#namemail').val();
      var domainmail = $('#domainmail').val();

      //Set
      $('#email').val(namemail + '@' + domainmail);
    })
  </script>
  <!-- Laravel Javascript Validation -->
  <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
  {!! JsValidator::formRequest(\Botble\Member\Http\Requests\RegisterRequest::class); !!}
@endpush
