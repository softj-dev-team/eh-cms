<style>
.form-control {
    border-bottom: none;
}
.form-submit {
    height: 30px;
    border-radius: 5px;
    color: #ffffff;
    padding: 0 20px;
    font-size: 12px;
    background-color: #EC1469;
    border: none;
}
#totalVotes {
    z-index: 1;
}
#rateYo {
    z-index: 1;
}

.hidden_comment_report {
  display: none;
}
</style>
<main id="main-content" data-view="lecture-evaluation" class="lecture-evaluation-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
          {!! Theme::partial('campus.menu',['active'=>"evaluation"]) !!}
          <!-- end of category menu -->
        </div>
        <div class="sidebar-template__content">
            <ul class="breadcrumb">
                @foreach (Theme::breadcrumb()->getCrumbs() as $i => $crumb)
                @if ($i != (count(Theme::breadcrumb()->getCrumbs()) - 1))
                <li>
                    <a href="{{ $crumb['url'] }}">{!! $crumb['label'] !!}</a>
                    <svg width="4" height="6" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                    </svg>
                </li>
                @else
                <li class="active">{!! $crumb['label'] !!}</li>
                @endif
                @endforeach
            </ul>

          <div class="heading">
            <div class="heading__title">
                {{__('campus.evaluation')}}
            </div>
            <p class="heading__description" style="display: none">{{__('campus.evaluation.heading__description')}}</p>

            <div class="single__info">
              <div class="single__date">
                <svg width="15" height="17" aria-hidden="true" class="icon">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                </svg>
                {{Carbon\Carbon::parse( $evaluation->publish)->format('Y-m-d')}}
              </div>
              <div class="single__eye">
                <svg width="16" height="10" aria-hidden="true" class="icon">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                </svg>
                {{ $evaluation->lookup ?? 0}}
              </div>
              <div class="single__eye">
                {!! Theme::partial('report' , [
                    'type_report'=> '1',
                    'type_post'=> '13',
                    'id_post'=> $evaluation->id,
                    'object' => $evaluation
                ]) !!}
              </div>

          </div>
          @if (session('err'))
          <div>
              <ul>
                  <li class="alert alert-danger" style="display: block">{{ session('err') }}</li>
              </ul>
          </div>
          @endif
          @if (session('success'))
          <div>
              <ul>
                  <li class="alert alert-success"  style="display: block">{{ session('success') }}</li>
              </ul>
          </div>
          @endif
{{--            msg report--}}
          @if (session('msg'))
            <div class="alert alert-success" role="alert">
              {{ session('msg') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
{{--            end report--}}
          @if ($errors->any())
          <div >
              <ul>
                  @foreach ($errors->all() as $error)
                      <li class="alert alert-danger" style="display: block">{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
          @endif
          <div class="lecture-evaluation">
            <div class="box-info">
              <div class="box-info__heading">
                <div class="row align-items-center">
                  <div class="col">
                    <p class="box-info__code">{{$evaluation->id}}</p>
                    <p class="box-info__title box-info__title--pink">{{$evaluation->title}}</p>
                  </div>
                  <div class="col text-right" style="display: flex;justify-content: flex-end">
                    @if(is_null($votes))
                        <div >{{ ''}}</div>
                    @else
                        <div style="font-size: 17px;">{{ round($votes,1)}}</div>
                        <div class="stars" style="display: flex;justify-content: flex-end">
                            <div id="totalVotes" ></div>
                        </div>
                    @endif
                  </div>
                </div>
              </div>

              <div class="box-info__table">
                <p>
                  <span class="label">{{__('campus.evaluation.professor_name')}}</span>
                  <span class="text">{{$evaluation->professor_name}}</span>
                </p>
                <p>
                  <span class="label">{{__('campus.evaluation.semester')}}</span>
                  <span class="text">{{$evaluation->semester}}</span>
                </p>
                <p>
                  <span class="label">{{__('campus.evaluation.score')}}</span>
                  <span class="text">{{$evaluation->score}}</span>
                </p>
                <p>
                  <span class="label">{{__('campus.evaluation.major')}}</span>
                  <span class="text">
                    @foreach ($evaluation->major as $key => $item)
                    @if (count($evaluation->major) == ($key + 1) )
                        {{$item->name.'.'  }}
                    @else
                        {{$item->name .', ' }}
                    @endif

                    @endforeach
                </span>

                </p>
                <p>
                  <span class="label">{{__('campus.evaluation.grade')}}</span>
                  <span class="text">{{$evaluation->grade}}</span>
                </p>
                <p>
                  <span class="label">{{__('campus.evaluation.remark')}}</span>
                  <span class="text">{{ EVULATION_REMARK[$evaluation->remark] ?? '' }}</span>
                </p>
              </div>
            </div>

            <div class="row no-gutters group-info">
              <div class="col">
                <div class="box-info">
                  <div class="box-info__heading">
                    <div class="row">
                      <div class="col">
                        <p class="box-info__title">{{__('campus.evaluation.lecture_information')}}</p>
                      </div>
                    </div>
                  </div>

                  <div class="box-info__table">
                    <p>
                    <!-- 학점 -->
                      <span class="label">{{__('campus.evaluation.score')}}</span>
                      <span>{{ getNameByValue( $evaluation->getValueLecture('grade') ?? 'no_rating',[
                          'normal'=>__('campus.evaluation.normal'),
                          'rose_knife'=>__('campus.evaluation.rose_knife'),
                          'no_rating'=> '',
                      ] )  }}
                      </span>
                    </p>
                    <p>
                    <!-- 과제 -->
                      <span class="label">{{__('campus.evaluation.assignment')}}</span>
                      <span>{{ getNameByValue( $evaluation->getValueLecture('assignment') ?? 'no_rating',[
                          'a_lot'=>__('campus.evaluation.a_lot'),
                          'normal'=>__('campus.evaluation.normal'),
                          'none'=>__('campus.evaluation.none'),
                          'no_rating'=> '',
                      ] )  }}
                      </span>
                    </p>
                    <p>
                    <!-- 출결 -->
                      <span class="label">{{__('campus.evaluation.attendance')}}</span>
                      <span>{{ getNameByValue( $evaluation->getValueLecture('attendance') ?? 'no_rating',[
                          'care_student'=>__('campus.evaluation.care_student'),
                          'designated_seat'=>__('campus.evaluation.designated_seat'),
                          'electronic_attendance'=>__('campus.evaluation.electronic_attendance'),
                          'dont_care_student'=>__('campus.evaluation.dont_care_student'),
                          'no_rating'=> '',
                      ] )  }}</span>
                    </p>
                    <p>
                    <!-- 팀플 -->
                      <span class="label">{{__('campus.evaluation.team_project')}}</span>
                      <span>{{ getNameByValue( $evaluation->getValueLecture('team_project') ?? 'no_rating',[
                          'a_lot'=>__('campus.evaluation.a_lot'),
                          'normal'=>__('campus.evaluation.normal'),
                          'none'=>__('campus.evaluation.none'),
                          'no_rating'=> '',
                      ] )  }}
                      </span>
                    </p>
                    <p>
                    <!-- 교제 -->
                      <span class="label">{{__('campus.evaluation.textbook')}}</span>
                      <span>{{ getNameByValue( $evaluation->getValueLecture('textbook') ?? 'no_rating',[
                          'textbook'=>__('campus.evaluation.textbook'),
                          'ppt'=>__('campus.evaluation.ppt'),
                          'none'=>__('campus.evaluation.none'),
                          'no_rating'=> '',
                      ] )  }}
                      </span>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="box-info">
                  <div class="box-info__heading">
                    <div class="row">
                      <div class="col">
                        <p class="box-info__title">{{__('campus.evaluation.exam')}}</p>
                      </div>
                    </div>
                  </div>

                  <div class="box-info__table">
                    <p>
                    <!-- 시험 횟수 -->
                      <span class="label">{{__('campus.evaluation.number_of_times')}}</span>
                      <span>
                      {{ getNameByValue( $evaluation->getValueLecture('number_times') ??  '' )  }}
                         {{-- <!-- {{ getNameByValue( $evaluation->getValueLecture('number_times') ?? 'no_rating',[
                          'times'=>__('campus.evaluation.times'),
                          'no_rating'=> '',
                      ] )  }} --> --}}
                      </span>
                    </p>
                    <p>
                    <!--시험 유형 -->
                      <span class="label">{{__('campus.evaluation.type')}}</span>
                      <span>
                      <!-- {{  getNameByValue( $evaluation->getValueInArr('type') ??  '' ) }} -->
                      {{ getNameByValue( $evaluation->getValueLecture('type') ?? 'no_rating',[
                          'multiple_choices'=>__('campus.evaluation.multiple_choices'),
                          'short_answer'=>__('campus.evaluation.short_answer'),
                          't_f'=>__('campus.evaluation.tf'),
                          'short_essay'=>__('campus.evaluation.short_essay'),
                          'long_essay'=>__('campus.evaluation.long_essay'),
                          'oral'=>__('campus.evaluation.oral'),
                          'alternative'=>__('campus.evaluation.alternative'),
                          'no_rating'=> '',
                      ] )  }}
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="other-posts">
                @if ($canCreateComment)
                <div  class="text-right" >

                    <button class=" filter__item btn btn-primary mx-3" id="showVote" checkVote="0" >{{__('campus.evaluation.write_lecture_evaluation')}}</button>
                </div>
                <div class="box-info comments" style="display:none">
                    <form action="{{route('campus.evaluation_comments',['id'=>$evaluation->id])}}" method="post">
                        @csrf
                        <input type="hidden" name="evaluation_id" value="{{$evaluation->id}}">
                        <input type="hidden" name="starVote" id="starVote" value="5">
                        <div class="box-info__table form-group" >
                            <div class="lecture-evaluation">
                                  <div class="write-post">
                                    <div class="write-post__box">
                                      <p class="write-post__title">{{__('campus.evaluation.lecture_information')}}</p>
                                      <div class="write-post__info">
                                        <div class="write-post__row">
                                          <div class="write-post__col">
                                            <p class="label">{{__('campus.evaluation.score')}}</p>
                                            <div class="options">
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="grade" id="cb1" value="normal" checked>
                                                <label class="form-check-label" for="cb1">
                                                 {{__('campus.evaluation.normal')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="grade" id="cb2" value="rose_knife">
                                                <label class="form-check-label" for="cb2">
                                                  {{__('campus.evaluation.rose_knife')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="grade" id="cb3" value="none">
                                                <label class="form-check-label" for="cb3">
{{--                                                  {{__('campus.evaluation.none')}}--}}
                                                  쁠몰
                                                </label>
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="write-post__row">
                                          <div class="write-post__col">
                                            <p class="label">{{__('campus.evaluation.assignment')}}</p>
                                            <div class="options">
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="assignment" id="as1" value="a_lot" checked>
                                                <label class="form-check-label" for="as1">
                                                 {{__('campus.evaluation.a_lot')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="assignment" id="as2" value="normal">
                                                <label class="form-check-label" for="as2">
                                                  {{__('campus.evaluation.normal')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="assignment" id="as3" value="none">
                                                <label class="form-check-label" for="as3">
                                                  {{__('campus.evaluation.none')}}
                                                </label>
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="write-post__row">
                                          <div class="write-post__col">
                                            <p class="label">{{__('campus.evaluation.attendance')}}</p>
                                            <div class="options">
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="attendance" id="att1" value="care_student" checked>
                                                <label class="form-check-label" for="att1">
                                                  {{__('campus.evaluation.care_student')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="attendance" id="att2" value="designated_seat">
                                                <label class="form-check-label" for="att2">
                                                  {{__('campus.evaluation.designated_seat')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="attendance" id="att3" value="electronic_attendance">
                                                <label class="form-check-label" for="att3">
                                                  {{__('campus.evaluation.electronic_attendance')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="attendance" id="att4" value="dont_care_student">
                                                <label class="form-check-label" for="att4">
                                                  {{__('campus.evaluation.dont_care_student')}}
                                                </label>
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="write-post__row">
                                          <div class="write-post__col">
                                            <p class="label">{{__('campus.evaluation.team_project')}}</p>
                                            <div class="options">
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="team_project" id="team1" value="a_lot" checked>
                                                <label class="form-check-label" for="team1">
                                                  {{__('campus.evaluation.a_lot')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="team_project" id="team2" value="normal">
                                                <label class="form-check-label" for="team2">
                                                  {{__('campus.evaluation.normal')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="team_project" id="team3" value="none">
                                                <label class="form-check-label" for="team3">
                                                  {{__('campus.evaluation.none')}}
                                                </label>
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="write-post__row">
                                          <div class="write-post__col">
                                            <p class="label">{{__('campus.evaluation.textbook')}} {{__('campus.evaluation.Can_select_multiple_options')}}</p>
                                            <div class="options">
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="textbook[]" id="text1" value="textbook" checked>
                                                <label class="form-check-label" for="text1">
                                                  {{__('campus.evaluation.textbook')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="textbook[]" id="text2" value="ppt">
                                                <label class="form-check-label" for="text2">
                                                  {{__('campus.evaluation.ppt')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="textbook[]" id="text3" value="none">
                                                <label class="form-check-label" for="text3">
                                                    {{__('campus.evaluation.none')}}
                                                </label>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                    <div class="write-post__box">
                                      <p class="write-post__title">{{__('campus.evaluation.exam')}}</p>
                                      <div class="write-post__info">
                                        <div class="write-post__row">
                                          <div class="write-post__col">
                                            <p class="label">{{__('campus.evaluation.number_of_times')}}</p>
                                            <div class="options">
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="number_times" id="time1" value="over_4_times" checked>
                                                <label class="form-check-label" for="time1">
                                                  4회 이상
{{--                                                  {{__('campus.evaluation.times',['times'=>4])}}--}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="number_times" id="time2" value="3_times">
                                                <label class="form-check-label" for="time2">
                                                 {{__('campus.evaluation.times',['times'=>3])}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="number_times" id="time3" value="2_times">
                                                <label class="form-check-label" for="time3">
                                                    {{__('campus.evaluation.times',['times'=>2])}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="number_times" id="time4" value="1_times">
                                                <label class="form-check-label" for="time4">
                                                    {{__('campus.evaluation.times',['times'=>1])}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="radio" name="number_times" id="time5" value="none">
                                                <label class="form-check-label" for="time5">
                                                    {{__('campus.evaluation.none')}}
                                                </label>
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="write-post__row">
                                          <div class="write-post__col">
                                            <p class="label">{{__('campus.evaluation.type')}} {{__('campus.evaluation.Can_select_multiple_options')}}</p>
                                            <div class="options">
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="type[]" id="type1" value="multiple_choices" checked>
                                                <label class="form-check-label" for="type1">
                                                  {{__('campus.evaluation.multiple_choices')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="type[]" id="type2" value="short_answer">
                                                <label class="form-check-label" for="type2">
                                                  {{__('campus.evaluation.short_answer')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="type[]" id="type3" value="t_f">
                                                <label class="form-check-label" for="type3">
                                                  {{__('campus.evaluation.tf')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="type[]" id="type4" value="short_essay">
                                                <label class="form-check-label" for="type4">
                                                  {{__('campus.evaluation.short_essay')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="type[]" id="type5" value="long_essay">
                                                <label class="form-check-label" for="type5">
                                                  {{__('campus.evaluation.long_essay')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="type[]" id="type6" value="oral">
                                                <label class="form-check-label" for="type6">
                                                  {{__('campus.evaluation.oral')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="type[]" id="type7" value="alternative">
                                                <label class="form-check-label" for="type7">
                                                  {{__('campus.evaluation.alternative')}}
                                                </label>
                                              </div>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="type[]" id="type8" value="other">
                                                <label class="form-check-label" for="type8">
                                                  {{__('campus.evaluation.other')}}
                                                </label>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                    <div class="write-post__box write-post__box--text">
                                      <p class="write-post__title">{{__('campus.evaluation.overall_score')}}</p>
                                      <div class="write-post__info">
                                        <div class="stars">
                                            <div class="box-info__title" id="rateYo"></div>
                                        </div>

                                        <div class="form-group">
                                          <textarea name="comments" id="" cols="30" rows="10" class="form-control form-control--textarea" style="height: 230px !important;" placeholder="{{__('campus.evaluation.placeholder_comments')}}"></textarea>
                                        </div>
                                      </div>
                                    </div>

                                    <div class="write-post__submit text-center">
                                      <button class="btn">{{__('campus.evaluation.submit')}}</button>
                                    </div>
                                  </div>

                              </div>
                        </div>
                    </form>
                </div>
                {{-- @else
                <div  class="text-right" >
                        <a href="{{route('public.member.login')}}" title="Login" style="color: #EC1469;">
                            <span class="">{{__('campus.evaluation.please_login_to_comments')}}</span>
                        </a>
                </div> --}}

                @endif
                @if($canViewComment)
              <div class="list">
                <div class="custom-scrollbar">
                @if ($evaluation->comments->count() > 0)
                    @foreach ($evaluation->comments as $item)
                    <div class="item">
                        <div class="row align-content-center">
                        <div class="col">
                            <div class="stars">
                                    <div id="voteComments{{$item->id}}" ></div>
                                    <script>
                                       $(function(){
                                            $('#voteComments{{$item->id}}').rateYo({
                                                rating: {{$item->votes}},
                                                ratedFill: "#EC1469",
                                                fullStar: true,
                                                starWidth : '17px',
                                                readOnly:true,
                                            });
                                       })

                                    </script>
                            </div>
                        </div>
                        <div class="col text-right">
                            <p class="item__date">{{date('Y/m/d', strtotime($item->created_at))}}</p>
                        </div>
                        </div>
                        <p class="item__content">{{$item->comments}}</p>
                        <p class="item__report">
                        <button style="border:none; background: none; color:#999999" data-target="#reportPost2{{$item->id}}" data-toggle="modal">
                          <span>
                            {{__('campus.evaluation.report_this_content')}}

                          </span>
                        </button>
                          @if(auth()->guard('member')->user()->role_member_id === 7)
                        <a href="/ewhaian/delete_comment/{{ $item->id }}" class="btn btn-primary mx-3">
                          <span style="color: #ffffff">
                            댓글 삭제
                          </span>
                        </a>
                          @endif
{{--                        <a href="#" title="">{{__('campus.evaluation.report_this_content')}}</a>--}}
                        </p>
                    </div>
                      <div class="item__content">
                        <div class="modal fade modal--confirm" id="reportPost2{{$item->id}}" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
                            <div class="modal-content" style="padding-right: 0px">
                              <div class="modal-body">
                                <form action="{{route('ewhaian.report')}}" method="POST" id="report_form{{$item->id}}">
                                  @csrf\
                                  <div>
                                    <input type="hidden" name="type_report" value="2">  <!-- report comment -->
                                    <input type="hidden" name="type_post" value="{{$type_post ?? 10 }}"> <!-- type post -->
                                    <input type="hidden" name="id_post" value="{{$item->id}}"> <!-- type post -->
                                    <div class="d-lg-flex align-items-center mx-3">
                                      <div class="d-lg-flex align-items-start flex-grow-1">
                                        <div class="form-group form-group--1 flex-grow-1 mb-3">
                                          <div class="md-form">
                                            <div class="custom-control custom-checkbox" style="margin: 10px 0;background-color: #dddddd;padding: 20px;">
                                              <div class="label">
                                                <!-- [훌리건 신고] -->
                                                {{__('comments.report_hooligan')}}
                                              </div>
                                              @if(auth()->guard('member')->check())
                                                <div style="margin: 10px 0">
                                                  신고자 : {{auth()->guard('member')->user()->id_login ?? ''}}
                                                  |  신고글번호  :  {{$item->id}}
                                                </div>
                                              @endif
                                              <div>신고에 신중해 주시길 바랍니다.
                                                <br>적절하지 않은 신고는 반영되지 않으며
                                                <br>불이익이 있을 수 있습니다.
                                                {{--                                      <div>{{__('comments.report_info')}}--}}
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="flex-line" style="display: flex">
                                      <div class="label">
                                      </div>
                                      <ul class="listColor">
                                        <li style="padding-bottom: 10px;">
                                          <div class="custom-control custom-checkbox mx-3">
                                            <input type="radio" class="custom-control-input checkbox_report_comment"
                                                   id="reason_option12{{$item->id}}" name="reason_option" value="1" checked
                                                   required>
                                            <label class="custom-control-label" for="reason_option12{{$item->id}}">
                                              <!-- 훌리건 의심 -->
                                              훌리건 의심
                                            </label>
                                          </div>
                                        </li>
                                        <li style="padding-bottom: 10px;">
                                          <div class="custom-control custom-checkbox mx-3">
                                            <input type="radio" class="custom-control-input checkbox_report_comment"
                                                   id="reason_option22{{$item->id}}" name="reason_option" value="2">
                                            <label class="custom-control-label" for="reason_option22{{$item->id}}">
                                              <!-- 회원에 대한 욕설 혹은 저격 -->
                                              {{__('comments.report_hooligan.insult_user')}}
                                            </label>
                                          </div>
                                        </li>
                                        <li style="padding-bottom: 10px;">
                                          <div class="custom-control custom-checkbox mx-3">
                                            <input type="radio" class="custom-control-input checkbox_report_comment"
                                                   id="reason_option32{{$item->id}}" name="reason_option" value="3">
                                            <label class="custom-control-label" for="reason_option32{{$item->id}}">
                                              <!-- 허위사실 유포 -->
                                              {{__('comments.report_hooligan.fake_info')}}
                                            </label>
                                          </div>
                                        </li>
                                        <li style="padding-bottom: 10px;">
                                          <div class="custom-control custom-checkbox mx-3">
                                            <input type="radio" class="custom-control-input checkbox_report_comment"
                                                   id="reason_option42{{$item->id}}" name="reason_option" value="4">
                                            <label class="custom-control-label" for="reason_option42{{$item->id}}">
                                              <!-- 게시 자료의 저작권 위반 -->
                                              {{__('comments.report_hooligan.copyright')}}
                                            </label>
                                          </div>
                                        </li>
                                        <li style="padding-bottom: 10px;">
                                          <div class="custom-control custom-checkbox mx-3">
                                            <input type="radio" class="custom-control-input checkbox_report_comment"
                                                   id="reason_option52{{$item->id}}" name="reason_option" value="5">
                                            <label class="custom-control-label" for="reason_option52{{$item->id}}">
                                              <!-- 일반인 신상정보 게시 -->
                                              {{__('comments.report_hooligan.post_personal_info')}}
                                            </label>
                                          </div>
                                        </li>
                                        <li style="padding-bottom: 10px;">
                                          <div class="custom-control custom-checkbox mx-3">
                                            <input type="radio" class="custom-control-input checkbox_report_comment"
                                                   id="reason_option62{{$item->id}}" name="reason_option" value="6">
                                            <label class="custom-control-label" for="reason_option62{{$item->id}}">
                                              <!-- 지나친 홍보 또는 상거래 유도 -->
                                              {{__('comments.report_hooligan.commerce')}}
                                            </label>
                                          </div>
                                        </li>
                                        <li style="padding-bottom: 10px;">
                                          <div class="custom-control custom-checkbox mx-3">
                                            <input type="radio" class="custom-control-input checkbox_report_comment"
                                                   id="reason_option72{{$item->id}}" name="reason_option" value="7">
                                            <label class="custom-control-label" for="reason_option72{{$item->id}}">
                                              <!-- 다른 게시판에 적절한 게시글 -->
                                              {{__('comments.report_hooligan.not_board')}}
                                            </label>
                                          </div>
                                        </li>
                                        <li style="padding-bottom: 10px;">
                                          <div class="custom-control custom-checkbox mx-3">
                                            <input type="radio"
                                                   class="custom-control-input checkbox_report_comment orther_report_comment"
                                                   id="reason_option82{{$item->id}}" name="reason_option" value="8">
                                            <label class="custom-control-label" for="reason_option82{{$item->id}}">
                                              <!-- 기타 -->
                                              {{__('comments.report_hooligan.extra')}}
                                            </label>
                                          </div>
                                        </li>
                                      </ul>
                                    </div>
                                  </div>
                                  <div class="d-lg-flex align-items-center mx-3">
                                    <div class="d-lg-flex align-items-start flex-grow-1">

                                      <div class="form-group form-group--1 flex-grow-1 mb-3">
                                        <div class="md-form hidden_comment_report">
                                                        <textarea id="reason2{{$item->id}}"
                                                                  class="md-textarea form-control comment_report"
                                                                  rows="5"
                                                                  minlength="10"
                                                                  name="reason" required
                                                                  style="border: none;border-bottom: 1px solid #e8e8e8;"
                                                        >N/A</textarea>
                                          <label for="reason2{{$item->id}}">신고 사유를 입력해주세요</label>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="button-group mb-2">
                                      <!-- <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">cancel</button> -->
                                      <button type="button" class="btn btn-cancel mr-lg-10" data-dismiss="modal">{{__('comments.report_hooligan.cancel')}}</button>
                                      <!-- <button type="submit" class="btn btn-primary">Send</button> -->
                                      <button type="submit" class="btn btn-ok">{{__('comments.report_hooligan.send')}}</button>
                                    </div>

                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                @else
                <div class="item" style="padding: 0px">
                        <p class="item__content">{{__('campus.evaluation.no_comments')}}</p>
                </div>
                @endif
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
{!! Theme::partial('modal-report' , [
    'type_report'=> '1',
    'type_post'=> '13',
    'id_post'=> $evaluation->id,
    'object' => $evaluation
]) !!}
  <script>
    $(function () {

        $('#totalVotes').rateYo({
            rating: {{ FLOOR($votes) ?? 5 }},
            ratedFill: "#EC1469",
            fullStar: true,
            starWidth : '24px',
            readOnly: true,
        });

        $('#showVote').on('click',function(){
            if($(this).attr('checkVote') == 0){
                $(this).attr('checkVote',1);
                $('.comments').attr('style','display:block')
            }else{
                $(this).attr('checkVote',0);
                $('.comments').attr('style','display:none');
            }
        })

        $("#rateYo").rateYo({

        rating: 5,
        ratedFill: "#EC1469",
        fullStar: true,
        starWidth : '24px'

        })
        .on("rateyo.set", function (e, data) {
            $('#starVote').val( data.rating);
        });

        $('#text3').on('click', function(){
            if($("#text3").is(':checked')){
                $("#text1").prop('checked', false);
                $("#text2").prop('checked', false);
            }
        });

        $('#text1').on('click', function(){
            if($("#text1").is(':checked')){
                $("#text3").prop('checked', false);
            }
        });

        $('#text2').on('click', function(){
            if($("#text2").is(':checked')){
                $("#text3").prop('checked', false);
            }
        })

    });

    $(document.body).on('click', '.checkbox_report_comment', function (e) {
        if ($('.orther_report_comment').is(':checked')) {
            $('.hidden_comment_report .comment_report').val('');
            $('.hidden_comment_report').show();
        } else {
            $('.hidden_comment_report').hide();
            $('.hidden_comment_report .comment_report').val('N/A');
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        var elements = document.getElementsByTagName("textarea");
        console.log(elements.length);
        for (var i = 0; i < elements.length; i++) {
            elements[i].oninvalid = function(e) {
                e.target.setCustomValidity("");
                if (!e.target.validity.valid) {
                    e.target.setCustomValidity("신고 사유는 최소 10자 이상 입력해주세요");
                }
            };
            elements[i].oninput = function(e) {
                if(e.target.value.length < 10){
                    e.target.setCustomValidity("신고 사유는 최소 10자 이상 입력해주세요");
                }else{
                    e.target.setCustomValidity("");
                }

            };
        }
    });
  </script>
