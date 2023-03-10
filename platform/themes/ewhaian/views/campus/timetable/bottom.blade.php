<main id="frame-search__footer">
    <div class="search_panel">
        <div class="form_style flex">
            <div class="form-line">
                <select class="classsearchhelper form-control" name="helper" id="helper">
                    <option selected="selected" hidden="hidden">Choose major</option>
                    @foreach ($categories as $item)
                    @if( $item->getChild()->count() > 0 )
                    <optgroup label="{{$item->name}}">
                        @foreach ($item->getChild() as $subitem)
                        <option value="{{$subitem->name}}">{{$subitem->name}}</option>
                        @endforeach
                    </optgroup>
                    @else
                    <option value="{{$item->name}}">{{$item->name}}</option>
                    @endif

                    @endforeach
                </select>
            </div>
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
                    Search
                </a>
            </div>
        </div>
    </div>
    <div class="table_panel">
        <table class="table table-hover table-responsive table-striped">
            <thead>
                <tr>
                    <th>
                        Lecture
                    </th>
                    <th>
                        Major Name
                    </th>
                    <th>
                        Course code
                    </th>
                    <th>
                        Title
                    </th>
                    <th>
                        Professor Name
                    </th>
                    <th>
                        Score
                    </th>
                    <th>
                        Grades
                    </th>
                    <th>
                        Lecture room
                    </th>
                    <th>
                        Time
                    </th>
                    <th>
                        Remarks
                    </th>
                    <th>
                        Compete
                    </th>
                </tr>
            </thead>
            <tbody class="lecture">
                @foreach ($evaluation as $item)
                <tr data-json='{{ toJsonLecture($item->datetime,$item->title,$item->id)}}'>
                    <td>
                        <div class="story_btn">
                            <a href="{{route('campus.evaluation_details',['id'=>$item->id])}}" title="" target="_blank">
                                    <img src="/themes/ewhaian/img/classsearch_storybtn.png" alt="Image not found">
                            </a>

                        </div>

                    </td>
                    <td>{{$item->major->first()->name}}</td>
                    <td>{{$item->course_code}} </td>
                    <td>
                        <a href="javascript:void(0)" class="subject">{{$item->title}}</a>
                    </td>
                    <td>{{$item->professor_name}}</td>
                    <td>{{$item->score}}</td>
                    <td>{{$item->grade}}</td>
                    <td>{{$item->lecture_room}}</td>
                    <td>{{ date("월m일d",strtotime( $item->created_at) ) }}</td>
                    <td>{{$item->remark}}</td>
                    <td>{{$item->compete}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>

<script>
    $(function(){
            function convertObjToStyle(obj) {
                return Object.keys(obj).map((x) => `${x}: ${obj[x]}`).join(';');
            }

            function compileLiteralTemplate(style, id, title) {
                    return "<div class=\"timetable__content-task\" style=\"".concat(convertObjToStyle(style), "\" data-id=").concat(id, ">\n    ").concat(title, "\n  </div>");
            }

            // const jsonData = $('[data-timetable]').data('json');
            // // const timelineWidth = $('.timetable__content-timeline:first-child').width();
            // // const dayWidth = timelineWidth / jsonData.daysOfWeek.length;
            // // let taskTemplate = '';
            // const config = $('[data-config]').data('config') || { from: 8, to: 20, unit: 1 };

            // const data = jsonData.reduce((a, x) => ({ ...a, [x.day]: [...(a[x.day] || []), x] }), {});

            var render__timetable = () => {
                function compileLiteralTemplate(style, id, title) {
                    return "<div class=\"timetable__content-task\" style=\"".concat(convertObjToStyle(style), "\" data-id=").concat(id, ">\n    ").concat(title, "\n  </div>");
                }
                const jsonData = $('[data-timetable]').data('json');

                const config = $('[data-config]').data('config') || { from: 8, to: 20, unit: 1 };

                const data = jsonData.reduce((a, x) => ({ ...a, [x.day]: [...(a[x.day] || []), x] }), {});

                Object.keys(data).forEach((key) => {
                    data[key].forEach((item) => {
                        const position = Math.floor(item.from / config.unit);
                        const parentEL = $('[data-day=' + key + ']');
                        const dayEL = parentEL.find('[data-start=' + position + ']');

                        const positionOfEl = {
                            top: ((item.from % config.unit) * dayEL.outerHeight()) + 'px',
                            height: ((item.to - item.from) / config.unit * dayEL.outerHeight()) + 'px',
                        };
                        dayEL.append(compileLiteralTemplate(positionOfEl, item.id, item.title));
                    })
                })

            }
            var hover_line_schedule = () => {
            var $line = $("#frame-search__footer .table_panel table tbody tr");
            var isDay = [];
            var isStart = [];
            var isEnd = [];
            var valStart = [];
            var valEnd = [];
            var list_time = [];
            var a_time = null;
            var json = [];
            var $time_content = null;
            var obj = [];
            var objTime = [];

            var $subject = $line.find('a.subject');
            var $story_btn = $line.find('.story_btn');
            // var json = $line.data("json");
            $subject.on('click', function (e) {
                     sessionStorage.setItem('test', 'helllo');
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
                            // $('#top-component .pretty-split-pane-component-inner').empty();
                            // $('#top-component .pretty-split-pane-component-inner').html(data);
                            // render__timetable();
                            $('#test').text(sessionStorage.getItem("test")) ;

                        }

                    }
                }).always(function(){
                    $this.css('pointer-events', 'auto').data('isRunAjax', false);
                });

            });

            $line.on('mouseenter', function (e) {
                //renew array json with new data
                list_time = [];

                var it = e.currentTarget;
                var thisDay = $(`.timetable__day`);

                //get data json for each row
                json = $(this).closest('tr').data('json');
                //Loop json
                $.each(json, function (i, val) {
                    // Loop day of week
                    thisDay.each(function (iDay, vDay) {
                        if ($(vDay).attr('data-day') == val.day) {
                            isDay.push($(vDay));
                            $time_content = $(vDay).find('.timetable__content-day');
                            //Loop time of day
                            $time_content.each(function (iTime, vTime) {
                                // console.log($(vTime).attr('data-start'));
                                if (parseInt($(vTime).attr('data-start')) == val.from) {
                                    isStart.push($(vTime));
                                    valStart.push(parseInt($(vTime).attr('data-start')));
                                }
                                if (parseInt($(vTime).attr('data-start')) == val.to) {
                                    isEnd.push($(vTime));
                                    valEnd.push(parseInt($(vTime).attr('data-start')));
                                }
                                // Initalize time aray
                                list_time = [val.from];
                                for (i = 1; i <= val.to - val.from; i++) {
                                    a_time = val.from + i;
                                    list_time.push(a_time);
                                };
                                //Check specific cell
                                $.each(list_time, function (i, val) {
                                    $time_content.each(function (iDay, vDay) {
                                        if (parseInt($(vDay).attr('data-start')) === val) {
                                            $(vDay).css({
                                                "background": "rgba(51,51,51,0.6)",
                                                "border-bottom": "none"
                                            });
                                        }
                                    });
                                });
                            });
                            //save time DOM in new array
                            objTime.push($time_content);

                        };
                    });
                    //save time array in new array
                    obj.push(list_time);
                });
                // console.log(list_time);
            }).on('mouseleave', function (e) {
                $.each(objTime, function (iD, vDayArray) {
                    vDayArray.each(function (iTime, vTime) {
                        $.each(obj, function (index, value) {
                            $.each(value, function (i, v) {
                                if (parseInt($(vTime).attr('data-start')) === v) {
                                    $(vTime).css({
                                        "background": "",
                                        "border-bottom": "1px solid #e8e8e8"
                                    });
                                };
                            });
                        })
                    });
                })
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
