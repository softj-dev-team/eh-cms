<style>
.btn-active {
    background-color: #EC1469;
    color: #ffffff;
    outline: none;
}
.btn-inactive {
    background-color: #F4F4F4;
    color: #444444;
    outline: none;
}
.btn-active:hover,.btn-active:focus {
    opacity: 0.7;
    background-color: #EC1469;
    color: #ffffff;
 }
.btn-inactive:hover, .btn-inactive:focus {
    opacity: 0.7;
    background-color: #F4F4F4;
    color: #444444;
 }
</style>
<main id="main-content" data-view="event-comments" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->
                {!! Theme::partial('campus.menu',['active'=>"evaluation"]) !!}
                <!-- end of category menu -->
            </div>
            <div class="sidebar-template__content">
                <div class="event-comments">
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
                      <div class="heading" style="display: flex;">
                        <div class="heading__title" style="white-space: nowrap;">
                            {{__('campus.evaluation')}}
                        </div>
                        <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                            {!!$description->description ?? ""!!}
                        </div>

                    </div>
                    <div class="heading">
                      <a href="{{route('campus.evaluation_comments_major')}}" title="{{__('campus.evaluation.view_lecture_evaluations_by_subject')}}" class="filter__item btn btn-inactive mr-4 ml-0">
                          <span>{{__('campus.evaluation.view_lecture_evaluations_by_subject')}}</span>
                      </a>
                      <a href="{{route('campus.evaluation_comments_lastest')}}" title="{{__('campus.evaluation.view_the_latest_course_reviews')}}" class="filter__item btn btn-active ml-0">
                        <span>{{__('campus.evaluation.view_the_latest_course_reviews')}}</span>
                      </a>
                    </div>
                    <!-- table -->
                    <table class="table table--custom table--event-comments" style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="text-align: center;" >{{__('campus.evaluation.title')}}</th>
                                <th style="text-align: center;" >{{__('campus.evaluation.professor_name')}}</th>
                                <th style="text-align: center; width: 85px">{{__('campus.evaluation.votes')}}</th>
                                <th style="text-align: center;" >{{__('campus.evaluation.date')}}</th>
                                <th style="text-align: center;" ></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($comments->count() > 0)

                            @foreach ($comments as $key => $item)
                            <tr data-toggle="collapse" data-target="#demo{{$key}}" class="events_c">
                                <td class="table__col table__col-title" width="40%">
                                    <a href="{{route('campus.evaluation_details',['id'=>$item->evaluation->id])}}" title="{!! $item->evaluation->title !!}">
                                        {!! highlightWords2($item->evaluation->title,request('keyword')) !!}
                                    </a>
                                </td>
                                <td class="table__col table__col--category table__col--has-label text-center">
                                        {{ $item->evaluation->professor_name}}
                                </td>
                                <td class="table__col table__col--title text-center">
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
                                </td>
                                <td class="table__col table__col-title text-center">{{getStatusDateByDate2($item->created_at)}}</td>
                                <td class="table__col table__col--action table__col--has-label">
                                  {{-- <button type="button" class="table__btn events_comments">
                                    <svg width="16" height="7" aria-hidden=" true" class="icon">
                                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow">
                                      </use>
                                    </svg>
                                  </button> --}}
                                  <i class="fas fa-angle-down table__btn events_comments" data-toggle="collapse" data-target="#demo{{$key}}"></i>
                                </td>
                            </tr>
                            {{-- accordian-body collapse --}}
                            <tr style="padding: 0 !important;">
                                <td colspan="5" class="single comments item" style="padding: 0 !important;">
                                    <div class="event-details single accordian-body collapse" id="demo{{$key}}"
                                        style="margin-bottom: 30px;">

                                        <!-- comments -->
                                        <div class="comments" style="margin-top:0px;">
                                            <div class="comments__list">
                                                @if(!is_null($item->comments))
                                                <div class="item">
                                                    <div class="item__content">
                                                    {!! $item->comments !!}
                                                    </div>
                                                </div>
                                                @else
                                                <div class="item">{{__('campus.evaluation.no_comments')}}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- end of comments -->


                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4" style="text-align: center">{{__('campus.evaluation.no_comments')}}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <!-- end of table -->
                    @if(!is_null($comments) )
                        {!! Theme::partial('paging',['paging'=>$comments->appends(request()->input()) ]) !!}
                    @endif

                </div>
            </div>
        </div>
    </div>
</main>
