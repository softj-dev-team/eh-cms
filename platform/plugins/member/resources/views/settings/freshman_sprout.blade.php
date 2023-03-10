@switch($user->status_fresh1)
  @case(1)
  <div class="avatar-upload-container">
    <div class="form-group">
      <label for="freshman_1">{{ trans('plugins/member::dashboard.freshman_1') }}</label>
      <div id="freshman_1">
        <div class="profile-image">
          <div class="mt-card-avatar">
            <img class="br2" src="{{ $user->freshman1 ?? '/vendor/core/images/placeholder.png' }}"
                 style="width: 200px;">
            <div class="mt-overlay br2" style="opacity: 1; cursor: initial">
              <span style="display: block; top: 20%">Waiting accept by admin</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- freshman1 -->
  <div class="form-group">
    <label for="note_freshman1">{{ trans('plugins/member::dashboard.name') }}</label>
    <input type="text" class="form-control" id="note_freshman1" disabled="disabled"
           value="{{ old('note_freshman1') ?? $user->note_freshman1 }}">
  </div>

  <button type="button" class="btn default-btn fw6" onclick="cancelRequest('fresh1')" style="margin-bottom: 15px;">
    {{ trans('plugins/member::dashboard.cancel_request') }}
  </button>
  @break
  @case(2)
  <div class="avatar-upload-container">
    <div class="form-group">
      <label for="freshman_1">{{ trans('plugins/member::dashboard.freshman_1') }}</label>

      <div id="freshman_1" style="pointer-events: none;">
        <div class="profile-image">
          <div class="freshman-view-1 mt-card-avatar">
            <img class="br2" src="{{ $user->freshman1 ?? '/vendor/core/images/placeholder.png' }}"
                 style="width: 200px;">
            <div class="mt-overlay br2" style="opacity: 1; display: table">
              <span style="display: table-cell; vertical-align: middle">
                {{ trans('plugins/member::dashboard.freshman_1_accepted')}}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div style="margin-top: 15px">
        <button type="button" class="btn default-btn fw6" onclick="deleteImage('fresh1')">
          인증 서류 삭제
        </button>
      </div>

    </div>
    <!-- Print messages -->
    <div id="print-msg" class="alert dn"></div>
  </div>
  <!-- freshman1 -->
  <div class="form-group">
    <label for="note_freshman1">{{ trans('plugins/member::dashboard.name') }}</label>
    <input type="text" class="form-control" id="note_freshman1" disabled="disabled" required
           value="이화 인증이 완료되었습니다">
  </div>
  @break
  @case(3)
  <div class="avatar-upload-container">
    <div class="form-group">
      <label for="freshman_1">{{ trans('plugins/member::dashboard.freshman_1') }}</label>
      <div id="freshman_1">
        <div class="profile-image">
          <div class="freshman-view-1 mt-card-avatar">
            <img class="br2" src="{{ $user->freshman1 ?? '/vendor/core/images/placeholder.png' }}"
                 style="width: 200px;">
            <div class="mt-overlay br2" style="opacity: 1; display: table">
              <span style="display: table-cell; vertical-align: middle">
                인증 신청이 거절되었습니다.
                <i class="fa fa-edit"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="reason_reject_1">{{ trans('plugins/member::dashboard.reason_reject') }}</label>
        <input type="text" class="form-control" id="reason_reject_1" disabled="disabled"
               value="{{ $user->reason_reject_1}}">
      </div>

      <div style="margin-top: 15px">
        <button type="button" class="btn default-btn fw6" onclick="deleteImage('fresh1')">
          인증 서류 삭제
        </button>
      </div>
    </div>
    <!-- Print messages -->
    <div id="print-msg" class="alert dn"></div>
  </div>
  <!-- freshman1 -->
  <div class="form-group">
    <label for="note_freshman1">{{ trans('plugins/member::dashboard.name') }}</label>
    <input type="text" class="form-control" id="note_freshman1" name="note_freshman1" required
           value="{{ old('note_freshman1') ?? $user->note_freshman1 }}">
  </div>
  @break
  @default
  <div class="avatar-upload-container">
    <div class="form-group">
      <label
        for="freshman_1">{{ trans('plugins/member::dashboard.freshman_1') }}</label>
      <div id="freshman_1">
        <div class="profile-image">
          <div class="freshman-view-1 mt-card-avatar">
            <img class="br2" src="/vendor/core/images/placeholder.png"
                 style="width: 200px;">
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

  <!-- freshman1 -->
  <div class="form-group">
    <label for="note_freshman1">{{ trans('plugins/member::dashboard.name') }}</label>
    <input type="text" class="form-control" id="note_freshman1" name="note_freshman1"
           placeholder="{{ trans('plugins/member::dashboard.name') }}" required="required"
           value="{{ old('note_freshman1') ?? $user->note_freshman1 }}">
  </div>
  @break
@endswitch

<div class="form-group">
  <label for="note_freshman1">{{ trans('plugins/member::dashboard.sprouts_number') }}</label>
  <input type="text" class="form-control" id="sprouts_number" name="sprouts_number"
         placeholder="{{ trans('plugins/member::dashboard.sprouts_number') }}" required="required"
         value="{{ old('sprouts_number') ?? $user->sprouts_number }}">
</div>

<input type="file" id="uploadFile1" accept="image/gif, image/jpeg, image/png"
       data-add-image="" name="freshman1" value="" hidden>

@if( $user->status_fresh1 == 0 || $user->status_fresh1 == 3 )
  <div style="display: flex; justify-content: space-around">
    <div>
      <button type="submit" class="btn default-btn fw6">
        {{ trans('plugins/member::dashboard.save') }}
      </button>
    </div>
    <div>
      <button type="button" class="btn default-btn fw6" onclick="location.href='{{route('home.index')}}'">
        취소
      </button>
    </div>
  </div>
@endif

