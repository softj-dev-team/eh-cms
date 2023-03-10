<div class="info-schedule-popup">
    <div class="content">
        <div class="info-head">
            <span>{{$timeline->course_division}}</span>
            @if ($timeline->major_type)
            | <span>{{$timeline->major_type}}</span>
            @endif
            @if ($timeline->department)
            | <span>{{$timeline->department}}</span>
            @endif
            @if ($timeline->compete)
            | <span>{{$timeline->compete}}</span>
            @endif
            <div class="closeInfo">
                <a href="#"></a>
            </div>
        </div>
        <div class="info-content">
            <h4 class="text-center font-weight-bold mb-3">{{ $timeline->course_division }}</h4>
            <ul class="nav">
                <li>
                    <span class="label">{{ __('campus.timetable.professor_name') }}: &nbsp;</span>
                    <span>{{$timeline->professor_name}}</span>
                </li>

                <li>
                    <span class="label">{{ __('campus.timetable.credit') }}: &nbsp;</span>
                    <span>{{ $timeline->score ? $timeline->score : '---' }}</span>
                </li>

                <li>
                  <span class="label">{{ __('campus.timetable.lecture_room') }}: &nbsp;</span>
                    <span>{{ $timeline->lecture_room }}</span>
                </li>

                <li>
                    <span class="label">{{ __('campus.timetable.remarks') }}: &nbsp;</span>
                    <span>{{ $timeline->remark ? $timeline->remark : '---' }}</span>
                </li>

                <li>
                    <span class="label">{{ __('campus.timetable.quota') }}: &nbsp;</span>
                    <span>{{ $timeline->quota ? $timeline->quota : '---' }}</span>
                </li>

                <li>
                    <span class="label">{{ __('campus.timetable.time') }}: &nbsp;</span>
                    @forEach ($timeline->datetime as $datetime)
                      <div>{{ucfirst($datetime['day']) }} {{ $datetime['from'] }} - {{ $datetime['to'] }}</div>
                    @endforeach
                </li>
            </ul>
        </div>
        <div class="info-bottom">
            <div class="text">
              @if($timeline->group_color)
                융복합교양(표현과예술)
                {{-- 융복합교양(표현과예술)
                융복합교양(표현과예술) --}}
              @endif
            </div>

            <div class="btn-template">
                @if(!is_null($timeline->group_color))
                <div class="btn__ehw lighter" style="margin-right: 10px;">
                    <a href="javascript:void(0)" title="" target="_blank" data-toggle="modal"
                    data-target="#lecturePopup" class="showLecture" lecture_src="{{route('scheduleFE.timeline.showLecture',['id'=>$timeline->group_color])}}">
                    강의평가
                    </a>
                </div>
                @endif
                <div class="btn__ehw lighter colorPicker" style="margin-right: 10px;">색상변경</div>

                <a class="btn__ehw lighter delete" href="{{route('scheduleFE.timeline.delete.timeline',['id' => $timeline->id])}}" title="강의삭제">강의삭제</a>
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
                    <div class="themeColorBox" data-color='#FFFFFF' style='background-color:#FFFFFF;'></div>
                    <div class="themeColorBox" data-color='#F9D895' style='background-color:#F9D895;'></div>
                    <div class="themeColorBox" data-color='#F9CC69' style='background-color:#F9CC69;'></div>
                    <div class="themeColorBox" data-color='#F9C33E' style='background-color:#F9C33E;'></div>
                    <div class="themeColorBox" data-color='#FEC200' style='background-color:#FEC200;'></div>
                    <div class="themeColorBox" data-color='#A07304' style='background-color:#A07304;'></div>
                </div>
            </div>
        </div>
    </div>
</div>
