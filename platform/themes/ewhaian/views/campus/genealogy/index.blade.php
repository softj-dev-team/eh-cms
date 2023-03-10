<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            {!! Theme::partial('campus.menu',['active'=>"genealogy"]) !!}
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    <li><a href="{{route('campus.evaluation_comments_major')}}" title="{{__('campus')}}">{{__('campus')}}</a></li>
                    <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    <li>{{__('campus.genealogy')}}</li>
                </ul>
                <div class="heading" style="display: flex;">
                    <div class="heading__title" style="white-space: nowrap;">
                        {{__('campus.genealogy')}}
                    </div>
                    <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                        {!!$description->description ?? __('campus.genealogy.no_have_description')!!}
                    </div>
                </div>

                <form action="{{route('genealogy.search')}}" method="GET" id="form-search-1">
                    <div class="filter align-items-center">
                      <div class="filter__item filter__title mr-3">{{__('campus.genealogy.search')}}</div>
                      <div class="filter__item d-flex align-items-md-center justify-content-md-center  mr-3 ">
                        <div class="d-flex align-items-center">
                          <span class="arrow">
                            <svg width="6" height="15" aria-hidden="true" class="icon">
                              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
                            </svg>
                          </span>
                          <input data-datepicker-start type="text" class="form-control form-control--date startDate"
                            id="startDate" name="startDate" value="{{request('startDate') ?: getToDate() }}" autocomplete="off">
                        </div>
                        <span class="filter__connect">-</span>
                        <div class="d-flex align-items-md-center">
                          <input data-datepicker-end type="text" class="form-control form-control--date endDate" id="endDate"
                            name="endDate" value="{{request('endDate') ?: getToDate() }}" autocomplete="off">
                          <span class="arrow arrow--next">
                            <svg width="6" height="15" aria-hidden="true" class="icon">
                              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
                            </svg>
                          </span>
                        </div>
                      </div>

                      <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
                        <select class="form-control form-control--select mx-3" name="type" value="{{ request('type') }}">
                          <option value="0" @if(request('type')==0) selected @endif>{{__('campus.genealogy.title')}}</option>
                          <option value="1" @if(request('type')==1) selected @endif>{{__('campus.genealogy.detail')}}</option>
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
                      <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
                        style="display:none">
                  </form>
                </div>
              @if(count($notices) > 0 )
                @foreach ($notices as $notice)
                  <div class="notice-alert">
                    <div class="notice-alert__title" style="white-space: nowrap;">{{__('campus.genealogy.notice')}}</div>
                    <div class="notice-alert__description">
                      <a href="{{route('campus.genealogy.notices.detail',['id'=>$notice->id])}}">{!!$notice->name!!}</a>
                    </div>
                  </div>

                @endforeach
              @else
                <div class="notice-alert">
                  <div class="notice-alert__title" style="white-space: nowrap;">{{__('campus.old_genealogy.notice')}}</div>
                  <div class="notice-alert__description">
                    <span> {{__('campus.old_genealogy.no_have_notices')}}</span>
                  </div>
                </div>
              @endif

                @if (session('err'))
                    <div class="alert alert-danger" style="display: block">
                        {{ session('err') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success" style="display: block">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="content">
                    <div class=" table-responsive">
                        <table class="table">
                            <thead>
                                <th class="text-center"  width="45%">@if(request('type') == 1) {{__('campus.genealogy.detail')}}  @else {{__('campus.genealogy.title')}} @endif</th>
                                <th class="d-none d-lg-block text-center">{{__('campus.genealogy.date')}}</th>
                                <th class="text-center">{{__('campus.genealogy.lookup')}}</th>
                            </thead>
                            <tbody>
                                @if(count($genealogy) > 0 )
                                @foreach ($genealogy as $item)
                                <tr>
                                    <td> <a href="{{route('campus.genealogy_details',['id'=>$item->id])}}" title="{!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                            @if(request('type') > 0 )
                                                {!! Theme::partial('show_title',['text'=>$item->detail ?? "No have details", 'keyword' => request('keyword') ]) !!}
                                                {{' ('.$item->comments->count().')' }}
                                            @else
                                               {!! titleGenealogy($item,request('keyword')) !!}
                                              {{' ('.$item->comments->count().')' }}

                                            @endif
                                        </a>
                                    </td>
                                    <td class="d-none d-lg-block text-center">
                                      {{getStatusDateByDate2($item->published)}}

                                    </td>
                                    <td class="text-center">{{$item->lookup ?? 0}}</td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                    <td colspan="5" style="text-align: center">{{__('campus.genealogy.no_genealogy')}}</td>
                            </tr>
                            @endif

                            </tbody>
                        </table>
                    </div>

                </div>
                <div id="form-search-2">
                    <div class="filter filter--1 align-items-end">
                        <button  type="button" class="filter__item btn btn-secondary"  onClick="window.location.href='{{route('campus.genealogy_list')}}'">
                            <svg width="18" height="20.781" aria-hidden="true">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
                            </svg>
                            <span>{{__('campus.genealogy.last_list')}}</span>
                        </button>

                        @if($canCreate)
                            <a href="{{route('genealogyFE.create')}}" title="{{__('campus.genealogy.create_genealogy')}}" class="filter__item btn btn-primary mx-3 btn3">
                                <span style="white-space: nowrap;">{{__('campus.genealogy.write')}}</span>
                            </a>
                        @endif
                    </div>
                </div>
                {!! Theme::partial('paging',['paging'=>$genealogy->appends(request()->input()) ]) !!}
            </div>

        </div>

    </div>
</main>
