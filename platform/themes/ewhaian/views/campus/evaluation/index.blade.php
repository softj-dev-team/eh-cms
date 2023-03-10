<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            {!! Theme::partial('campus.menu',['active'=>"evaluation"]) !!}
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
                <div class="heading" style="display: flex;">
                    <div class="heading__title" style="white-space: nowrap;">
                        {{__('campus.evaluation')}}
                    </div>
                    <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                        {!!$description->description ?? __('campus.evaluation.no_have_description')!!}
                    </div>

                </div>
                <div class="heading">
                    <a href="{{route('campus.evaluation_comments_major')}}" title="{{__('campus.evaluation.view_lecture_evaluations_by_subject')}}" class="filter__item btn btn-primary mx-3 btn-reset-padding" style="margin-left: 0px !important;">
                        <span>{{__('campus.evaluation.view_lecture_evaluations_by_subject')}}</span>
                    </a>
                </div>

                <form action="{{route('evaluation.search')}}" method="GET" id="form-search-1">
                    <div class="filter align-items-center">
                        <div class="filter__item filter__title mr-3">{{__('campus.evaluation.search')}}</div>
                        <div class="filter__item d-flex align-items-md-center justify-content-md-center  mr-3 ">
                            <div class="d-flex align-items-center">
                                <span class="arrow">
                                    <svg width="6" height="15" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow">
                                        </use>
                                    </svg>
                                </span>
                                <input data-datepicker-start type="text"
                                    class="form-control form-control--date startDate" id="startDate" name="startDate"
                                    value="{{request('startDate') ?: getToDate() }}" autocomplete="off">
                            </div>
                            <span class="filter__connect">-</span>
                            <div class="d-flex align-items-md-center">
                                <input data-datepicker-end type="text" class="form-control form-control--date endDate"
                                    id="endDate" name="endDate" value="{{request('endDate') ?: getToDate() }}" autocomplete="off">
                                <span class="arrow arrow--next">
                                    <svg width="6" height="15" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow">
                                        </use>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
                            <div class="form-group form-group--search  flex-grow-1  mx-3">
                                <a href="javascript:{}" onclick="document.getElementById('form-search-1').submit();">
                                <span class="form-control__icon">
                                    <svg width="14" height="14" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use>
                                    </svg>
                                </span>
                                </a>
                                <input type="text" class="form-control" placeholder="교과목, 교수명" name="keyword"
                                    value="{{ request('keyword') }}">
                            </div>
                        </div>

                        <input type="submit" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
                            style="display:none">
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

            @if (session('err'))
                <div class="alert alert-danger" style="display: block">
                    {{ session('err') }}
                </div>
            @endif
            <div class="content">
                <div class=" table-responsive">
                    <table class="table">
                        <thead>
                            <th style="text-align: center;"  width="45%">{{__('campus.evaluation.title')}}</th>
                            <th style="text-align: center;"  >{{__('campus.evaluation.professor_name')}}</th>
                            <th style="text-align: center;" >{{__('campus.evaluation.date')}}</th>
                            <th style="text-align: center;" >{{__('campus.evaluation.lookup')}}</th>
                        </thead>
                        <tbody>
                            @if(!is_null($evaluation) && $evaluation->count() > 0 )
                            @foreach ($evaluation as $item)
                            <tr>
                                <td > <a href="{{route('campus.evaluation_details',['id'=>$item->id])}}" title="  {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                    {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword') ]) !!}
                                    {{' ('.$item->comments->count().')' }}
                                    </a>
                                </td>
                                <td style="text-align: center;">
                                    {!! highlightWords2($item->professor_name ?? "No Professor name",request('keyword')) !!}
                                </td>
                                <td style="text-align: center;">
                                    @if( getStatusDateByDate($item->created_at) == "Today" ) <span
                                        class="icon-label">N</span> @else {{getStatusDateByDate($item->created_at)}}
                                    @endif

                                </td>
                                <td style="text-align: center;">{{$item->lookup ?? 0}}</td>
                            </tr>
                            @endforeach
                            @else
                                @if(is_null($evaluation))
                                <tr>
                                    <td colspan="5" style="text-align: center">{{__('campus.evaluation.search_title')}}</td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="5" style="text-align: center">{{__('campus.evaluation.no_lecture')}}</td>
                                </tr>
                                @endif

                            @endif

                        </tbody>
                    </table>
                </div>

            </div>
            <div id="form-search-2">
                <div class="filter filter--1 align-items-end">
                    <button type="button" class="filter__item btn btn-secondary"
                        onClick="window.location.href='{{route('campus.evaluation_comments_major')}}'" >
                        <svg width="18" height="20.781" aria-hidden="true">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
                        </svg>
                        <span>{{__('campus.evaluation.last_list')}}</span>
                    </button>
                </div>
            </div>
            @if(!is_null($evaluation))
            {!! Theme::partial('paging',['paging'=>$evaluation->appends(request()->input()) ]) !!}
            @endif
        </div>

    </div>

    </div>
</main>
