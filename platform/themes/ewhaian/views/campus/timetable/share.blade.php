{{-- <div class="loading">
        <div class="image-loading">
            <img src="/themes/ewhaian/img/Loading.gif" />
            <img src="/themes/ewhaian/img/EH logo.png" />
        </div>
</div> --}}
<main id="main-content" data-view="timetable" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        {!! Theme::partial('campus.menu',['active'=>"timetableShare"]) !!}
        <div class="sidebar-template__content">
          <ul class="breadcrumb">
            <li><a href="" title="Event">Campus</a></li>
            <li>
              <svg width="4" height="6" aria-hidden="true" class="icon">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
              </svg>
            </li>
            <li> Timetable Share With Me</li>
          </ul>
          <div class="heading" style="display: flex;">
            <div class="heading__title" style="white-space: nowrap;">
                Timetable Share With Me
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
                  @foreach ($scheduleAll as $item)
                    <a href="{{route('scheduleFE.timeline.sharebyid',['id'=>$item->id])}}" title=" {{$item->name}}" class="@if($item->id == $schedule->id) btn btn-primary @else  btn btn-secondary @endif ">
                        {{$item->name}}
                      </a>
                  @endforeach
                </div>
              </div>
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
                      <svg width="15.849" height="17" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_share"></use>
                      </svg>
                      <div class="timetable__actions">By {{$schedule->scheduleShare()->first()->author}}</div>
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
            <div class="timetable">
              <div class="timetable__content">
                <div class="d-flex align-items-center">
                  <div class="timetable__header flex-grow-1">
                    <h3>No Schedule Share With Me</h3>
                  </div>
                </div>
              </div>

            </div>
            @endif
          </div>

        </div>

      </div>

    </div>
  </main>
