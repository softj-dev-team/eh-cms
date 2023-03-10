<main id="frame-search__footer">
    <div class="search_panel" >
        <div class="form_style flex">
{{--          hidden VT02P010J-121--}}
{{--            <div class="form-line hidden">--}}
{{--                <select class="classsearchhelper form-control" name="helper" id="helper">--}}
{{--                    <option selected="selected" hidden="hidden">{{__('campus.timetable.choose_major')}}</option>--}}
{{--                    @foreach ($categories as $item)--}}
{{--                    @if( $item->getChild()->count() > 0 )--}}
{{--                    <optgroup label="{{$item->name}}">--}}
{{--                        @foreach ($item->getChild() as $subitem)--}}
{{--                        <option value="{{$subitem->name}}">{{$subitem->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </optgroup>--}}
{{--                    @else--}}
{{--                    <option value="{{$item->name}}">{{$item->name}}</option>--}}
{{--                    @endif--}}

{{--                    @endforeach--}}
{{--                </select>--}}
{{--            </div>--}}
            <div class="form-line has-checkbox" >
                <input type="checkbox" id="myclassft" name="checkDuplicate" >
                <label for="myclassft">겹치는 수업제외</label>
            </div>
            <div class="form-line">
                <input type="text" class="form-control bg-pink" autocomplete="false" id="searchValue">
                <div class="description-dropdown d-none">
                    <div class="dropdown_title">
                        <p>검색 안내</p>
                    </div>
                    <div class="dropdown_panel">
                        <div class="dropdown__line">
                            <div class="left">시간 제한</div>
                            <div class="right">
                                월요일3교시
                                <span>목3-4</span>
                                | 월.화.목요일
                                <span>월화목</span>
                                | 월, 화요일1-3교시
                                <span>월화1-3</span>
                            </div>
                        </div>
                        <div class="dropdown__line">
                            <div class="left">시간 제외</div>
                            <div class="right">
                                월요일3교시
                                <span>목3-4</span>
                                | 월.화.목요일
                                <span>월화목</span>
                                | 월, 화요일1-3교시
                                <span>월화1-3</span>
                            </div>
                        </div>
                        <div class="dropdown__line">
                            <div class="left">학점</div>
                            <div class="right">
                                월요일2교시가 포함된 모든 수업
                                <span>+월2</span>
                            </div>
                        </div>
                        <div class="dropdown__line">
                            <div class="left">학점</div>
                            <div class="right">
                                월요일3교시
                                <span>목3-4</span>
                                | 월.화.목요일
                                <span>월화목</span>
                                | 월, 화요일1-3교시
                                <span>월화1-3</span>
                            </div>
                        </div>
                        <div class="dropdown__line">
                            <div class="left">학점</div>
                            <div class="right">
                                1학년
                                <span>#1</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-line btn-template">
                <a class="btn__ehw pink" id="searchLecture">
                    {{__('campus.timetable.search')}}
                </a>
            </div>
        </div>
    </div>
    <div class="table_panel d-none d-lg-block">
        <table class="table table-hover table-responsive table-striped">
            <thead>
                <tr>
                    <th>
                        {{__('campus.timetable.lecture_evaluation')}}
                    </th>
{{--                    <th>--}}
{{--                        {{__('campus.timetable.category')}}--}}
{{--                    </th>--}}
                    {{-- <th>
                      {{__('campus.timetable.department')}}
                    </th>
                    <th>
                      {{__('campus.evaluation.major')}}
                    </th> --}}
                    <th>
                      {{__('campus.timetable.major_type')}}
                    </th>
                    <th>
                        {{__('campus.timetable.course_code_division')}}
                    </th>
                    <th>
                        {{__('campus.timetable.subject_name')}}
                    </th>
{{--                    <th>--}}
{{--                        {{__('campus.timetable.title')}}--}}
{{--                    </th>--}}

                    <th>
                        {{__('campus.timetable.professor_name')}}
                    </th>
                    <th>
                        {{__('campus.timetable.grades')}}
                    </th>
                    <th>
                      {{__('campus.timetable.credit')}}
                    </th>
                    <th>
                        {{__('campus.timetable.lecture_room')}}
                    </th>
                    <th>
                        {{__('campus.timetable.time')}}
                    </th>
                    <th>
                        {{__('campus.timetable.remarks')}}
                    </th>

                    <th>
                        {{__('campus.timetable.quota')}}
                    </th>
                    <th>
                        {{__('campus.timetable.compete')}}
                    </th>
                </tr>
            </thead>
            <tbody class="lecture">
                @foreach ($evaluation as $item)
                <tr data-json='{{ toJsonLecture($item->datetime,$item->title,$item->id)}}'>
                    <td>
                        <div class="story_btn">
                            <a href="javascript:void(0)" title="" target="_blank" data-toggle="modal"
                            data-target="#lecturePopup" class="showLecture" lecture_src="{{route('scheduleFE.timeline.showLecture',['id'=>$item->id])}}">
                                    <img src="/themes/ewhaian/img/classsearch_storybtn.png" alt="Image not found">
                            </a>

                        </div>

                    </td>
{{--                    <td> @if ($item->major->first()) {{$item->major->first()->name}} @endif</td>--}}
                    {{-- <td>{{$item->department}}</td>
                    <td>{!! $item->major->pluck('name')->implode(',')!!}</td> --}}
                    <td>{{$item->major_type}}</td>
                    <td>{{$item->course_code}}-{{$item->division}}  </td>
{{--                    <td>--}}
{{--                        <a href="javascript:void(0)" class="subject" lecture_id="{{$item->id}}">{{$item->title}}</a>--}}
{{--                    </td>--}}
                    <td>{{$item->subject_name}}</td>
                    <td>{{$item->professor_name}}</td>
                    <td>{{$item->grade}}</td>
                    <td>{{$item->score}}</td>
                    <td>{{$item->lecture_room}}</td>
                    <td>{{ date("월m일d",strtotime( $item->created_at) ) }}</td>
                    <td>{{$item->remark}}</td>
                    <td>{{$item->quota}}</td>
                    <td>{{$item->compete}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="table_panel d-lg-none">
        <div class="table-responsive">
            <table id="table-schedule-mb" class="table table-hover table-striped">
                <thead>
                    <tr>
                        {{-- <th>
                            {{__('campus.timetable.lecture_evaluation')}}
                        </th>
                        --}}
                        <th width="9%">
                            {{__('campus.timetable.course_code_division')}}
                        </th>
                        <th width="6%">
                            {{__('campus.timetable.subject_name')}}
                        </th>
                        <th width="5%">
                            {{__('campus.timetable.professor_name')}}
                        </th>
                        {{-- <th>
                            {{__('campus.timetable.grades')}}
                        </th>
                        <th>
                        {{__('campus.timetable.credit')}}
                        </th>
                        <th>
                            {{__('campus.timetable.lecture_room')}}
                        </th> --}}
                        <th width="7%">
                            {{__('campus.timetable.time')}}
                        </th>
                        <th width="7%">
                            {{__('campus.timetable.remarks')}}
                        </th>
                        <th width="8%">
                        {{__('campus.timetable.major_type')}}
                        </th>
                        <th>
                        {{__('campus.timetable.grades')}}
                        </th>
                        <th>
                        {{__('campus.timetable.credit')}}
                        </th>
                        <th width="15%">
                            {{__('campus.timetable.lecture_room')}}
                        </th>
                        <th>
                            {{__('campus.timetable.quota')}}
                        </th>
                        <th>
                            {{__('campus.timetable.compete')}}
                        </th>
                    </tr>
            </thead>
            <tbody class="lecture">
                @foreach ($evaluation as $item)
                <tr data-json='{{ toJsonLecture($item->datetime,$item->title,$item->id)}}'>
                    {{-- <td>
                        <div class="story_btn">
                            <a href="javascript:void(0)" title="" target="_blank" data-toggle="modal"
                            data-target="#lecturePopup" class="showLecture" lecture_src="{{route('scheduleFE.timeline.showLecture',['id'=>$item->id])}}">
                                    <img src="/themes/ewhaian/img/classsearch_storybtn.png" alt="Image not found">
                            </a>

                        </div>

                    </td> --}}
                    <td>{{$item->course_code}}-{{$item->division}}  </td>
                    <td>{{$item->subject_name}}</td>
                    <td>{{$item->professor_name}}</td>
                    {{-- <td>{{$item->grade}}</td>
                    <td>{{$item->score}}</td>
                    <td>{{$item->lecture_room}}</td> --}}
                    <td>{{ date("월m일d",strtotime( $item->created_at) ) }}</td>
                    <td>{{$item->remark}}</td>
                    <td>{{$item->major_type}}</td>
                    <td>{{$item->grade}}</td>
                    <td>{{$item->score}}</td>
                    <td>{{$item->lecture_room}}</td>
                    <td>{{$item->quota}}</td>
                    <td>{{$item->compete}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
      </div>
    </div>

</main>

<script>
    $(function(){
            function convertObjToStyle(obj) {
                return Object.keys(obj).map((x) => `${x}: ${obj[x]}`).join(';');
            }

            function compileLiteralTemplate(style, id, title, color,group_color) {
                return "<div data-template='#template-info-html' data-group_color='"+group_color+"' data-color='"+ color +"'  class=\"timetable__content-task\" style=\"".concat(convertObjToStyle(style), "\" data-id=").concat(id, ">\n    ").concat(title, "\n  </div>");
            }
            var render__timetable = () => {
                function compileLiteralTemplate(style, id, title, color,group_color) {
                    return "<div data-template='#template-info-html' data-group_color='"+group_color+"' data-color='"+ color +"'  class=\"timetable__content-task\" style=\"".concat(convertObjToStyle(style), "\" data-id=").concat(id, ">\n    ").concat(title, "\n  </div>");
                }
                const jsonData = $('[data-timetable]').data('json');

                const config = $('[data-config]').data('config') || { from: 8, to: 20, unit: 1 };
                var showlecture = $('[data-showlecture]').data('showlecture') || { '1' : on, '2' : on, '3': on };

                const data = jsonData.reduce((a, x) => ({ ...a, [x.day]: [...(a[x.day] || []), x] }), {});

                Object.keys(data).forEach((key) => {
                    data[key].forEach((item) => {
                        const position = Math.floor(item.from / config.unit);
                        const parentEL = $('[data-day=' + key + ']');
                        const dayEL = parentEL.find('[data-start=' + position + ']');
                        var color = item.color;
                        var group_color = item.group_color;
                        const positionOfEl = {
                            top: ((item.from % config.unit) * dayEL.outerHeight()) + 'px',
                            height: ((item.to - item.from) / config.unit * dayEL.outerHeight()) + 'px',
                            'background-color' : color,
                        };
                        var title = '';
                        if (showlecture[1] == 'on') {
                            let temp = ''
                            if (item.title.length > 5) {
                                temp = '...';
                            }
                            title = title + item.title.substr(0,5) + temp +'<br/>';
                        }
                        if (showlecture[2] == 'on') {
                            title = title + item.professor_name + '<br/>';
                        }
                        if (showlecture[3] == 'on') {
                            title = title +item.lecture_room;
                        }
                        dayEL.append(compileLiteralTemplate(positionOfEl, item.id, title,color,group_color));
                    })
                })

            }
            var hover_line_schedule = () => {
                var $line = $("#frame-search__footer .table_panel table tbody tr");
                const $table = $(".table_panel");

                var $subject = $line.find('a.subject');
                $subject.on('click', function (e) {
                    e.preventDefault();
                    let $this = $(this);

                    let json_cell = $this.closest('tr').data('json');

                    if($this.data('isRunAjax')==true){return;}
                    $this.css('pointer-events', 'none').data('isRunAjax', true);
                    $.ajax({
                        type:'POST',
                        url:'{{route('scheduleFE.timetable')}}',
                        data:{
                                _token: "{{ csrf_token() }}",
                                'timeline': json_cell,
                                'schedule_id': {{$schedule->id ?? 0}},
                                'lecture_id':$this.attr('lecture_id'),
                        },
                        dataType: 'html',
                        success: function( data ) {
                            if( data == 'Error01' || data =='Error02' || data == 'Error03' ){

                                switch (data) {
                                    case 'Error01':
                                    alert("From must be smaller than To!!!");
                                        break;
                                    case 'Error02':
                                    alert("Time already exists, please choose another time!!!");
                                        break;
                                    case 'Error03':
                                    alert("Please add new Schedule");
                                        break;
                                    default:
                                        break;
                                }
                            }else{
                                $('#top-component .pretty-split-pane-component-inner').empty();
                                $('#top-component .pretty-split-pane-component-inner').html(data);
                                render__timetable();
                                slide_content();
                            }

                        }
                    }).always(function(){
                        $this.css('pointer-events', 'auto').data('isRunAjax', false);
                    });

                });

                if ($table.length < 1) { return }
                $(".table_panel tbody tr").on("mouseenter", e => {
                    const json = $(e.currentTarget).data("json");
                    json.map(d => {
                        for (let i = Math.floor(d.from); i < d.to; i++) {
                            $(`.timetable__day[data-day="${d.day}"] .timetable__content-day[data-start="${i}"]`).addClass("day-hl");
                        }
                        if (Math.floor(d.to) < d.to) {
                            $(`.timetable__day[data-day="${d.day}"] .timetable__content-day[data-start="${Math.floor(d.to)}"]`).addClass("day-hl-plus");
                        }
                        if (Math.floor(d.from) < d.from) {
                            $(`.timetable__day[data-day="${d.day}"] .timetable__content-day[data-start="${Math.floor(d.from)}"]`).addClass("day-hl-minus");
                        }
                    });
                }).on("mouseleave", e => {
                    $(`.timetable__day .timetable__content-day.day-hl`).removeClass('day-hl day-hl-plus day-hl-minus');
                });
         };
            hover_line_schedule();
            $("#searchLecture").on("click", function(e){

                var $input = $('#searchValue');
                var $checkbox = $('#myclassft');

                e.preventDefault();
                var $this = $(this);

                if($this.data('isRunAjax')==true){return;}
                $this.css('pointer-events', 'none').data('isRunAjax', true);
                $.ajax({
                    type:'POST',
                    url:'{{route('scheduleFE.ajaxSearch')}}',
                    data:{
                            _token: "{{ csrf_token() }}",
                            'keyword': $input.val(),
                            'checkDuplicate':  $checkbox.prop("checked"),
                            'schedule_id': {{$schedule->id ?? 0}},
                    },
                    success: function( data ) {
                        $('.lecture').empty();
                        $('.lecture').html(data.result);
                         hover_line_schedule();
                    }
                }).always(function(){
                    $this.css('pointer-events', 'auto').data('isRunAjax', false);
                });
            });

        });
</script>

