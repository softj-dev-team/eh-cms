<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                 <!-- intro menu -->
                 {!! Theme::partial('intro.menu',['notices'=>1,'categories'=>$categories]) !!}
                 <!-- end of intro menu -->
            </div>
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    <li><a href="" title="{{__('eh-introduction')}}">{{__('eh-introduction')}}</a></li>
                    <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    <li>{{__('eh-introduction.notices')}}</li>
                </ul>
                <div class="heading">
                    <div class="heading__title">
                        {{__('eh-introduction.notices')}}
                    </div>
                </div>
                <form action="{{route('eh_introduction.notices.search')}}" method="GET" id="form-search-1">
                    <div class="filter align-items-center">
                        <div class="filter__item filter__title mr-3">{{__('eh-introduction.notices.search')}}</div>
                        <div class="filter__item d-flex align-items-md-center justify-content-md-center  mr-3">
                            <div class="d-flex align-items-center">
                                <span class="arrow">
                                    <svg width="6" height="15" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow">
                                        </use>
                                    </svg>
                                </span>
                                <input data-datepicker-start type="text"
                                    class="form-control form-control--date startDate" id="startDate1" name="startDate"
                                    value="{{request('startDate') ?: getToDate() }}" autocomplete="off">

                            </div>
                            <span class="filter__connect">-</span>
                            <div class="d-flex align-items-md-center">
                                <input data-datepicker-end type="text" class="form-control form-control--date endDate"
                                    id="endDate1" name="endDate" value="{{request('endDate') ?: getToDate() }}" autocomplete="off">

                                <span class="arrow arrow--next">
                                    <svg width="6" height="15" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow">
                                        </use>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
                            <select class="form-control form-control--select mx-3" name="type"
                                value="{{ request('type') }}">
                                <option value="0" @if(request('type')==0) selected @endif>{{__('eh-introduction.notices.title')}}</option>
                                <option value="1" @if(request('type')==1) selected @endif>{{__('eh-introduction.notices.detail')}}</option>
                            </select>
                            <div class="form-group form-group--search  flex-grow-1  mx-3">
                                <a href="javascript:{}" onclick="document.getElementById('form-search-1').submit();">
                                <span class="form-control__icon">
                                    <svg width="14" height="14" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use>
                                    </svg>
                                </span>
                                </a>
                                <input type="text" class="form-control" placeholder="{{__('search.title_or_content')}}" name="keyword"
                                    value="{{ request('keyword') }}">
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
                        style="display:none">
                </form>
                @if (session('err'))
                    <div class="alert alert-danger" style="display: block">
                        {{ session('err') }}
                    </div>
                @endif
                <div class="content">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th class="d-none d-lg-block" style="text-align: center; min-width:53px;">{{__('eh-introduction.notices.no')}}</th>
                                <th style="text-align: center;">{{__('eh-introduction.notices.title')}}</th>
                                <th style="text-align: center; min-width:105px;">{{__('eh-introduction.notices.date')}}</th>
                                <th class="d-none d-lg-block" style="text-align: center; min-width:74px;">{{__('eh-introduction.notices.lookup')}}</th>
                            </thead>
                            <tbody>
                                @if (count($notices) > 0 )
                                @foreach ($notices as $item)
                                <tr>
                                    <td class="d-none d-lg-block" style="text-align: center;">{{$item->id}}</td>
                                    <td><a href="{{route('eh_introduction.notices.detail',['id'=>$item->id])}}"
                                            title="{{$item->name}}">
                                            @if(request('type') > 0 )
                                            {!! Theme::partial('show_title',['text'=>$item->notices ?? "No have details", 'keyword' => request('keyword') ]) !!}
                                               @if(getNew($item->created_at)) <span class="icon-label">N</span>@endif
                                            @else
                                              {!! Theme::partial('show_title',['text'=>$item->name , 'keyword' => request('keyword') ]) !!}
                                               @if(getNew($item->created_at)) <span class="icon-label">N</span>@endif
                                            @endif
                                        </a>
                                    </td>

                                    <td style="text-align: center;">
                                        {{getStatusDateByDate($item->created_at)}}
                                    </td>
                                    <td class="d-none d-lg-block" style="text-align: center;">{{$item->lookup ?? 0}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" style="text-align: center">
                                        {{__('eh-introduction.notices.no_have_notice')}}
                                    </td>
                                </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
                {!! Theme::partial('paging',['paging'=>$notices->appends(request()->input()) ]) !!}
            </div>

        </div>

    </div>
</main>
