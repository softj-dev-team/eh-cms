@extends('plugins.member::layouts.skeleton')
@section('content')
  <div class="settings crop-avatar">
    <div class="container">
      <div class="row">
        @include('plugins.member::settings.sidebar')
        <div class="col-12 col-lg-9">
          <!-- Setting Title -->
          <div class="row">
            <div class="col-12">
              <h4 class="with-actions">
                {{ trans('plugins/member::dashboard.account_field_title') }} &nbsp;
                <span id="show-button" style="cursor: pointer;display: none">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                       class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                    <path
                      d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                  </svg>
                </span>

                <span id="hide-button" style="cursor: pointer; display: none">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                       class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                    <path
                      d="M10.79 12.912l-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.027 7.027 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.088z"/>
                    <path
                      d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708l-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6l-12-12 .708-.708 12 12-.708.707z"/>
                  </svg>
                </span>
              </h4>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4 order-lg-12">
              <div class="avatar-upload-container">
                <div class="form-group">
                  <label for="account-avatar">{{ trans('plugins/member::dashboard.profile-picture') }}</label>
                  <div id="account-avatar">
                    <div class="profile-image" style="cursor: unset">
                      <div class="">
                        <img class="br2" src="{{ getLvlImage() }}">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-8 order-lg-0">
              @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('status') }}

                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
            @endif
            <!-- Level -->
              <div id="level-block" class="header header_level">
                <div class="account__overview" style="width: 100%">
                  <p class="d-flex justify-content-between account__info">
                    <span class="account__name">{{ auth()->guard('member')->user()->nickname }}</span>
                    <span class="account__level">Level {{ getLevelMember() }}  <b>({{getPercentLevelMember().'%'}}) </b></span>
                  </p>
                  <div class="account__process">
                    <span style="width: {{getPercentLevelMember()}}%"></span>
                  </div>
                </div>
              </div>
              <form action="{{ route('public.member.post.settings') }}" id="setting-form" method="POST"
                    enctype="multipart/form-data">
              @csrf
              <!-- ID -->
                <div class="form-group">
                  <label for="ID">{{ trans('plugins/member::dashboard.id_login') }}</label>
                  <input type="text" class="form-control" id="id_login" disabled="disabled"
                         placeholder="{{ trans('plugins/member::dashboard.email_placeholder') }}" required
                         value="{{ old('id_login') ?? $user->id_login }}">
                </div>
                <!-- nickname -->
                <div class="form-group">
                  <label for="nickname">{{ trans('plugins/member::dashboard.nickname') }}</label>
                  <input type="text" class="form-control" id="nickname" required name="nickname"
                         value="{{ old('nickname') ?? $user->nickname }}">
                </div>
                <!-- fullname -->
                <div class="form-group">
                  <label for="fullname">{{ trans('plugins/member::dashboard.fullname') }}</label>
                  <input type="text" class="form-control" id="fullname" disabled="disabled" required
                         value="{{ old('fullname') ?? $user->fullname }}">
                </div>

                <!-- Phone -->
                <div class="form-group">
                  <label for="phone">{{ trans('plugins/member::dashboard.phone') }}</label>
                  <input type="text" class="form-control" id="phone" disabled="disabled" required
                         value="{{ old('phone') ?? $user->phone }}">
                </div>
                <!-- Email -->
                <div class="form-group">
                  <label for="email">{{ trans('plugins/member::dashboard.email') }}</label>
                  <input type="email" class="form-control" name="email" id="email"
                         placeholder="{{ trans('plugins/member::dashboard.email_placeholder') }}" required
                         value="{{ old('email') ?? $user->email }}">
                </div>
                <!-- Student number -->
                <div class="form-group">
                  <label for="student_number">{{ trans('plugins/member::dashboard.student_number') }}</label>
                  <input type="text" class="form-control" id="student_number" disabled="disabled" required
                         value="{{ $user->student_number ?? 'no value'}}">
                </div>
                <input type="file" id="uploadFile1" accept="image/gif, image/jpeg, image/png" data-add-image=""
                       name="freshman1" value="" hidden>
                <input type="file" id="uploadFile2" accept="image/gif, image/jpeg, image/png" data-add-image=""
                       name="freshman2" value="" hidden>
                <div style="display: flex;justify-content: space-around">
                  <div>
                    <button type="submit"
                            class="btn default-btn fw6">{{ trans('plugins/member::dashboard.save') }}</button>
                  </div>
                  <div>
                    <button type="button" class="btn default-btn fw6" onclick="location.href='{{route('home.index')}}'">
                      취소
                    </button>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('plugins.member::modals.avatar')
  </div>
@endsection
@push('scripts')
  <!-- Laravel Javascript Validation -->
  <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

  {!! JsValidator::formRequest(\Botble\Member\Http\Requests\SettingRequest::class); !!}

  <script type="text/javascript">
    // index => month [0-11]
    let numberDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    $(document).ready(function () {
      // Init form select
      initSelectBox();
    });

    function initSelectBox() {
      let oldBirthday = '{{ $user->dob }}';
      let selectedDay = '';
      let selectedMonth = '';
      let selectedYear = '';

      if (oldBirthday !== '') {``
        selectedDay = parseInt(oldBirthday.substr(8, 2));
        selectedMonth = parseInt(oldBirthday.substr(5, 2));
        selectedYear = parseInt(oldBirthday.substr(0, 4));
      }

      let dayOption = `<option value="">{{ trans('plugins/member::dashboard.day_lc') }}</option>`;
      for (let i = 1; i <= numberDaysInMonth[0]; i++) { //add option days
        if (i === selectedDay) {
          dayOption += `<option value="${i}" selected>${i}</option>`;
        } else {
          dayOption += `<option value="${i}">${i}</option>`;
        }
      }
      $('#day').append(dayOption);

      let monthOption = `<option value="">{{ trans('plugins/member::dashboard.month_lc') }}</option>`;
      for (let j = 1; j <= 12; j++) {
        if (j === selectedMonth) {
          monthOption += `<option value="${j}" selected>${j}</option>`;
        } else {
          monthOption += `<option value="${j}">${j}</option>`;
        }
      }
      $('#month').append(monthOption);

      let d = new Date();
      let yearOption = `<option value="">{{ trans('plugins/member::dashboard.year_lc') }}</option>`;
      for (let k = d.getFullYear(); k >= 1918; k--) {// years start k
        if (k === selectedYear) {
          yearOption += `<option value="${k}" selected>${k}</option>`;
        } else {
          yearOption += `<option value="${k}">${k}</option>`;
        }
      }
      $('#year').append(yearOption);
    }

    function isLeapYear(year) {
      year = parseInt(year);
      if (year % 4 !== 0) {
        return false;
      }
      if (year % 400 === 0) {
        return true;
      }
      if (year % 100 === 0) {
        return false;
      }
      return true;
    }

    function changeYear(select) {
      if (isLeapYear($(select).val())) {
        // Update day in month of leap year.
        numberDaysInMonth[1] = 29;
      } else {
        numberDaysInMonth[1] = 28;
      }

      // Update day of leap year.
      let monthSelectedValue = parseInt($('#month').val());
      if (monthSelectedValue === 2) {
        let day = $('#day');
        let daySelectedValue = parseInt($(day).val());
        if (daySelectedValue > numberDaysInMonth[1]) {
          daySelectedValue = null;
        }

        $(day).empty();

        let option = `<option value="">{{ trans('plugins/member::dashboard.day_lc') }}</option>`;
        for (let i = 1; i <= numberDaysInMonth[1]; i++) { //add option days
          if (i === daySelectedValue) {
            option += `<option value="${i}" selected>${i}</option>`;
          } else {
            option += `<option value="${i}">${i}</option>`;
          }
        }

        $(day).append(option);
      }
    }

    function changeMonth(select) {
      let day = $('#day');
      let daySelectedValue = parseInt($(day).val());
      let month = 0;

      if ($(select).val() !== '') {
        month = parseInt($(select).val()) - 1;
      }

      if (daySelectedValue > numberDaysInMonth[month]) {
        daySelectedValue = null;
      }

      $(day).empty();

      let option = `<option value="">{{ trans('plugins/member::dashboard.day_lc') }}</option>`;

      for (let i = 1; i <= numberDaysInMonth[month]; i++) { //add option days
        if (i === daySelectedValue) {
          option += `<option value="${i}" selected>${i}</option>`;
        } else {
          option += `<option value="${i}">${i}</option>`;
        }
      }

      $(day).append(option);
    }
  </script>

  <script>
    $(document).ready(function () {
      function filePreview1(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('.freshman-view-1 .br2').attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
        }
      }

      $('.freshman-view-1').click(function () {
        $('#uploadFile1').trigger('click');
      });

      $('#uploadFile1').change(function () {
        filePreview1(this);
      });

      function filePreview2(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('.freshman-view-2 .br2').attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
        }
      }

      $('.freshman-view-2').click(function () {
        $('#uploadFile2').trigger('click');
      });

      $('#uploadFile2').change(function () {
        filePreview2(this);
      });

      $('#hide-button').click(() => {
        $('#hide-button').hide();
        $('#level-block').hide();
        $('#show-button').show();
      })

      $('#show-button').click(() => {
        $('#hide-button').show();
        $('#level-block').show();
        $('#show-button').hide();
      })
    });
  </script>
@endpush
