<style>
    .event-comments .filter--1 {
        max-width: unset;
    }
    td .active{
        color: #ffffff !important;
        background-color: #EC1469 !important;
    }
    .popular-search .popular__list  .popular__item .active{
        color: #ffffff !important;
        background-color: #EC1469 !important;
    }
    .popular__item {
        cursor: pointer;
    }
    .popular-search a.active,
    .popular-search a:hover {
        color: #ffffff !important;
        background-color: #EC1469 !important;
    }
</style>
<main id="main-content" data-view="event-comments" data-page="life" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->

                {!! Theme::partial('life.menu',['active'=>"open_space"]) !!}

                <!-- end of category menu -->
            </div>
            <div class="sidebar-template__content">
                <div class="event-comments">
                    <ul class="breadcrumb">
                        <li><a href="" title="{{__('life')}}">{{__('life')}}</a></li>
                        <li>
                            <svg width="4" height="6" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                            </svg>
                        </li>
                        <li>{{__('life.open_space')}}</li>
                    </ul>
                    <div class="heading">
                        <div class="heading__title">
                            {{__('life.open_space')}}
                        </div>
                        <div class="heading__description">
                            {!!$description->description ?? '' !!}
                        </div>
                    </div>

                    <!-- filter -->
                    <br>
                    <div class="tab-content">
                    <div class="popular-search">
                        <ul class="popular__list ">
                            <li class="popular__item">
                                <a class="alert bg-white  @if(request('categories_id') == '') active @endif all" href="javascript:{}" title="전체" data-value="" style="border: 1px solid #EC1469 ;">전체</a>
                            </li>

                            <li class="popular__item">
                                <a class="alert bg-purple @if(request('categories_id') == '0') active @endif" title="공동구매" data-value="0" id="children3">공동구매</a>
                            </li>

                            <li class="popular__item">
                                <a class="alert  bg-green @if(request('categories_id') == '1') active @endif" href="javascript:{}" title="분실" data-value="1" id="children4">분실</a>
                            </li>
                            <li class="popular__item">
                                <a class="alert  bg-yellow-1 @if(request('categories_id') == '2') active @endif  " href="javascript:{}" title="기타" data-value="2" id="children5">기타</a>
                            </li>
                        </ul>
                        <form id="categories_search" action="{{route('openSpace.search')}}" method="get">
                            <input type="hidden" name="categories_id" id="categories_id" value="">
                            <input type="hidden" name="style" value="{{request('style')}}">
                        </form>
                        <script>
                            $(function() {
                                $('.popular-search .popular__item .alert').on('click',function( e){
                                    $('#categories_id').val($(this).data('value'));
                                    $('#categories_search').submit();
                                })
                            })
                        </script>
                    </div>
                    </div>
                    <form action="{{route('openSpace.search')}}" method="GET" id="form-search-1">
                        <div class="filter align-items-center">
                            <div class="filter__item filter__title mr-3">{{__('life.open_space.search')}}</div>
                            <div class="filter__item d-flex align-items-md-center justify-content-md-center  mr-3">
                                <div class="d-flex align-items-center">
                                    <span class="arrow">
                                        <svg width="6" height="15" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                xlink:href="#icon_play_arrow"></use>
                                        </svg>
                                    </span>
                                    <input data-datepicker-start type="text"
                                        class="form-control form-control--date startDate" id="startDate"
                                        name="startDate" value="{{request('startDate') ?: getToDate() }}" autocomplete="off" readonly>
                                </div>
                                <span class="filter__connect">-</span>
                                <div class="d-flex align-items-md-center">
                                    <input data-datepicker-end type="text"
                                        class="form-control form-control--date endDate" id="endDate" name="endDate"
                                        value="{{request('endDate') ?: getToDate() }}" autocomplete="off" readonly>
                                    <span class="arrow arrow--next">
                                        <svg width="6" height="15" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                xlink:href="#icon_play_arrow"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="type">
                                    <option value="0" @if(request('type')==0) selected @endif>{{__('life.open_space.title')}}</option>
                                    <option value="1" @if(request('type')==1) selected @endif>{{__('life.open_space.details')}}</option>
                                </select>
                                <div class="form-group form-group--search  flex-grow-1  mx-3">
                                    <a href="javascript:{}" onclick="document.getElementById('form-search-1').submit();">
                                    <span class="form-control__icon">
                                        <svg width="14" height="14" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search">
                                            </use>
                                        </svg>
                                    </span>
                                    </a>
                                    <input type="text" class="form-control" placeholder="{{__('search.title_or_content')}}" name="keyword"
                                        value="{{request('keyword')}}">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="style" value="{{$style}}">
                        <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
                            style="display:none">
                        <input type="hidden" name="categories_id" value="{{request('categories_id') ?? ''}}">
                    </form>
                  @if(count($notices) > 0 )
                    @foreach ($notices as $notice)
                      <div class="notice-alert">
                        <div class="notice-alert__title" style="white-space: nowrap;">{{__('life.part-time_job.notice')}}</div>
                        <div class="notice-alert__description">
                          <a href="{{route('life.open_space.notices.detail',['id'=>$notice->id])}}">{!!$notice->name!!}</a>
                        </div>
                      </div>

                    @endforeach
                  @else
                    <div class="notice-alert">
                      <div class="notice-alert__title" style="white-space: nowrap;">{{__('life.part-time_job.notice')}}</div>
                      <div class="notice-alert__description">
                        <span> {{__('life.part-time_job.no_notices')}}</span>
                      </div>
                    </div>
                  @endif
                    <!-- end of filter -->
                    @if (session('err'))
                        <div class="alert alert-danger" style="display: block">
                            {{ session('err') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- table -->
                    @if(isset($openSpace) && $openSpace!=null )
                    <div style="overflow-x: auto;">
                        <table class="table table--content-middle" style="width: 100%">
                            <thead>
                                <tr>

                                    @if($style > 0)<th style="text-align: center; width:148px">{{__('life.open_space.images')}}</th> @endif
                                    <th style="text-align: center;width:13%;" >분류</th>
                                    <th style="text-align: center;" >{{__('life.open_space.title')}}</th>
                                    <th style="text-align: center;width:13%;">{{__('life.open_space.author')}}</th>
                                    <th class="" style="text-align: center;width:13%;">{{__('life.open_space.date')}}</th>
                                    <th style="text-align: center;width:13%;">{{__('life.open_space.lookup')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($openSpace)>0)

                                @foreach ($openSpace as $key => $item)
                                <tr style="opacity: {{100 - ($item->dislikes_count ?? 0) *10 }}%;">
                                    @if($style > 0)
                                    <td>
                                        <div class="item__image">
                                            @if(getNew($item->published))
                                                <div class="item__new">
                                                    <div class="item__rectangle" style="z-index: 1">

                                                    </div>
                                                    <div class="item__eye" style="z-index: 2;margin-right: -10px;margin-top: -10px">
                                                    <span class="icon-label-image">N</span>
                                                    </div>
                                                </div>
                                            @endif

                                            <a href="{{route('life.open_space_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                            <div class="table__image @if($item->status != 'publish') table__image--overlay d-flex align-items-center @endif"
                                                style="background-image: url('{{ geFirsttImageInArray($item->images,'thumb')}}')"
                                                width="150">
                                            </div>
                                            </a>
                                        </div>
                                    </td>
                                    @endif
                                    <td style="text-align: center;" >
                                        @switch($item->categories_id)
                                            @case(0)
                                                 <span class="alert bg-purple" title="공동구매">공동구매</span>
                                                @break
                                            @case(1)
                                                 <span class="alert  bg-green" title="분실">분실</span>
                                                 @break
                                             @case(2)
                                                 <span class="alert  bg-yellow-1" title="기타">기타</span>
                                                 @break

                                            @default
                                                 <span class="alert bg-white" title="전체">전체</span>
                                        @endswitch
                                     </td>
                                    <td >
                                        @if(request('type') == 1)
                                        <a href="{{route('life.open_space_details',['id'=>$item->id])}}" title=" {!! $item->detail !!}{{' ('.$item->comments->count().')' }}">
                                            {!! Theme::partial('show_title',['text'=>$item->detail ?? "No have details", 'keyword' => request('keyword'), 'num' => 35 ]) !!}
                                            {{' ('.$item->comments->count().')' }}
                                            @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                                        </a>
                                        @else
                                        <a href="{{route('life.open_space_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                            {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword'), 'num' => 35]) !!}
                                            {{' ('.$item->comments->count().')' }}
                                            @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                                        </a>
                                        @endif
                                    </td>

                                    <td >
                                        <div style="display: flex;justify-content: left;align-items: center;">
                                            @if( !is_null($item->members)   )
                                                {!! getStatusWriter($item->members->certification)  !!}
                                            @else
                                            {!! getStatusWriter('real_name_certification')  !!}
                                            @endif
                                            {{$item->members->nickname ?? __('life.open_space.admin')}}
                                        </div>
                                    </td>
                                    <td class="" style="text-align: center;">
                                        {{ getStatusDateByDate($item->created_at) }}
                                    </td>
                                    <td style="text-align: center;">
                                       {{$item->views}}
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" style="text-align: center">{{__('life.open_space.no_open_space')}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <!-- end of table -->
                    <br>
                    <!-- filter -->
                    <div id="form-search-2">
                      <div class="filter filter--1 align-items-end">
                        <button  type="button" class="filter__item btn btn-secondary"  onClick="window.location.href='{{route('life.open_space_list')}}'">
                          <svg width="18" height="20.781" aria-hidden="true">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
                          </svg>
                          <span>{{__('life.last_list')}}</span>
                        </button>

                          @if( auth()->guard('member')->check() && $canCreate )
                                <div style="text-align: right; margin-top: 20px;">
                                  <a href="{{route('openSpaceFE.create')}}" title="{{__('life.open_space.create_open_space')}}"
                                      class="filter__item btn btn-primary mx-3 btn3">
                                      <span>{{__('life.open_space.write')}}</span>
                                  </a>
                              </div>
                          @endif
                      </div>
                      </div>
                    <!-- end of filter -->

                    @if(isset($openSpace) && $openSpace!=null )
                    {!! Theme::partial('paging',['paging'=>$openSpace->appends(request()->input()) ]) !!}
                    @else
                    {!! Theme::partial('paging',['paging'=>$contents->appends(request()->input()) ]) !!}
                    @endif


                </div>
            </div>
        </div>
    </div>
</main>
