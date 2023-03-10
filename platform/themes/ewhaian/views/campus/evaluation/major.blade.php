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
                      <a href="{{route('campus.evaluation_comments_major')}}" title="{{__('campus.evaluation.view_lecture_evaluations_by_subject')}}" class="filter__item btn btn-active mx-3" style="margin-left: 0px !important; width: 207px;">
                        <span>{{__('campus.evaluation.view_lecture_evaluations_by_subject')}}</span>
                      </a>
                      <a href="{{route('campus.evaluation_comments_lastest')}}" title="{{__('campus.evaluation.view_the_latest_course_reviews')}}" class="filter__item btn btn-inactive mx-3" style="margin-left: 0px !important; width: 207px;">
                          <span>{{__('campus.evaluation.view_the_latest_course_reviews')}}</span>
                      </a>
                    </div>
                    @if ($errors->any())
                    <div >
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="alert alert-danger" style="display: block">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
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
                            <li class="alert alert-success" style="display: block">{{ session('success') }}</li>
                        </ul>
                    </div>
                    @endif
                    <div class="heading">
                        <form action="{{route('evaluation.major.search')}}" method="GET" id="form-search-1">
                            <div class="filter align-items-center">

                                <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
                                    <select class="form-control form-control--select mx-3" name="major" value="{{request('major')}}">
                                        @if(! request('major') )
                                            <option selected="selected" hidden="hidden" value="">{{__('campus.evaluation.choose_major')}}</option>
                                        @endif

                                        @foreach ($categories as $item)
                                        @if( $item->getChild()->count() > 0 )
                                        <optgroup label="{{$item->name}}">
                                            @foreach ($item->getChild() as $subitem)
                                                <option  @if(request('major') == $subitem->id) selected="selected" @endif value="{{$subitem->id}}">{{$subitem->name}}</option>
                                            @endforeach
                                        </optgroup>
                                        @else
                                        <option @if(request('major') == $item->id) selected="selected" @endif value="{{$item->id}}">{{$item->name}}</option>
                                        @endif

                                        @endforeach
                                    </select>

                                    <div class="form-group form-group--search  flex-grow-1  mx-3">
                                        <input type="text" class="form-control" placeholder="{{__('campus.evaluation.enter_title')}}" name="title" value="{{request('title')}}">
                                    </div>
                                    <div class="form-group form-group--search  flex-grow-1  mx-3">
                                      <a href="javascript:{}" onclick="document.getElementById('form-search-1').submit();">
                                        <span class="form-control__icon">
                                            <svg width="14" height="14" aria-hidden="true" class="icon">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use>
                                            </svg>
                                        </span>
                                      </a>
                                        <input type="text" class="form-control" placeholder="{{__('campus.evaluation.enter_professor_name')}}" name="professor_name" value="{{request('professor_name')}}">
                                    </div>
                                </div>

                                <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
                                    style="display:none">
                            </div>
                        </form>

                    </div>
                  @if(count($notices) > 0 )
                    @foreach ($notices as $notice)
                      <div class="notice-alert">
                        <div class="notice-alert__title" style="white-space: nowrap;">{{__('campus.genealogy.notice')}}</div>
                        <div class="notice-alert__description">
                          <a href="{{route('campus.evaluation.notices.detail',['id'=>$notice->id])}}">{!!$notice->name!!}</a>
                        </div>
                      </div>

                    @endforeach
                  @else
                    <div class="notice-alert">
                      <div class="notice-alert__title" style="white-space: nowrap;">{{__('campus.genealogy.notice')}}</div>
                      <div class="notice-alert__description">
                        <span> {{__('campus.genealogy.no_have_notices')}}</span>
                      </div>
                    </div>
                  @endif
                    <!-- table -->
                    <table class="table table--custom table--event-comments" style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="text-align: center;width: 135px;"  style="">{{__('campus.evaluation.major')}}</th>
                                <th style="text-align: center; padding-left: 0px" >{{__('campus.evaluation.title')}}</th>
                                <th style="text-align: center; " >{{__('campus.evaluation.professor_name')}}</th>
                                <th style="text-align: center;width: 77px;" >{{__('campus.evaluation.rating')}}</th>
                                <th style="text-align: center; " >{{__('campus.evaluation.votes')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($evaluation->count() > 0)

                            @foreach ($dataEvaluations as $key => $item)
                            <tr style="text-align: center; " >
                                <td class="table__col table__col-title" style="text-align: center; " >
                                    <span class="text">
                                        {{-- @if( request('major') )
                                          {{ $item->major->firstWhere('id',request('major') )->name}}
                                        @else
                                          {{ $item->major->first()->name}}
                                        @endif --}}
                                        @foreach ($item->major as $keyMajor => $major)
                                          @if (count($item->major) == ($keyMajor + 1) )
                                              {{ $major->name }}
                                          @else
                                              {{ $major->name .', ' }}
                                          @endif
                                        @endforeach
                                    </span>
                                </td>
                                <td class="table__col table__col--title" style="text-align: center; " >
                                    <a href="{{route('campus.evaluation_details',['id'=> $item->id])}}">
                                        {!! highlightWords2($item->title,request('title')) !!}
                                    </a>
                                </td>
                                <td class="table__col table__col--category" style="text-align: center; " >
                                    {!! highlightWords2($item->professor_name,request('professor_name')) !!}
                                </td>
                                <td class="table__col table__col-title " style="text-align: center; " >{{$item->comments_count}}</td>
                                <td class="table__col table__col--action table__col--has-label" style="text-align: center; " >
                                    @if ($item->comments_count > 0 )
                                    <div id="voteComments{{$item->id}}" ></div>
                                    <script>
                                       $(function(){
                                            $('#voteComments{{$item->id}}').rateYo({
                                                rating: {{  FLOOR($item->avg_comment) }},
                                                ratedFill: "#EC1469",
                                                fullStar: true,
                                                starWidth : '17px',
                                                readOnly:true,
                                            });
                                       })

                                    </script>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" style="text-align: center"> {{__('campus.evaluation.no_lecture_evaluation')}}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <!-- end of table -->
                    @if(!is_null($evaluation) )
                        {!! Theme::partial('paging',['paging'=>$evaluation->appends(request()->input()) ]) !!}
                    @endif

                </div>
            </div>
        </div>
    </div>
</main>
