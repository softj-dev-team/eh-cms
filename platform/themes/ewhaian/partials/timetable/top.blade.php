<style>
    .btn_active {
        background-color: #ec1469;
        color: #fff;
    }

    .layer-modal {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1049;
        width: 100vw;
        height: 100vh;
        background-color: #000;
        opacity: 0;
        visibility: hidden;
        transition: 0.8s ease;
    }

    .modal-ewha .modal-dialog {
        transition: none !important;
    }

    /* .modal-ewha .modal-content {
        z-index: 1051;
    } */

    .close-button {
        border: none;
        display: inline-block;
        padding: 8px 16px;
        vertical-align: middle;
        overflow: hidden;
        text-decoration: none;
        color: red;
        background-color: black;
        text-align: center;
        cursor: pointer;
        white-space: nowrap;
        z-index: 1050;
    }

    .topright {
        position: absolute;
        right: 0;
        top: 0
    }
    .btn-template .btn_active:hover,:focus {
    color: white;
}
.timetable__btn-group .dropdownPanel .isSetting .content:before {
    right: 54px;
}
.dropdown-container-semeter.isSemester{
  width: 300px;
  visibility: visible;
  opacity: 1;
  transform: translateY(0);
  position: absolute;
    top: 50px;
    right: -20px;
    z-index: 20;
    background: #fff;
    border: 1px solid rgba(186, 186, 186, 1);
    border-radius: 12px;
    visibility: hidden;
    opacity: 0;
    transform: translateY(-40px);
    transition: 0.4s ease;
}

.dropdown-container-semeter.isOpen {
    visibility: visible;
    opacity: 1;
    transform: translateY(0);
}


.dropdown-container-semeter.isSemester .content:before {
    content: '';
    position: absolute;
    top: -11px;
    right: 26px;
    width: 20px;
    height: 20px;
    background: #fff;
    border: 1px solid rgba(186, 186, 186, 1);
    border-bottom: none;
    border-right: none;
    transform: rotate(45deg);
    z-index: 1;
}

.semesterTitle{
  height: 50px;
  width: 100%;
  font-size: 14px;
  padding-top: 20px;
  font-weight: bold;
  border-bottom-width: 1px;
  border-bottom-color: rgba(180,180,180, 1);
  border-bottom-style: solid;
}

/* ul.timetable__content-schedule__header.d-flex.align-items-center.justify-content-around li {
    flex: 0 0 16.66667%;
    max-width: 16.66667%;
    text-align: center;
    padding-right: 6px;
} */

.activeSemester{
  font-weight: bold;
  color: #ec1469;
}
.listColor {
  width: 100%;
  padding-left: 0
}
.inline-select[name="config[time][from]"], .inline-select[name="config[time][to]"] {
  margin-right: calc(100% - 200px);
}
.inline-select[name="config[day][from]"] {
  margin-right: 10px
}
.inline-select[name="config[day][to]"] {
  margin-right: calc(100% - 290px);
}

@media (max-width: 576px) {
  .timetable__btn-group .dropdownPanel .dropdown-container {
    width: 350px;
    right: 0;
  }
  .flexPanel {
    padding-top: 5px;
    padding-bottom: 0
  }
}
</style>
<script type="text/x-custom-template" id="template-info-html">
<div class="info-schedule-popup">
    <div class="content">
        <div class="info-head">
            <span></span>
            | <span></span>
            <div class="closeInfo">
                <a href="#"></a>
            </div>
        </div>
        <div class="info-content">
            <ul class="nav">
                <li>
                    <span class="label">추가하기: &nbsp;</span>
                    <span>추가하기</span>
                </li>
                <li>
                    <span class="label">추가하기: &nbsp;</span>
                    <span>추가하기</span>
                </li>
                <li>
                    <span class="label">추가하기: &nbsp;</span>
                    <span>추가하기</span>
                </li>
                <li>
                    <span class="label">추가하기: &nbsp;</span>
                    <span>추가하기</span>
                </li>
            </ul>
        </div>
        <div class="info-bottom">
            <div class="text">
                융복합교양(표현과예술) 융복합교양(표현과예술)융복합교양(표현과예술)
            </div>
            <div class="btn-template">
              <div class="btn__ehw lighter colorPicker">색상</div>
              <div class="btn__ehw lighter">추가하기</div>
            </div>
        </div>
        <div class='selectColor d-none'>
            <div class='top'>
                <div class="title">색상 선택</div>
                <div class="closePanel">
                    <a href="#"></a>
                </div>
            </div>
            <div class="themeColorAllDiv">
                <div class="themeColorList">
                <div class="themeColorBox" data-color='#AAA3FD' style='background-color:#AAA3FD;'></div>
                <div class="themeColorBox" data-color='#8D8AF9' style='background-color:#8D8AF9;'></div>
                <div class="themeColorBox" data-color='#6B6BF2' style='background-color:#6B6BF2;'></div>
                <div class="themeColorBox" data-color='#4A4ADD' style='background-color:#4A4ADD;'></div>
                <div class="themeColorBox" data-color='#2C3591' style='background-color:#2C3591;'></div>
                <div class="themeColorBox" data-color='#171C61' style='background-color:#171C61;'></div>
                <div class="themeColorBox" data-color='#95E0F9' style='background-color:#95E0F9;'></div>
                <div class="themeColorBox" data-color='#6BCEF9' style='background-color:#6BCEF9;'></div>
                <div class="themeColorBox" data-color='#42BAFC' style='background-color:#42BAFC;'></div>
                <div class="themeColorBox" data-color='#1FA3FD' style='background-color:#1FA3FD;'></div>
                <div class="themeColorBox" data-color='#2F49A8' style='background-color:#2F49A8;'></div>
                <div class="themeColorBox" data-color='#172A88' style='background-color:#172A88;'></div>
                <div class="themeColorBox" data-color='#B7F4F9' style='background-color:#B7F4F9;'></div>
                <div class="themeColorBox" data-color='#81EAF9' style='background-color:#81EAF9;'></div>
                <div class="themeColorBox" data-color='#47E8F4' style='background-color:#47E8F4;'></div>
                <div class="themeColorBox" data-color='#28D9DD' style='background-color:#28D9DD;'></div>
                <div class="themeColorBox" data-color='#13A8A8' style='background-color:#13A8A8;'></div>
                <div class="themeColorBox" data-color='#145956' style='background-color:#145956;'></div>
                <div class="themeColorBox" data-color='#7AEDA8' style='background-color:#7AEDA8;'></div>
                <div class="themeColorBox" data-color='#4EDD88' style='background-color:#4EDD88;'></div>
                <div class="themeColorBox" data-color='#27B761' style='background-color:#27B761;'></div>
                <div class="themeColorBox" data-color='#139E4B' style='background-color:#139E4B;'></div>
                <div class="themeColorBox" data-color='#156B38' style='background-color:#156B38;'></div>
                <div class="themeColorBox" data-color='#0D4C2B' style='background-color:#0D4C2B;'></div>
                <div class="themeColorBox" data-color='#E8F261' style='background-color:#E8F261;'></div>
                <div class="themeColorBox" data-color='#DCF240' style='background-color:#DCF240;'></div>
                <div class="themeColorBox" data-color='#C9E829' style='background-color:#C9E829;'></div>
                <div class="themeColorBox" data-color='#B8CE19' style='background-color:#B8CE19;'></div>
                <div class="themeColorBox" data-color='#A3AF0C' style='background-color:#A3AF0C;'></div>
                <div class="themeColorBox" data-color='#7F8206' style='background-color:#7F8206;'></div>
                <div class="themeColorBox" data-color='#F9D895' style='background-color:#F9D895;'></div>
                <div class="themeColorBox" data-color='#F9CC69' style='background-color:#F9CC69;'></div>
                <div class="themeColorBox" data-color='#F9C33E' style='background-color:#F9C33E;'></div>
                <div class="themeColorBox" data-color='#FEC200' style='background-color:#FEC200;'></div>
                <div class="themeColorBox" data-color='#A07304' style='background-color:#A07304;'></div>
                <div class="themeColorBox" data-color='#F9C07D' style='background-color:#F9C07D;'></div>
                </div>
            </div>
        </div>
    </div>
</div>
</script>
<main id="main-content" data-view="timetable" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <div class="nav nav-left">
                    <ul class="nav__list">
                        </a>
                        <div class="nav__title">
                            <a href="#" id="addSchedule" class="active" title="Add timeline" data-toggle="modal"
                                data-target="#schedulePopup">
                                <img src="/themes/ewhaian/img/time_add_btn.jpg" width="25" id="addBtn">
                            </a>
                        </div>
                    </ul>
                </div>

                <div class="btn-template">
                    <div style="text-align: right;margin-bottom: 10px;">
                        누적: {{$schedule->total_credit ?? '0.0'}}학점
                    </div>
                    @if(!is_null($schedule))
                    {!! Theme::partial('timetable.leftSideBar',[
                        'scheduleAll' => $scheduleAll,
                        'schedule'=>$schedule,
                        'listFilter' => $listFilter,
                        'activeID' => $activeID
                    ])!!}
                    @endif

                    @if(!is_null($schedule))
                    <form action="{{route('scheduleFE.timeline.delete')}}" id="myform" method="POST">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{$schedule->id ?? 0}}">
                        <input type="hidden" name="active_filter" value="{{ request()->active_filter }}">
                        @if( is_null($schedule->timeline()->first() ) )

                        <input type="hidden" name="deleteSchedule" value="true">

                        @endif

                        <a class="btn__trash" onclick="document.getElementById('myform').submit()">
                            시간표 삭제하기
                        </a>
                    </form>
                    @endif
                    <div class="btn_line">
                        <input type="hidden" id="copyScheduleId" value="0">
                        <a class="btn__ehw grey" title="복사" id="copy" style="margin-right: 5px">
                            복사
                        </a>
                        <a class="btn__ehw grey" title="붙여넣기" id="paste" style="margin-right: 0px">
                            붙여넣기
                        </a>
                        <form action="{{route('scheduleFE.timeline.copySchedule')}}" method="post" id="copyPasteForm">
                            @csrf
                            <input type="hidden" name="schedule_id" id="pasteScheduleId" value="{{$schedule->id ?? 0}}">
                            <input type="hidden" name="curSem" id="curSem" value="{{request()->get('active_filter')}}">
                        </form>
                    </div>
                    @if ($scheduleAll->count() > 0)
                    <a href="#" title="Add timeline" class="btn__ehw white-border" data-toggle="modal"
                        data-target="#timelinePopup" id="addTimeLine">
                        일정 직접 추가하기
                    </a>
                    @endif
                </div>
            </div>
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    <li><a href="" title="{{__('campus')}}">{{__('campus')}}</a></li>
                    <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    <li>{{__('campus.timetable')}}</li>
                </ul>
                <div class="heading" style="display: flex;">
                    <div class="heading__title" style="white-space: nowrap;">
                        {{__('campus.timetable')}}
                    </div>
                    <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                        {!!$description->description ?? ""!!}
                    </div>
                </div>
                @if (session('err'))
                <div class="alert alert-danger" style="width:100% ; justify-content: center;">
                    {{ session('err') }}
                </div>
                @endif

                @if (session('success'))
                <div class="alert alert-success" style="width:100%;justify-content: center;">
                    {{ session('success') }}
                </div>
                @endif
                <div class="content">
                    @php
                        if(isset($config) && !is_null($config->show_lecture)) {
                            $json_show_lecture =  json_encode(  $config->show_lecture );
                        }
                        else {
                            if(!isset($config)) {
                                $json_show_lecture = json_encode(['1'=>'on','2'=>'on','3'=>'on']);
                            }
                        }
                    @endphp
                    <input type="hidden" content="{{route('scheduleFE.saveColor')}}" id ="route_save_color">
                    <input type="hidden" content="{{route('scheduleFE.timeline.get.timeline')}}" id ="route_get_data_timeline">
                    <input type="hidden" value="{{$schedule->id ?? null }}"  id ="schedule_id">
                    <div class="timetable" data-timetable="data-timetable"
                        data-json='@if(!is_null($schedule)){{ $timeline->toJson() }}@endif'
                        data-config='{{  isset($config) && !is_null($config->time)  ? json_encode($config->time): json_encode( $scheduleTime)}}'
                        data-showlecture='{{  $json_show_lecture}}'>
                        <div class="timetable__schedule-list d-flex flex-wrap mb-3 pb-3">
                            <div class="flex-grow-1"></div>
                            @if(!is_null($schedule))
                            <div class="timetable__btn-group">
                                <!---DROPDOWN CONTENT SETTING-->
                                <div class="dropdownPanel">
                                    <a href="javascript:;" class="btn btn-default btn-icon settingColor">
                                        <svg width="17" height="17" aria-hidden="true" class="icon">
                                        <use
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            xlink:href="#icon_setting"
                                        ></use>
                                        </svg>
                                      </a>
                                    <!---container SETTING-->
                                    <div class="dropdown-container isSetting">
                                        <div class="content">
                                        <form action="{{route('scheduleFE.timeline.setting')}}" method="POST" id="settingForm">
                                            @csrf
                                            <div class="flexPanel">
                                              {{-- <div class="flex-line align-center">
                                                  <div class="label font-weight-bold"></div>
                                              </div> --}}
                                              <div class="flex-line align-center">
                                                  <ul class="nav listBindingClass">
                                                      @if(!is_null($timeline1))
                                                          @foreach ($timeline1 as $item)
                                                          <li>
                                                              <a class='itemClass' href="#" data-target='{{$item->id}}' >

                                                                  <div style="overflow: hidden;
                                                                      line-height: 30px;
                                                                      height: 30px;
                                                                      width: 130px;"
                                                                  >
                                                                      {{$item->title}}
                                                                  </div>
                                                              <span class="colorClass" style='background-color: {{$item->color ?? 'white;border: 1px solid #ec1469'}};'
                                                              data-group_color="{{$item->group_color}}">
                                                              </span>
                                                              </a>
                                                              {!! Theme::partial('timetable.colorPickerPanel')!!}
                                                          </li>
                                                          @endforeach
                                                      @endif
                                                      @if(!is_null($timeline2))
                                                          @foreach ($timeline2 as $item)
                                                          <li>
                                                              <a class='itemClass' href="#" data-target='{{$item->id}}' >

                                                                  <div style="overflow: hidden;
                                                                      line-height: 30px;
                                                                      height: 30px;
                                                                      width: 130px;"
                                                                  >
                                                                      {{$item->title}}
                                                                  </div>
                                                              <span class="colorClass" style='background-color: {{$item->color ?? 'white;border: 1px solid #ec1469'}};'
                                                              data-group_color="{{$item->group_color}}">
                                                              </span>
                                                              </a>
                                                              {!! Theme::partial('timetable.colorPickerPanel')!!}
                                                          </li>
                                                          @endforeach
                                                      @endif
                                                  </ul>
                                              </div>
                                              <input type="hidden" name="schedule_id" value="{{$schedule->id ?? null}}">
                                              <div class="flex-line align-center">
                                                  <div class="label">시간표명</div>
                                                  <input
                                                    class="form-control"
                                                    type="text"
                                                    name="schedule_name"
                                                    placeholder="{{$schedule->name}}"
                                                    value="{{$schedule->name}}"
                                                    required
                                                  />
                                              </div>
                                              <div class="flex-line">
                                                <div class="label">보이는 강의정보
                                                </div>
                                                <ul class="listColor">
                                                  <li>
                                                      <div
                                                      class="custom-control custom-checkbox"
                                                      >
                                                      <input
                                                          type="checkbox"
                                                          class="custom-control-input"
                                                          id="customCheckSetting1"
                                                          name="show_lecture[1]"
                                                          @if(isset($config) && !is_null($config->show_lecture) && isset($config->show_lecture[1]))
                                                          checked
                                                          @else
                                                              @if(!isset($config))
                                                                  checked
                                                              @endif

                                                          @endif
                                                      />
                                                      <label
                                                          class="custom-control-label"
                                                          for="customCheckSetting1"
                                                          >강의명</label
                                                      >
                                                      </div>
                                                  </li>
                                                  <li>
                                                      <div
                                                      class="custom-control custom-checkbox"
                                                      >
                                                      <input
                                                          type="checkbox"
                                                          class="custom-control-input"
                                                          id="customCheckSetting2"
                                                          name="show_lecture[2]"
                                                          @if(isset($config) && !is_null($config->show_lecture) && isset($config->show_lecture[2]))
                                                              checked
                                                          @else
                                                              @if(!isset($config))
                                                                  checked
                                                              @endif
                                                          @endif
                                                      />
                                                      <label
                                                          class="custom-control-label"
                                                          for="customCheckSetting2"
                                                          >교수명</label
                                                      >
                                                      </div>
                                                  </li>
                                                  <li>
                                                      <div
                                                      class="custom-control custom-checkbox"
                                                      >
                                                      <input
                                                          type="checkbox"
                                                          class="custom-control-input"
                                                          id="customCheckSetting3"
                                                          name="show_lecture[3]"
                                                          @if(isset($config) && !is_null($config->show_lecture) && isset($config->show_lecture[3]))
                                                          checked
                                                              @else
                                                              @if(!isset($config))
                                                                  checked
                                                              @endif
                                                          @endif
                                                      />
                                                      <label
                                                          class="custom-control-label"
                                                          for="customCheckSetting3"
                                                          >장소</label
                                                      >
                                                      </div>
                                                  </li>
                                                </ul>
                                            </div>
                                            <div class="flex-line align-center">
                                                <div class="label">시간표 시작 시간
                                                </div>
                                                <select class="form-control inline-select" name="config[time][from]">
                                                @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
                                                    <option value="{{$i}}"
                                                    @if( isset($config) && $config->time['from'] == $i) {{'selected'}}  @endif

                                                    >{{$i.':00'}}</option>
                                                @endfor
                                                </select>
                                            </div>
                                            <div class="flex-line align-center">
                                                <div class="label">시간표 종료 시간
                                                </div>
                                                <select class="form-control inline-select" name="config[time][to]">
                                                    @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
                                                    <option
                                                    @if( isset($config) && $config->time['to'] == ($i+1) )
                                                        {{'selected'}}
                                                    @else
                                                        @if( !isset($config) && $scheduleTime->to == $i)
                                                        {{'selected'}}
                                                        @endif
                                                    @endif
                                                    value="{{$i+1}}"
                                                    >{{($i + 1).':00'}}</option>
                                                @endfor
                                                </select>
                                            </div>
                                            <input type="hidden" name="config[time][unit]" value="1">
                                            <div class="flex-line align-center">
                                                <div class="label">시간표 요일
                                                </div>
                                                <select class="form-control inline-select" name="config[day][from]">
                                                    @foreach ($scheduleDay as $key => $item)
                                                    <option value="{{$key+1}}"
                                                        @if( isset($config) && $config->day['from'] == $key +1) {{'selected'}} @endif
                                                    >{{ ucfirst( $item->name ) }}</option>
                                                    @endforeach
                                                </select>
                                                <select class="form-control inline-select" name="config[day][to]">
                                                @foreach ($scheduleDay as $key => $item)
                                                    <option value="{{$key+1}}"
                                                        @if(isset($config) && $config->day['to'] == $key +1)
                                                         {{'selected'}}
                                                        @else
                                                            @if( !isset($config) && $key+1 == count($scheduleDay))
                                                                {{'selected'}}
                                                            @endif

                                                         @endif
                                                    >{{ ucfirst( $item->name ) }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="flex-line">
                                                <div class="group-btn flex btn-template">
                                                <a style="display:none" download="timetable.jpeg" href="" id="linkImgDownload">download</a>
                                                <a href="#" class="btn__ehw lighter long screenShotEl">
                                                    이미지로 저장
                                                </a>
                                                <button type="submit" class="btn__ehw lighter">
                                                    확인
                                                </button>
                                                <button type="button" class="btn__ehw lighter" onclick="$('.dropdown-container').removeClass('isOpen')">
                                                    닫기
                                                </button>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>


                                    <!-- Semester list -->

                                    <a href="javascript:;" class="btn btn-default btn-icon semesterList">
                                      <svg width="17" height="17" aria-hidden="true" class="icon">
                                      <use
                                          xmlns:xlink="http://www.w3.org/1999/xlink"
                                          xlink:href="#icon_menu-list"
                                      ></use>
                                      </svg>
                                    </a>
                                    <div class="dropdown-container-semeter isSemester">
                                      <div class="content">
                                          <div style="width: 100%;text-align:center;padding-left:20px;padding-right:20px;"><div class="semesterTitle">시간표 학기 리스트</div></div>
                                      </div>
                                      <div style="padding:20px;">

                                        @foreach ($listFilter as $key=>$item)
                                        <div style="text-align: center">
                                          <a href="{{ route('scheduleFE.timeline.v2')}}?active_filter={{$item->id}}"><div class="hkleft"></div><div class="hkright @if($activeID == $item->id)activeSemester @endif">{{$item->name}}</div></a>
                                          </div>
                                        @endforeach




                                      </div>
                                    </div>
                                    <!-- End semester list -->

                                </div>
                            </div>
                            @else
                              <!-- Semester list -->
                              <div class="timetable__btn-group">
                                <!---DROPDOWN CONTENT SETTING-->
                                <div class="dropdownPanel">
                                    <a href="javascript:;" class="btn btn-default btn-icon semesterList">
                                      <svg width="17" height="17" aria-hidden="true" class="icon">
                                      <use
                                          xmlns:xlink="http://www.w3.org/1999/xlink"
                                          xlink:href="#icon_menu-list"
                                      ></use>
                                      </svg>
                                    </a>
                                    <div class="dropdown-container-semeter isSemester">
                                      <div class="content">
                                          <div style="width: 100%;text-align:center;padding-left:20px;padding-right:20px;"><div class="semesterTitle">시간표 학기 리스트</div></div>
                                      </div>
                                      <div style="padding:20px;">

                                        @foreach ($listFilter as $key=>$item)
                                        <div style="text-align: center">
                                          <a href="{{ route('scheduleFE.timeline.v2')}}?active_filter={{$item->id}}"><div class="hkleft"></div><div class="hkright @if($activeID == $item->id)activeSemester @endif">{{$item->name}}</div></a>
                                          </div>
                                        @endforeach




                                      </div>
                                    </div>
                                </div>
                              </div>
                              <!-- End semester list -->
                            @endif
                        </div>
                        <dialog id="screenShotmodal">
                            <div class="closePanel">
                                <a href="#"></a>
                            </div>
                        </dialog>
                        <div id="screenShot"></div>
                        <div class="timetable__content" >
                            <div class="d-flex align-items-center">
                                <div class="timetable__header flex-grow-1">
                                    <h3>{{$schedule->name ?? __('campus.timetable.no_have_schedule')}}</h3>
                                    @if(!is_null($schedule))
                                    <div class="timetable__time" style="display: none;margin-bottom: 10px">
                                        <span class="timetable__circle">
                                            <svg width="10" height="13" aria-hidden="true" class="icon">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    xlink:href="#icon_datetime"></use>
                                            </svg>
                                        </span>
                                        {{ date('d M Y', strtotime($schedule->start))}} -
                                        {{ date('d M Y', strtotime($schedule->end))}}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @if(!is_null($schedule))
                            <div class="timetable__content-schedule">
                                <ul
                                    class="timetable__content-schedule__header d-flex align-items-center justify-content-around" style="margin-bottom: 0px;">
                                    @if(isset($config))
                                        @foreach ($scheduleDay as $key => $item)
                                            @if( $config->day['from'] <= ($key+1)  && ($key+1) <= $config->day['to'] )
                                                <li
                                                style="width : {{100 / ($config->day['to'] - $config->day['from'] + 1).'%' }}; text-align : center; padding: 0 6px;"
                                                >{{ ucfirst( $item->name ) }}</li>
                                            @endif

                                        @endforeach
                                    @else
                                        @foreach ($scheduleDay as $item)
                                        <li
                                            style="width : {{100 / count($scheduleDay) .'%' }}; text-align : center; padding: 0 6px;"
                                        >{{ ucfirst( $item->name ) }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex flex-column align-items-center">
                                        @if(isset($config))
                                            @for ($i = $config->time['from']; $i <= $config->time['to']; $i++)
                                            <div class="timetable__content-number">{{$i}}</div>
                                            @endfor
                                        @else
                                            @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
                                            <div class="timetable__content-number">{{$i}}</div>
                                            @endfor
                                        @endif

                                    </div>
                                    <div class="d-flex flex-grow-1">
                                        <div class="row mx-0 flex-grow-1">
                                            @foreach ($scheduleDay as $key => $item)
                                                @if(isset($config))
                                                    @if(  $config->day['from'] <= ($key + 1)  && ($key + 1) <= $config->day['to']  )
                                                        <div class="col px-0 timetable__day" data-day="{{$item->name}}">
                                                            @for ($i = $config->time['from']; $i <= $config->time['to'] ;$i = $i + $config->time['unit'])
                                                                <div class="timetable__content-day" data-start="{{$i}}"></div>
                                                            @endfor
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="col px-0 timetable__day" data-day="{{$item->name}}">
                                                        @for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i = $i + $scheduleTime->unit)
                                                            <div class="timetable__content-day" data-start="{{$i}}">
                                                                @if (isset($dataSchedules[$item->name][$i]))
                                                                    @forEach ($dataSchedules[$item->name][$i] as $itemMonth)
                                                                        @for ($j = 0; $j < count($dataSchedules[$item->name][$i]); $j++)
                                                                            @php
                                                                                $color = $dataSchedules[$item->name][$i][$j]['color'] ?
                                                                                    $dataSchedules[$item->name][$i][$j]['color'] :
                                                                                    '#fff';
                                                                                $top = 0;
                                                                                $height = 25;
                                                                                $dateTimes = $dataSchedules[$item->name][$i][$j]['datetime'];
                                                                                foreach ($dateTimes as $dateTime) {
                                                                                    if ($dateTime['from'] - $i < 1) {
                                                                                        $from = $dateTime['from'];
                                                                                        $to = $dateTime['to'];
                                                                                        if ($from - $i > 0) {
                                                                                            $top = $height;
                                                                                        }
                                                                                        $height = ($to - $from) * 50;
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                            <div data-template="#template-info-html" data-group_color="{{ $dataSchedules[$item->name][$i][$j]['group_color'] }}" data-color="{{ $dataSchedules[$item->name][$i][$j]['color'] }}" class="timetable__content-task"
                                                                                data-id="{{ $dataSchedules[$item->name][$i][$j]['id'] }}"
                                                                                style="top: {{ $top }}px;height: {{ $height }}px;background-color: {{ $color }}" >
                                                                                <div>{{ Str::limit($dataSchedules[$item->name][$i][$j]['title'], 8) }}</div>
                                                                                <div>{{ Str::limit($dataSchedules[$item->name][$i][$j]['professor_name'], 8) }}</div>
                                                                                <div>{{ Str::limit($dataSchedules[$item->name][$i][$j]['lecture_room'], 8) }}</div>
                                                                            </div>
                                                                        @endfor
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        @endfor
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                </div>

                            </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</main>
<script>
    $(function() {
		// $('#addSchedule').click(function(){
		// 	$('.modal-backdrop').addClass("d-none");
		// 	$('.layer-modal').css({
		// 		"opacity": "0.4",
		// 		"visibility": "visible"
		// 	});

		// 	$('.layer-modal').on('click',function(){
		// 		$("#schedulePopup").modal('hide');
		// 	});
		// });

		// $('#addTimeLine').click(function(){
		// 	$('.modal-backdrop').addClass("d-none");
		// 	$('.layer-modal').css({
		// 		"opacity": "0.4",
		// 		"visibility": "visible"
		// 	});

		// 	$('.layer-modal').on('click',function(){
		// 		$("#timelinePopup").modal('hide');
		// 	});
		// });
        $(document.body).on('click', '.showLecture',function(){
				$('#lecture').attr('src',"");
				$('#lecture').attr('src',$(this).attr('lecture_src'));


				// $('.modal-backdrop').addClass("d-none");

				// $('.layer-modal').css({
				// 	"opacity": "0.4",
				// 	"visibility": "visible"
				// });

				// $('.layer-modal').on('click',function(){
				// 	$("#lecturePopup").modal('hide');
				// 	$('#lecture').attr('src',"");
				// });

				$('.close-button').on('click',function(){
					$("#lecturePopup").modal('hide');
					$('#lecture').attr('src',"");
				})
		});

		$('.header__key').click(function(){

			// $('.modal-backdrop').addClass("d-none");
			$('.layer-modal').css({
				"opacity": "0.4",
				"visibility": "visible"
			});

			$('.layer-modal').on('click',function(){
				$("#confirmPopup").modal('hide');
			});
		});

		$('#copy').click(function(e){
			e.preventDefault();
			$('#copyScheduleId').val(1);
			alert('시간표가 복사되었습니다');
		})

		$('#paste').click(function(e){
			e.preventDefault();
			if($('#copyScheduleId').val() == 0){
				alert('복사할 시간표를 선택해주세요');
			}else{
				$('#copyPasteForm').submit();
			}
		})

    $(".semesterList").click(function(e){
      if(!$(".isSemester").hasClass("isOpen")){
        $(".isSemester").addClass("isOpen");
        $(".isSetting").removeClass("isOpen");
      }else{
        $(".isSemester").removeClass("isOpen");
      }
    })


    $(".settingColor").click(function(e){
      if($(".isSemester").hasClass("isOpen")){
        $(".isSemester").removeClass("isOpen");
      }
    })

    $(document).mouseup(function(e)
    {
        var container = $(".isSemester");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            container.removeClass("isOpen");
        }
    });

	});

</script>
