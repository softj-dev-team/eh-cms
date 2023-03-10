@switch($user->status_fresh2)
  @case(1)
  <div class="avatar-upload-container">
    <div class="form-group">
      <label
        for="freshman_2">{{ trans('plugins/member::dashboard.freshman_2') }}</label>
      <div id="freshman_2">
        <div class="profile-image">
          <div class="mt-card-avatar">
            <img class="br2"
                 src="{{ $user->freshman2 ?? '/vendor/core/images/placeholder.png' }}"
                 >
            <div class="mt-overlay br2" style="opacity: 1; cursor: initial">
              <span style="display: block;">Waiting accept by admin</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- note_freshman2 -->
  <div class="form-group">
    <label for="note_freshman2">{{ trans('plugins/member::dashboard.name') }}</label>
    <input type="text" class="form-control" id="note_freshman2" disabled="disabled" required
           value="{{ old('note_freshman2') ?? $user->note_freshman2 }}">
  </div>
  <!-- auth_studentID -->
  <div class="form-group">
    <label for="auth_studentid">{{ trans('plugins/member::dashboard.student_number') }}</label>
    <input type="text" class="form-control" id="auth_studentid" disabled="disabled"
           value="{{ old('auth_studentid') ?? $user->auth_studentid }}">
  </div>

  <button type="button" class="btn default-btn fw6" onclick="cancelRequest('fresh2')">
    {{ trans('plugins/member::dashboard.cancel_request') }}
  </button>
  @break
  @case(2)
  <div class="avatar-upload-container">
    <div class="form-group">
      <label for="freshman_2">{{ trans('plugins/member::dashboard.freshman_2') }}</label>

      <div id="freshman_2" style="pointer-events: none;">
        <div class="profile-image">
          <div class="freshman-view-2 mt-card-avatar">
            <img class="br2" src="{{ $user->freshman2 ?? '/vendor/core/images/placeholder.png' }}"
            >
            <div class="mt-overlay br2" style="opacity: 1;">
              <span style="display: block;">
              {{ trans('plugins/member::dashboard.freshman_2_accepted')}}
            </div>
          </div>
        </div>
      </div>

      <div style="margin-top: 15px">
        <button type="button" class="btn default-btn fw6 btn-mo-100" onclick="deleteImage('fresh2')">
          인증 서류 삭제
        </button>
      </div>
    </div>
    <!-- Print messages -->
    <div id="print-msg" class="alert dn"></div>
  </div>
  <div class="form-group">
    <label for="note_freshman2">{{ trans('plugins/member::dashboard.name') }}</label>
    <input type="text" class="form-control" id="note_freshman2" required disabled="disabled"
           value="이화 인증이 완료되었습니다 ">
  </div>
  <!-- auth_studentID -->
  <div class="form-group">
    <label for="auth_studentid">{{ trans('plugins/member::dashboard.student_number') }}</label>
    <input type="text" class="form-control" id="auth_studentid" disabled="disabled" value="이화 인증이 완료되었습니다 ">
  </div>
  @break
  @case(3)
  <div class="avatar-upload-container">
    <div class="form-group">
      <label for="freshman_2">{{ trans('plugins/member::dashboard.freshman_2') }}</label>
      <div id="freshman_2">
        <div class="profile-image">
          <div class="freshman-view-2 mt-card-avatar">
            <img class="br2" src="{{ $user->freshman2 ?? '/vendor/core/images/placeholder.png' }}"
                >
            <div class="mt-overlay br2" style="opacity: 1;">
              <span style="display: block;">
                인증 신청이 거절되었습니다.
                <i class="fa fa-edit"></i></span>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="reason_reject_2">{{ trans('plugins/member::dashboard.reason_reject') }}</label>
        <input type="text" class="form-control" id="reason_reject_2" disabled="disabled"
               value="{{ $user->reason_reject_2}}">
      </div>

      <div style="margin-top: 15px">
        <button type="button" class="btn default-btn fw6 btn-mo-100" onclick="deleteImage('fresh2')">
          인증 서류 삭제
        </button>
      </div>
    </div>
    <!-- Print messages -->
    <div id="print-msg" class="alert dn"></div>
  </div>
  <div class="form-group">
    <label for="note_freshman2">{{ trans('plugins/member::dashboard.name') }}</label>
    <input
      type="text"
      class="form-control"
      id="note_freshman2"
      name="note_freshman2"
      required
      value="{{ old('note_freshman2') ?? $user->note_freshman2 }}"
    >
  </div>
  <!-- auth_studentID -->
  <div class="form-group">
    <label for="auth_studentid">{{ trans('plugins/member::dashboard.student_number') }}</label>
    <input
      type="text"
      class="form-control"
      id="auth_studentid"
      name="auth_studentid"
      required
      value="{{ old('auth_studentid') ?? $user->auth_studentid }}"
      placeholder="{{ trans('plugins/member::dashboard.student_number') }}"
    >
  </div>
  @break
  @default
  <div class="avatar-upload-container">
    <div class="form-group">
      <label
        for="freshman_2">{{ trans('plugins/member::dashboard.freshman_2') }}</label>
      <div id="freshman_2">
        <div class="profile-image">
          <div class="freshman-view-2 mt-card-avatar">
            <img class="br2" src="/vendor/core/images/placeholder.png"
                 >
            <div class="mt-overlay br2">
              <span><i class="fa fa-edit"></i></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Print messages -->
    <div id="print-msg" class="alert dn"></div>
  </div>
  <!-- freshman2 -->
  <div class="form-group">
    <label for="note_freshman2">{{ trans('plugins/member::dashboard.name') }}</label>
    <input
      type="text"
      class="form-control"
      id="note_freshman2"
      name="note_freshman2"
      required
      value="{{ old('note_freshman2') ?? $user->note_freshman2 }}"
      placeholder="{{ trans('plugins/member::dashboard.name') }}"
    >
  </div>
  <!-- auth_studentID -->
  <div class="form-group">
    <label for="auth_studentid">{{ trans('plugins/member::dashboard.student_number') }}</label>
    <input
      type="text"
      class="form-control"
      id="auth_studentid"
      name="auth_studentid"
      required
      value="{{ old('auth_studentid') ?? $user->auth_studentid }}"
      placeholder="{{ trans('plugins/member::dashboard.student_number') }}"
    >
  </div>
  @break
@endswitch

<input type="file" id="uploadFile2" accept="image/gif, image/jpeg, image/png"
       data-add-image="" name="freshman2" value="" hidden>

@if( $user->status_fresh2 == 0 || $user->status_fresh2 == 3)
  <div style="display: flex;justify-content: space-around">
    <div>
      <button type="submit"
              class="btn default-btn fw6">{{ trans('plugins/member::dashboard.save') }}</button>
    </div>
    <div>
      <button type="button" class="btn default-btn fw6"
              onclick="location.href='{{route('home.index')}}'">취소
      </button>
    </div>
  </div>
@endif
