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
        </style>
        <main id="main-content" data-view="lecture-evaluation" class="lecture-evaluation-page ewhaian-page">
            <div class="container">
              <div class="sidebar-template">
                <div class="sidebar-template__control">
                </div>
                <div class="sidebar-template__content">
                  <ul class="breadcrumb">
                    <li><a href="{{route('scheduleFE.timeline.v2')}}" title="{{__('campus')}}" target="_parent">{{__('campus')}}</a></li>
                    <li>
                      <svg width="4" height="6" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                      </svg>
                    </li>
                    <li>{{__('campus.evaluation')}}</li>
                  </ul>

                  <div class="heading">
                    <div class="heading__title">
                        {{__('campus.evaluation')}}
                    </div>
                    <p class="heading__description" style="display: none">{{__('campus.evaluation.heading__description')}}</p>
                  </div>

                  <div class="lecture-evaluation">
                    <div class="box-info">
                      <div class="box-info__heading">
                        <div class="row align-items-center">
                          <div class="col">
                            <p class="box-info__code">{{$evaluation->id}}</p>
                            <p class="box-info__title box-info__title--pink">{{$evaluation->title}}</p>
                          </div>
                          <div class="col text-right" style="display: flex;justify-content: flex-end">
                                <div style="font-size: 17px;">{{ round($votes,1)}}</div>
                            <div class="stars" style="display: flex;justify-content: flex-end">
                                <div id="totalVotes" ></div>
                            </div>
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
                          <span class="text">{{$evaluation->remark}}</span>
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
                                <span class="label">{{__('campus.evaluation.score')}}</span>
                                <span>{{ getNameByValue( $evaluation->getValueLecture('grade') ?? __('campus.evaluation.no_rating'))  }}</span>
                              </p>
                              <p>
                                <span class="label">{{__('campus.evaluation.assignment')}}</span>
                                <span>{{ getNameByValue( $evaluation->getValueLecture('assignment') ?? __('campus.evaluation.no_rating'))  }}</span>
                              </p>
                              <p>
                                <span class="label">{{__('campus.evaluation.attendance')}}</span>
                                <span>{{ getNameByValue( $evaluation->getValueLecture('attendance') ?? 'no_rating',[
                                    'care_student'=>'Care student',
                                    'designated_seat'=>'Designated seat',
                                    'electronic_attendance'=>'Electronic attendance',
                                    'dont_care_student'=>'Don\'t care student',
                                    'no_rating'=>__('campus.evaluation.no_rating'),
                                ] )  }}</span>
                              </p>
                              <p>
                                <span class="label">{{__('campus.evaluation.textbook')}}</span>
                                <span>{{  getNameByValue( $evaluation->getValueInArr('textbook') ?? __('campus.evaluation.no_rating') ) }}</span>
                              </p>
                              <p>
                                <span class="label">{{__('campus.evaluation.team_project')}}</span>
                                <span>{{ getNameByValue( $evaluation->getValueLecture('team_project') ?? __('campus.evaluation.no_rating') )  }}</span>
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
                                <span class="label">{{__('campus.evaluation.number_of_times')}}</span>
                                <span>{{ getNameByValue( $evaluation->getValueLecture('number_times') ?? __('campus.evaluation.no_rating') )  }}</span>
                              </p>
                              <p>
                                <span class="label">{{__('campus.evaluation.type')}}</span>
                                <span>{{  getNameByValue( $evaluation->getValueInArr('type') ?? __('campus.evaluation.no_rating') ) }}</span>
                              </p>
                            </div>
                          </div>
                        </div>
                    </div>

                    <div class="other-posts">
                      <div  class="text-right" style="display: none;">
                        <button class="btn text-right" id="showVote" checkVote="0" >{{__('campus.evaluation.write_lecture_evaluation')}}</button>
                        </div>

                        <div class="box-info comments" style="display:none;">
                            <div class="row">
                                    <div class="col" style="margin-bottom: 1.66667em;">
                                            <div class="box-info__title" id="rateYo"></div>
                                    </div>

                            </div>
                            <form action="{{route('campus.evaluation_comments',['id'=>$evaluation->id])}}" method="post">
                                    @csrf
                            <div class="box-info__table form-group" style="border: 1px solid #e8e8e8;height: 100px;">

                                        <input type="hidden" name="starVote" id="starVote" value="5">
                                        <input type="hidden" name="id"  value="{{$evaluation->id}}">
                                        <textarea name="comments" id="" cols="10" rows="3" class="form-control form-control--textarea" placeholder="{{ __('campus.evaluation.comment') }}" required="" style="padding: 1.07143em 1.42857em;"></textarea>

                            </div>
                            <div  class="text-right" >
                                    <button class="form-submit " type="submit">{{__('campus.evaluation.write')}}</button>
                            </div>
                        </form>
                        </div>

                        <div class="list">
                            <div >
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
                                    <a href="#" title="">{{__('campus.evaluation.report_this_content')}}</a>
                                    </p>
                                </div>
                                @endforeach
                            @else
                            <div class="item" style="padding: 0px">
                                    <p class="item__content">{{__('campus.evaluation.no_comments')}}</p>
                            </div>
                            @endif
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </main>

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
                        // $('.comments').attr('style','display:block')
                        // $('.comments').attr('style','display:block;height:300px')
                    }else{
                        $(this).attr('checkVote',0);
                        // $('.comments').attr('style','display:none');
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



            });
          </script>
