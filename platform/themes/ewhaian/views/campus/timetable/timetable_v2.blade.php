<div class="pretty-split-pane-frame">

        <!-- This div is added for styling purposes only. It's not part of the split-pane plugin. -->
        <div class="split-pane horizontal-percent">

            <div class="split-pane-component" id="top-component" style="z-index: 10;">
                {!! Theme::partial('header') !!}
                <div class="pretty-split-pane-component-inner ">
                        {!! Theme::partial('timetable.top',[
                            'schedule' => $schedule,
                            'scheduleTime' => $scheduleTime,
                            'scheduleDay' => $scheduleDay,
                            'scheduleAll' => $scheduleAll,
                            'description'=>$description,
                            'listFilter' =>$listFilter,
                            'timeline' => $timeline,
                            'timeline1'=> $timeline1,
                            'timeline2'=> $timeline2,
                            'config' => $config,
                            'activeID'=>$activeID,
                            "startSemester" => $startSemester,
                            "endSemester" => $endSemester,
                            "scheduleWeeks" => $scheduleWeeks,
                            "dataSchedules" => $dataSchedules,
                        ]) !!}
                </div>
            </div>
            <div class="split-pane-divider" id="my-divider"></div>
            <div class="split-pane-component" id="bottom-component">
                <div class="pretty-split-pane-component-inner">
                        {!! Theme::partial('timetable.bottom',['categories'=>$categories,'evaluation'=>$evaluation,'schedule' => $schedule]) !!}
                </div>
            </div>
        </div>
</div>
<!-- Modal -->
<div data-backdrop="static" class="modal fade modal--border modal-ewha" id="schedulePopup" tabindex="-1"
role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-md " role="document">
    <div class="modal-content">
        <div class="modal-body">
            <form action="{{route('scheduleFE.create')}}" method="post">
                <input type="hidden" name="curSem" value="{{$activeID}}" />
                @csrf
                <h3 class="text-bold mt-2 py-3 text-center">{{__('campus.timetable.create_new_schedule')}}</h3>
                <div class="form-group form-group--1 mb-4">
                    <label for="scheduleName" class="form-control">
                        <input type="text" required id="scheduleName" name="name" placeholder="&nbsp;">
                        <span class="form-control__label">{{__('campus.timetable.schedule_name')}}<span
                                class="required">*</span></span>
                    </label>
                </div>
                <span class="text-bold mr-3" style="display: none;">
                    {{__('campus.timetable.application_period')}}
                    <span class="required">*</span>
                </span>
                <div class="flex-grow-1 mb-4" style="display: none;">
                    <div class="d-flex align-items-center">
                        <div class="form-group form-group--search  flex-grow-1 mr-1">
                            <span class="form-control__icon form-control__icon--gray">
                                <svg width="20" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_calender">
                                    </use>
                                </svg>
                            </span>
                            {{-- <input data-datepicker-start type="text" class="form-control startDate"
                                id="startDate" name="start" value="{{getToDate(1) }} " autocomplete="off"> --}}
                            <input data-datepicker-start type="text" class="form-control startDate"
                                id="startDate" name="start" value="{{$startSemester }} " autocomplete="off">
                        </div>
                        <span class="filter__connect">~</span>
                        <div class="form-group form-group--search  flex-grow-1 ml-1">
                            <span class="form-control__icon form-control__icon--gray">
                                <svg width="20" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_calender">
                                    </use>
                                </svg>
                            </span>
                            {{-- <input data-datepicker-end type="text" class="form-control endDate" id="endDate"
                                name="end" value="{{  getToDate(1) }} " autocomplete="off"> --}}
                            <input data-datepicker-end type="text" class="form-control endDate" id="endDate"
                                name="end" value="{{  $endSemester }} " autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="button-group my-4 text-center">
                    <button type="button" class="btn btn-outline mr-lg-10"
                        data-dismiss="modal">{{__('campus.timetable.cancel')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('campus.timetable.create')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- <div class="layer-modal">

</div> --}}
</div>
<!-- Modal-->

{{-- Add Timeline --}}
<div data-backdrop="static" class="modal fade modal--border modal-ewha" id="timelinePopup" tabindex="-1"
role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md " role="document">
      <div class="modal-content">
          <div class="modal-body">
              <form id="form-add-timeline" action="{{route('scheduleFE.timeline.create')}}" method="post">
                  @csrf
                  <input type="hidden" name="schedule_id" value="{{$schedule->id ?? 0}}">
                  <div class="modal__header mt-2 py-3 ">
                      <h3 class="text-bold text-center" style="color: #999999">{{__('campus.timetable.add_timeline')}}</h3>
                      <p class="modal__header-description mb-0">__ {{__('campus.timetable.schedule')}} __</p>
                  </div>
                  <div id="form-add-timeline-msg"></div>
                  <div class="form-group form-group--1 mb-4">
                      <label for="courseCode" class="form-control">
                        <input type="text" required id="courseCode" name="course_code" placeholder="&nbsp;">
                        <span class="form-control__label">{{__('campus.timetable.course_code')}}<span
                            class="required">*</span></span>
                      </label>
                      <div id="error-add-timeline-course_code" class="error-add-timeline"></div>
                  </div>
                  <div class="form-group form-group--1 mb-4">
                    <label for="division" class="form-control">
                      <input type="text"  id="division" name="division" placeholder="&nbsp;">
                      <span class="form-control__label">{{__('campus.timetable.division')}}</span>
                    </label>
                  </div>
                  <div class="form-group form-group--1 mb-4">
                      <label for="subjectName" class="form-control">
                          <input type="text" required id="subjectName" name="title" placeholder="&nbsp;">
                          <span class="form-control__label">{{__('campus.timetable.subject_name')}}<span
                                  class="required">*</span></span>
                      </label>
                      <div id="error-add-timeline-title" class="error-add-timeline"></div>
                  </div>
                  <div class="form-group form-group--1 mb-4">
                      <label for="professorName" class="form-control">
                        <input type="text" required id="professorName" name="professor_name"
                            placeholder="&nbsp;">
                        <span class="form-control__label">{{__('campus.timetable.professor_name')}}<span
                                class="required">*</span></span>
                      </label>
                      <div id="error-add-timeline-professor_name" class="error-add-timeline"></div>
                  </div>
                  <div class="form-group form-group--1 mb-4">
                      <label for="lectureRoom" class="form-control">
                          <input type="text" required id="lectureRoom" name="lecture_room" placeholder="&nbsp;">
                          <span class="form-control__label">{{__('campus.timetable.lecture_room')}}<span
                                  class="required">*</span></span>
                      </label>
                  </div>
                  <div class="form-group">
                      <div data-template="" class="data-origin-template d-none">
                          <div class="mb-2">
                              <input type="hidden" class="template_id" value="#numberTemplate">
                              <div class="d-flex align-items-center">
                                  <span class="upload-file__name flex-grow-1" data-file-type="#fileType">{{__('campus.timetable.on')}}</span>
                                  <span class="upload-file__name flex-grow-1" data-file-type="#fileType">{{__('campus.timetable.from')}}</span>
                                  <span class="upload-file__name flex-grow-1" data-file-type="#fileType">{{__('campus.timetable.to')}}</span>
                                  <button class="btn btn-primary btn-icon mr-3 data-remove-file" data-remove-file type="button">
                                      <i class="fas fa-minus-square"></i>
                                  </button>
                              </div>
                              <div class="d-flex align-items-center">
                                  <div class="flex-grow-1">
                                      <input class="form-control" name="datetime[#template_day][day]" value="#dayValue">
                                  </div>
                                  <div class="flex-grow-1">
                                      <input class="form-control" name="datetime[#template_from][from]" value="#fromValue">
                                  </div>
                                  <div class="flex-grow-1">
                                      <input class="form-control" name="datetime[#template_to][to]" value="#toValue">
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div data-uploaded-content="" class="data-clone-child ">

                      </div>
                  </div>
                  <div class="row">
                      <div class="col">
                          <div class="form-group form-group--select">
                              <label class="form-control__label">{{__('campus.timetable.on')}} <span
                                      class="required">*</span></label>
                              <select class="form-control form-control--select" data-select-day style="height:30px;">
                                  @foreach ($scheduleDay as $item)
                                  <option value="{{$item->name}}">{{ ucfirst ($item->name ) }}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col">
                          <div class="form-group form-group--select">
                              <label class="form-control__label">{{__('campus.timetable.from')}} <span
                                      class="required">*</span></label>
                              <select class="form-control form-control--select" data-select-from style="height:30px;">
                                  @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
                                      <option value="{{$i}}">{{$i.':00'}}</option>
                                      <option value="{{$i+0.5}}">{{$i.':30'}}</option>
                                      @endfor
                              </select>
                          </div>
                      </div>
                      <div class="col">
                          <div class="form-group form-group--select">
                              <label class="form-control__label">{{__('campus.timetable.to')}} <span
                                      class="required">*</span></label>
                              <select class="form-control form-control--select" data-select-to style="height:30px;">
                                  @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
                                      <option value="{{$i+0.5}}">{{$i.':30'}}</option>
                                      <option value="{{$i+1}}">{{$i+1}}:00</option>
                                      @endfor
                              </select>
                          </div>
                      </div>
                      <div clas="col">
                          <button id="btn-add-datetime" class="btn btn-primary btn-icon mt-2" data-add-link-schedule type="button">
                              <i class="fas fa-plus-square"></i>
                          </button>
                      </div>
                  </div>
                  <div id="error-add-timeline-datetime" class="error-add-timeline"></div>
                  <div id="datetime-error" class="invalid-feedback"></div>
                  <div class="button-group my-4 text-center">
                      <button type="button" class="btn btn-outline mr-lg-10"
                          data-dismiss="modal">{{__('campus.timetable.cancel')}}</button>
                      <button type="submit" id="btn-add-timeline" class="btn btn-primary">{{__('campus.timetable.create')}}</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
  {{-- <div class="layer-modal">
  </div> --}}
</div>

<!-- Modal -->
<div data-backdrop="static" class="modal fade modal--confirm modal-ewha" id="confirmPopup" tabindex="-1"
role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header align-items-center justify-content-lg-center">
              <span class="modal__key">
                  <svg width="40" height="18" aria-hidden="true">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
                  </svg>
              </span>
          </div>
          <div class="modal-body">
              <div class="d-lg-flex align-items-center mx-3">
                  <div class="d-lg-flex align-items-start flex-grow-1">
                      <div class="form-group form-group--1 flex-grow-1 mr-lg-30 mb-3">
                          <label for="hint" class="form-control">
                              <input type=" text" id="hint" value="{{__('campus.timetable.hint')}}"
                                  placeholder="&nbsp;" readonly>
                              {{-- <span class="form-control__label"> Hint</span> --}}
                          </label>
                      </div>
                      <form action="{{route('gardenFE.passwd')}}" method="post" target="_parent">
                          @csrf
                          <div class="form-group form-group--1 flex-grow-1 mb-3">
                              <label for="password" class="form-control form-control--hint">
                                  <input type="password" id="password" name="password" placeholder="&nbsp;"
                                      value="{{Cookie::get('password_garden') ?? ''}}" maxlength="16" required>
                                  <span class="form-control__label"> {{__('campus.timetable.set_password')}}
                                  </span>
                              </label>
                              <span
                                  class="form-control__hint">{{__('campus.timetable.up_to_16_characters')}}</span>
                          </div>
                  </div>
                  <div class="button-group mb-2">
                      <button type="button" class="btn btn-outline mr-lg-10"
                          data-dismiss="modal">{{__('campus.timetable.cancel')}}</button>
                      <button type="submit" class="btn btn-primary">{{__('campus.timetable.save')}}</button>
                  </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
  {{-- <div class="layer-modal">

  </div> --}}
</div>
<!-- Modal -->
<div data-backdrop="false" class="modal fade modal--border modal-ewha" id="lecturePopup" tabindex="-1" role="dialog"
aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg " role="document" style="max-width: 1000px">

    <div class="modal-content">
        {{-- <button type="button" class="close close-button topright" data-dismiss="modal" style="width: 40px;height: 40px;font-size: 40px;">&times;</button> --}}
        <div class="modal-body">
            <div>
                <iframe id="lecture" src="" style="width: 920px;height: 500px"></iframe>
            </div>
        </div>
    </div>
</div>
{{-- <div class="layer-modal">

</div> --}}
</div>
<!-- Modal-->

<script>
  let form = $('#form-add-timeline');
  let btnAddTimeline = $('#btn-add-timeline');
  form.submit(function(e) {
    e.preventDefault();
    const courseCodeErr = '{{ __("validation.numeric") }}';
    const courseCode = form.find($('input[name="course_code"]')).val();
    $('#form-add-timeline-msg').html('');
    $('.error-add-timeline').html('');
    if (isNaN(+courseCode)) {
      const htmlError = `<div class="alert alert-danger alert-dismissible d-block">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              `+ courseCodeErr +`
            </div>`;
      $('#error-add-timeline-course_code').html(htmlError);
      return false;
    }

    $.ajax({
      type: 'POST',
      url: form.attr('action'),
      data: form.serialize(),
      beforeSend: function() {
        // btnAddTimeline.attr('disabled', true);
      }
    }).done(function (response) {
        if (typeof response.message != 'undefined') {
          const htmlSuccess = `<div class="alert alert-success alert-dismissible d-block">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              `+ response.message +`
            </div>`;
          $('#form-add-timeline-msg').html(htmlSuccess);
          setTimeout(function () {
              window.location.reload();
          }, 500);
        }
    }).fail(function (response) {
        if (response.status == 422) {
          const formErrorMsg = '{{ __("campus.timetable.add_timeline_form_error") }}';
          const formError = `<div class="alert alert-danger alert-dismissible d-block">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                `+ formErrorMsg +`
              </div>`;

          let errors = $.parseJSON(response.responseText);
          if (typeof errors.field != 'undefined') {
            const htmlError = `<div class="alert alert-danger alert-dismissible d-block">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                `+ errors.message +`
              </div>`;
            $('#error-add-timeline-' + errors.field).html(htmlError);
            $('#form-add-timeline-msg').html(formError);
          } else {
            let errors = $.parseJSON(response.responseText);
            $.each(errors, function(index, msgs) {
              $.each(msgs, function(key, val) {
                $('#error-add-timeline-' + key).html(val[0]);
              });
            });
            $('#form-add-timeline-msg').html(formError);
          }

          btnAddTimeline.attr('disabled', false);
        }
      });
  });
</script>
