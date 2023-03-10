<main id="main-content" data-view="timetable" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        {!! Theme::partial('campus.menu',['active'=>"timetable"]) !!}
        <div class="sidebar-template__content">
          <ul class="breadcrumb">
            <li><a href="{{route('campus.evaluation_comments_major')}}" title="Event">Campus</a></li>
            <li>
              <svg width="4" height="6" aria-hidden="true" class="icon">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
              </svg>
            </li>
            <li>Timetable</li>
          </ul>
          <div class="heading" style="display: flex;">
            <div class="heading__title" style="white-space: nowrap;">
                Timetable
            </div>
            <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                {!!$description->description ?? ""!!}
            </div>
        </div>
          <div class="content">
            @if( !is_null($schedule) )
            <div class="timetable" data-timetable="data-timetable"
              data-json='{{  json_encode( $schedule->timeline()->select('id','title','day','from','to')->where('status','publish')->get() ) }}'
              data-config='{{json_encode( $scheduleTime)}}'>
              <div class="timetable__schedule-list d-flex flex-wrap mb-3 pb-3">
                <div class="flex-grow-1">
                  <a href="#" title="Schedule 1" class="btn btn-primary btn-icon" data-toggle="modal"
                    data-target="#schedulePopup">
                    <svg width="12.121" height="15.121" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_mini"></use>
                    </svg>
                  </a>
                  @foreach ($scheduleAll as $item)
                    <a href="{{route('scheduleFE.details',['id'=>$item->id])}}" title=" {{$item->name}}" class="@if($item->id == $schedule->id) btn btn-primary @else  btn btn-secondary @endif ">
                        {{$item->name}}
                      </a>
                  @endforeach
                </div>
                {{-- <div class="timetable__btn-group">
                  <button class="btn btn-default btn-icon">
                    <svg width="17" height="17" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_setting"></use>
                    </svg>
                  </button>
                  <button class="btn btn-default btn-icon">
                    <svg width="18" height="15.781" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
                    </svg>
                  </button>
                </div> --}}
              </div>
              @if (session('err'))
              <div class="alert alert-danger" style="width:100% ; justify-content: center;">
                {{ session('err') }}
              </div>
              @endif

              @if (session('success'))
              <div class="alert alert-success"  style="width:100%;justify-content: center;">
                {{ session('success') }}
              </div>
              @endif
              <div class="timetable__content">
                <div class="d-flex align-items-center">
                  <div class="timetable__header flex-grow-1">
                    <h3>{{$schedule->name}}</h3>
                    <div class="timetable__time">
                      <span class="timetable__circle">
                        <svg width="10" height="13" aria-hidden="true" class="icon">
                          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_datetime"></use>
                        </svg>
                      </span>
                     {{ date('d M Y', strtotime($schedule->start))}} - {{ date('d M Y', strtotime($schedule->end))}}
                    </div>
                  </div>
                  <div class="timetable__actions">
                    <a href="#" title="Add timeline" class="btn btn-default" data-toggle="modal"
                      data-target="#timelinePopup">
                      <svg width="17" height="17" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_timeline"></use>
                      </svg>
                      <br>
                      Add timeline
                    </a>
                    <a href="#" title="Add timeline" class="btn btn-default" data-toggle="modal" data-target="#timelinePopupRemove">
                      <svg width="15.833" height="17" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_remove"></use>
                      </svg>
                      <br>
                      Remove
                    </a>
                    <a href="#" title="Add timeline" class="btn btn-default" data-toggle="modal" data-target="#timelinePopupCopy">
                      <svg width="13.305" height="17" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_document"></use>
                      </svg>
                      <br>
                      Copy
                    </a>
                    {{-- <a href="#" title="Add timeline" class="btn btn-default">
                      <svg width="15.849" height="17" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_share"></use>
                      </svg>
                      <br>
                      Share
                    </a> --}}
                  </div>
                </div>
                <div class="timetable__content-schedule" style="overflow: auto">
                <div class="scrollingBox" style="width: 833px;overflow-x: auto;">
                    <ul class="timetable__content-schedule__header d-flex align-items-center justify-content-around">
                        @foreach ($scheduleDay as $item)
                            <li>{{ ucfirst( $item->name ) }}</li>
                        @endforeach
                    </ul>
                    <div class="d-flex align-items-center">
                        <div class="d-flex flex-column align-items-center">
                           @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
                           <div class="timetable__content-number">{{$i}}</div>
                           @endfor
                        </div>
                        <div class="d-flex flex-grow-1">
                        <div class="row mx-0 flex-grow-1">
                            @foreach ($scheduleDay as $item)
                                <div class="col px-0" data-day="{{$item->name}}">
                                        @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i = $i + $scheduleTime->unit)
                                            <div class="timetable__content-day" data-start="{{$i}}"></div>
                                        @endfor
                                </div>
                            @endforeach
                        </div>
                        </div>

                    </div>
                </div>
                </div>
              </div>

            </div>
            @else
            @if (session('err'))
            <div class="alert alert-danger" style="width:100% ; justify-content: center;">
              {{ session('err') }}
            </div>
            @endif

            @if (session('success'))
            <div class="alert alert-success"  style="width:100%;justify-content: center;">
              {{ session('success') }}
            </div>
            @endif
            <div class="timetable">
              <div class="timetable__schedule-list d-flex flex-wrap mb-3 pb-3">
                <div class="flex-grow-1">
                  <a href="#" title="Schedule 1" class="btn btn-primary btn-icon" data-toggle="modal"
                    data-target="#schedulePopup">
                    <svg width="12.121" height="15.121" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_mini"></use>
                    </svg>
                  </a>
                    {{-- @foreach ($scheduleAll as $key => $item)
                    <a href="{{route('scheduleFE.details',['id'=>$item->id])}}" title=" {{$item->name}}" class="@if($key == 0 ) btn btn-primary @else  btn btn-secondary @endif ">
                        {{$item->name}}
                    </a>
                    @endforeach --}}
                </div>
                {{-- <div class="timetable__btn-group">
                  <button class="btn btn-default btn-icon">
                    <svg width="17" height="17" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_setting"></use>
                    </svg>
                  </button>
                  <button class="btn btn-default btn-icon">
                    <svg width="18" height="15.781" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
                    </svg>
                  </button>
                </div> --}}
              </div>
              <div class="timetable__content">
                <div class="d-flex align-items-center">
                  <div class="timetable__header flex-grow-1">
                    <h3>No have Schedule</h3>
                  </div>
                </div>
              </div>

            </div>
            @endif
          </div>

        </div>

      </div>

    </div>
    <!-- Modal -->
    <div class="modal fade modal--border" id="schedulePopup" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md " role="document">
        <div class="modal-content">
          <div class="modal-body">
            <form action="{{route('scheduleFE.create')}}" method="post">
                @csrf
            <h3 class="text-bold mt-2 py-3 text-center">Create New Schedule</h3>
            <div class="form-group form-group--1 mb-4">
              <label for="scheduleName" class="form-control">
                <input type="text" required id="scheduleName" name="name" placeholder="&nbsp;">
                <span class="form-control__label"> Schedule name <span class="required">*</span></span>
              </label>
            </div>
            <span class="text-bold mr-3">
              Application period
              <span class="required">*</span>
            </span>
            <div class="flex-grow-1 mb-4">
              <div class="d-flex align-items-center">
                <div class="form-group form-group--search  flex-grow-1 mr-1">
                  <span class="form-control__icon form-control__icon--gray">
                    <svg width="20" height="17" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_calender"></use>
                    </svg>
                  </span>
                  <input data-datepicker-start type="text" class="form-control startDate" id="startDate" name="start" value="{{getToDate(1) }} " autocomplete="off">
                </div>
                <span class="filter__connect">~</span>
                <div class="form-group form-group--search  flex-grow-1 ml-1">
                  <span class="form-control__icon form-control__icon--gray">
                    <svg width="20" height="17" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_calender"></use>
                    </svg>
                  </span>
                  <input data-datepicker-end type="text" class="form-control endDate" id="endDate" name="end" value="{{  getToDate(1) }} " autocomplete="off">
                </div>
              </div>
            </div>
            <div class="button-group my-4 text-center">
              <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Create</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->

    @if( !is_null($schedule) )

    {{-- Add Timeline --}}
    <div class="modal fade modal--border" id="timelinePopup" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <form action="{{route('scheduleFE.timeline.create')}}" method="post">
            @csrf
        <input type="hidden" name="schedule_id" value="{{$schedule->id ?? 0}}">
        <div class="modal-dialog modal-dialog-centered modal-md " role="document">
            <div class="modal-content">
            <div class="modal-body">
                <div class="modal__header mt-2 py-3 ">
                <h3 class="text-bold text-center">Add Timeline</h3>
                <p class="modal__header-description mb-0">__ Schedule __</p>
                </div>
                <div class="form-group form-group--1 mb-4">
                <label for="subjectName" class="form-control">
                    <input type="text" required id="subjectName" name="title" placeholder="&nbsp;">
                    <span class="form-control__label"> Subject name <span class="required">*</span></span>
                </label>
                </div>
                <div class="row">
                <div class="col">
                    <div class="form-group form-group--select">
                    <label class="form-control__label">On <span class="required">*</span></label>
                    <select class="form-control form-control--select" name="day">
                        @foreach ($scheduleDay as $item)
                            <option value="{{$item->name}}">{{ ucfirst ($item->name ) }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group form-group--select">
                    <label class="form-control__label">From <span class="required">*</span></label>
                    <select class="form-control form-control--select" name="from">
                            @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
                                <option value="{{$i}}" >{{$i.':00'}}</option>
                                <option value="{{$i+0.5}}" >{{$i.':30'}}</option>
                            @endfor
                    </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group form-group--select">
                    <label class="form-control__label">To <span class="required">*</span></label>
                    <select class="form-control form-control--select" name="to">
                            @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
                                <option value="{{$i}}" >{{$i.':00'}}</option>
                                <option value="{{$i+0.5}}" >{{$i.':30'}}</option>
                             @endfor
                    </select>
                    </div>
                </div>
                </div>
                <div class="button-group my-4 text-center">
                <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
            </div>
        </div>
        </form>
    </div>
    {{-- Remove Time Line --}}
    <div class="modal fade modal--border" id="timelinePopupRemove" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <form action="{{route('scheduleFE.timeline.delete')}}" method="post">
        @csrf
        <input type="hidden" name="schedule_id" value="{{$schedule->id ?? 0}}">
        <div class="modal-dialog modal-dialog-centered modal-md " role="document">
            <div class="modal-content">
            <div class="modal-body">
                <div class="modal__header mt-2 py-3 ">
                <h3 class="text-bold text-center">Remove Timeline</h3>
                <p class="modal__header-description mb-0">__ Schedule __</p>
                </div>
                @if(count($schedule->timeline()->get() ) >0 )
                <div class="form-group form-group--1 mb-4"></div>
                        <div class="row">
                        <div class="col">
                            <div class="form-group form-group--select">
                            <label class="form-control__label">Subject name <span class="required">*</span></label>
                            <select class="form-control form-control--select" name="idScheduleTimeLine">
                                @foreach ($schedule->timeline()->get() as $item)
                                    <option value="{{$item->id}}" >{{'Title : '.$item->title.' - Day : '.ucfirst($item->day)  }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        </div>
                @else
                    <div class="form-group form-group--1 mb-4">Want To Delete This Schedule ?</div>
                @endif
                <div class="button-group my-4 text-center">
                <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Remove</button>
                </div>
            </div>
            </div>
        </div>
        </form>
    </div>

    {{-- Copy Timeline --}}
    <div class="modal fade modal--border" id="timelinePopupCopy" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <form action="{{route('scheduleFE.timeline.create')}}" method="post">
            @csrf
        <input type="hidden" name="schedule_id" value="{{$schedule->id ?? 0}}">
        <div class="modal-dialog modal-dialog-centered modal-md " role="document">
            <div class="modal-content">
            <div class="modal-body">
                <div class="modal__header mt-2 py-3 ">
                <h3 class="text-bold text-center">Copy Timeline</h3>
                <p class="modal__header-description mb-0">__ Schedule __</p>
                </div>
                <div class="form-group form-group--select">
                    <label class="form-control__label">Subject name <span class="required">*</span></label>
                    <select class="form-control form-control--select" name="title">
                        @foreach ($schedule->timeline()->get() as $item)
                            <option value="{{$item->title}}" >{{$item->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                <div class="col">
                    <div class="form-group form-group--select">
                    <label class="form-control__label">On <span class="required">*</span></label>
                    <select class="form-control form-control--select" name="day">
                        @foreach ($scheduleDay as $item)
                            <option value="{{$item->name}}">{{ ucfirst ($item->name ) }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group form-group--select">
                    <label class="form-control__label">From <span class="required">*</span></label>
                    <select class="form-control form-control--select" name="from">
                            @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
                                <option value="{{$i}}" >{{$i.':00'}}</option>
                                <option value="{{$i+0.5}}" >{{$i.':30'}}</option>
                            @endfor
                    </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group form-group--select">
                    <label class="form-control__label">To <span class="required">*</span></label>
                    <select class="form-control form-control--select" name="to">
                            @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
                                <option value="{{$i}}" >{{$i.':00'}}</option>
                                <option value="{{$i+0.5}}" >{{$i.':30'}}</option>
                             @endfor
                    </select>
                    </div>
                </div>
                </div>
                <div class="button-group my-4 text-center">
                <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Copy</button>
                </div>
            </div>
            </div>
        </div>
        </form>
    </div>

    @endif
  </main>
