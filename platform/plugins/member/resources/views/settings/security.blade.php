@extends('plugins.member::layouts.skeleton')

@section('content')
  <style>
    #confirmDeletePopup .modal-header {
      color: #ec1569;
      text-transform: uppercase;
      font-weight: bold;
    }
  </style>

  <div class="settings">
    <div class="container">
      <div class="row">
        @include('plugins.member::settings.sidebar')
        <div class="col-12 col-md-9">
          <div class="mb-5">
            <!-- Title -->
            <div class="row">
              <div class="col-12">
                <h4 class="with-actions">{{ trans('plugins/member::dashboard.security_title') }}</h4>
              </div>
            </div>

            <!-- Content -->
            <div class="row">
              <div class="col-lg-8">
                @if (session('status'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                @endif
                <form method="POST" action="{{ route('public.member.post.security') }}" class="settings-reset">
                  @method('PUT')
                  @csrf
                  <div class="form-group">
                    <label for="current_password">{{ trans('plugins/member::dashboard.current_password') }}</label>
                    <input type="password" class="form-control" name="current_password" id="current_password">
                  </div>
                  <div class="form-group">
                    <label for="password">{{ trans('plugins/member::dashboard.password_new') }}</label>
                    <input type="password" class="form-control" name="password" id="password">
                    <span id="password-warning"
                          style="display: none;width: 100%;margin-top: .25rem;font-size: 80%;color: #EC1469;">비밀번호와 새로운 비밀번호가 일치합니다</span>
                  </div>
                  <div class="form-group">
                    <label
                      for="password_confirmation">{{ trans('plugins/member::dashboard.password_new_confirmation') }}</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                  </div>
                  <button type="submit"
                          class="btn default-btn fw6">{{ trans('plugins/member::dashboard.password_update_btn') }}</button>
                </form>
              </div>
            </div>
          </div>

          <div class="mb-3 br2">
            <!-- Title -->
            <div class="row">
              <div class="col-12">
                <h4>{{ trans('plugins/member::dashboard.danger_zone_title') }}</h4>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-8">
                <div class="ba br2 b--darkest-red">
                  <ul class="list pa0">
                    <li class="pa3" style="background: none;">
                      @if(empty(auth()->guard('member')->user()->destroy_date))
                        <div class="mb-2">{{ trans('plugins/member::dashboard.delete_account_desc') }}</div>
                        <form method="POST" action="{{route('public.member.post.account.delete')}}"
                              id="deleteAccountForm">
                          @csrf
                          <button type="button" class="btn default-btn fw6 darkest-red" id="deleteAccountBtn">
                            {{ trans('plugins/member::dashboard.delete_account_btn') }}
                          </button>

                          <input type="hidden" name="password" id="password-delete">
                          <input type="hidden" name="password_confirmation" id="password-confirmation-delete">
                        </form>
                      @else
                        <div
                          class="mb-2">{!! trans('plugins/member::dashboard.deleted_account_desc', ['destroy_date' => auth()->guard('member')->user()->destroy_date]) !!}</div>
                        <form method="POST" action="/users/reverse">
                          @csrf
                          <button type="submit" class="btn default-btn fw6 darkest-red">
                            {{ trans('plugins/member::dashboard.cancel_delete_account_btn') }}
                          </button>
                        </form>
                      @endif
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade modal--confirm" id="confirmDeletePopup" tabindex="-1" role="dialog"
       aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header align-items-center justify-content-lg-center md-header" style="background: #EC1469">
          {{ trans('plugins/member::dashboard.are_you_sure_you_want_to_delete_your_account') }}
        </div>

        <div class="modal-body">
          <div>
            {{ trans('plugins/member::dashboard.delete_account_text') }}
          </div>

          <div class="form-group form-group--1 flex-grow-1 mr-lg-20 mb-3">
            <label for="passwd_member" class="form-control form-control--hint">
              <input type="password" id="password-temp" placeholder=" " value=""
                     maxlength="16" required>
              <span class="form-control__label" style="pointer-events: none;">
                {{ trans('plugins/member::dashboard.password') }}
              </span>
            </label>
            <span class="form-control__hint hint_setting"></span>
          </div>

          <div class="form-group form-group--1 flex-grow-1 mr-lg-20 mb-3">
            <label for="passwd_member" class="form-control form-control--hint">
              <input type="password" id="password-confirmation-temp" placeholder=" " value=""
                     maxlength="16" required>
              <span class="form-control__label" style="pointer-events: none;">
                {{ trans('plugins/member::dashboard.password-confirmation') }}
              </span>
            </label>
            <span class="form-control__hint hint_setting"></span>
          </div>

          <div class="button-group mb-2" style="float: right">
            <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">
              <span style="display: block; width: 30px;">{{ trans('plugins/member::dashboard.close') }}</span>
            </button>
            <button type="button" class="btn btn-primary checkPasswdSetting" id="confirmDeleteBtn">
              <span style="display: block; width: 30px;">{{ trans('plugins/member::dashboard.save') }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <!-- Laravel Javascript Validation -->
  <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
  {!! JsValidator::formRequest(\Botble\Member\Http\Requests\UpdatePasswordRequest::class); !!}

  <script>
    $(function () {
      $(document.body).on('change', '#password', function () {
        let current_password = $('#current_password').val();
        let password = $('#password').val();
        if (password != '' && current_password == password && password.length >= 8 && password.length <= 16) {
          $('#password-warning').show();
        } else {
          $('#password-warning').hide();
        }
      });

      $('#deleteAccountBtn').click(() => {
        $('#confirmDeletePopup').modal('show');
      });

      $('#confirmDeleteBtn').click(() => {
        let password = $('#password-temp').val();
        let passwordConfirmation = $('#password-confirmation-temp').val();
        $('#password-delete').val(password);
        $('#password-confirmation-delete').val(passwordConfirmation);
        $('#deleteAccountForm').submit();
      });
    });
  </script>
@endpush
