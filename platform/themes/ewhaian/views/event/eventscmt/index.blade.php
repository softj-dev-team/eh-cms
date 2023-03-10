<style>
  .event-comments .filter--1 {
    max-width: unset;
  }
</style>
<main id="main-content" data-view="event-comments" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="sidebar-template__control">
        <!-- category menu -->
        <div class="category-menu">
          <h4 class="category-menu__title">{{__('event.menu__title')}}</h4>
          <ul class="category-menu__links">

            @foreach ($category as $item)
              <li class="category-menu__item">
                <a href="{{route('event.event_list', ['idCategory'=>$item->id]) }}" title="{{@$item->name}}">
                  {{@$item->name}}
                </a>
              </li>
            @endforeach
            <li class="category-menu__item active">
              <a href="{{route('event.cmt.list')}}"
                 title="{{__('event.event_comments')}}">{{__('event.event_comments')}}</a>
            </li>

          </ul>
        </div>
        <!-- end of category menu -->
      </div>
      <div class="sidebar-template__content">
        <div class="event-comments">
          <ul class="breadcrumb">
            @foreach (Theme::breadcrumb()->getCrumbs() as $i => $crumb)
              @if ($i != (count(Theme::breadcrumb()->getCrumbs()) - 1))
                <li>
                  <a href="{{ $crumb['url'] }}" title="{!! $crumb['label'] !!}">{!! $crumb['label'] !!}</a>
                  <svg width="4" height="6" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                  </svg>
                </li>
              @else
                <li class="active">{!! $crumb['label'] !!}</li>
              @endif
            @endforeach
          </ul>
          <h3 class="title-main">{{__('event.event_comments.list')}}</h3>


        <!-- filter -->
          <form action="{{route('events.search')}}" method="GET" id="form-search-1">
            <div class="filter align-items-center">
              <div class="filter__item filter__title mr-3">{{__('event.event_comments.search')}}</div>
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
                         name="startDate" value="{{request('startDate') }}" autocomplete="off">
                </div>
                <span class="filter__connect">-</span>
                <div class="d-flex align-items-md-center">
                  <input data-datepicker-end type="text"
                         class="form-control form-control--date endDate" id="endDate" name="endDate"
                         value="{{request('endDate') }}" autocomplete="off">
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
                  <option value="0" @if(request('type')==0) selected @endif>
                    {{__('event.event_comments.title')}}</option>
                  <option value="1" @if(request('type')==1) selected @endif>
                    {{__('event.event_comments.details')}}</option>
                </select>
                <div class="form-group form-group--search  flex-grow-1  mx-3">
                  <a href="javascript:{}"
                     onclick="document.getElementById('form-search-1').submit();">
                                        <span class="form-control__icon">
                                            <svg width="14" height="14" aria-hidden="true" class="icon">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#icon_search">
                                                </use>
                                            </svg>
                                        </span>
                  </a>
                  <input type="text" class="form-control"
                         placeholder="{{__('search.title_or_content')}}" name="keyword"
                         value="{{request('keyword')}}">
                </div>
              </div>
            </div>
            <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
                   style="display:none">
          </form>
          <!-- end of filter -->

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
        <!-- table -->
          @if(isset($events) && $events!=null )
            <div style="overflow-x: auto;">
              <table class="table table--event-comments" style="width: 100%;">
                <thead>
                <tr>
                  @if(request('type') == 1)
                    <th>{{__('event.event_comments.details')}}</th>
                  @else
                    <th>{{__('event.event_comments.title')}}</th>
                  @endif
                  <th style="text-align: center;width: 100px">{{__('event.event_comments.category')}}
                  </th>
                  <th style="text-align: center;width: 90px">{{__('event.event_comments.writer')}}
                  </th>
                  <th style="text-align: center;width: 90px">{{__('event.event_comments.date')}}</th>
                  <th style="text-align: center;width: 60px">{{__('event.event_comments.views')}}</th>
                </tr>
                </thead>
                <tbody>
                @if(count($events)>0)

                  @foreach ($events as $key => $item)
                    <tr>
                      <td>
                        @if(request('type') == 1)
                          <a href="{{route('event.cmt.detail',['id'=>$item->id])}}"
                             title="{!! $item->title.' ('.$item->comments->count().')'    !!}">
                            {!! Theme::partial('show_title',['text'=>$item->detail ?? "No have details", 'keyword' => request('keyword') ]) !!}
                            {{' ('.$item->comments->count().')' }}
                            @if(getNew($item->published,1)) <span class="icon-label">N</span>@endif
                          </a>
                        @else
                          <a href="{{route('event.cmt.detail',['id'=>$item->id])}}"
                             title="{{$item->title.' ('.$item->comments->count().')'   }}">
                            {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword') ]) !!}
                            {{' ('.$item->comments->count().')' }}
                            @if(getNew($item->published,1)) <span class="icon-label">N</span>@endif
                          </a>
                        @endif
                      </td>
                      <td style="text-align: center;">
                        {{@$item->category_events->name}}
                      </td>
                      <td>
                        <div style="display: flex">
                          @if( !is_null($item->members) )
                            {!! getStatusWriter($item->members->certification) !!}
                          @else
                            {!! getStatusWriter('real_name_certification') !!}
                          @endif
                          {{$item->members->nickname ?? "Admin"}}
                        </div>
                      </td>
                      <td style="text-align: center;">
                        {{ getStatusDateByDate($item->updated_at) }}
                      </td>
                      <td style="text-align: center;">
                        {{$item->views ?? 0}}
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="5" style="text-align: center">{{__('event.event_comments.no_events')}}
                    </td>
                  </tr>
                @endif
                </tbody>
              </table>
            </div>
          @endif
        <!-- end of table -->
          <br>
          <!-- filter -->
          <div action="{{route('events.search')}}" method="GET" id="form-search-2">
            <div class="filter filter--1 align-items-end">
              @if(!is_null( auth()->guard('member')->user() ) &&
              auth()->guard('member')->user()->hasPermission('eventsFE.cmt.create') )
                <div style="text-align: right; margin-top: 20px;">
                  <a href="{{route('eventsFE.cmt.create')}}" title="Create Events Comments"
                     class="filter__item btn btn-primary mx-3 btn3">
                    <span>{{__('event.event_comments.write')}}</span>
                  </a>
                </div>
              @endif
            </div>
          </div>
          <!-- end of filter -->
          @if(isset($events) && $events!=null )
            {!! Theme::partial('paging',['paging'=>$events->appends(request()->input()) ]) !!}
          @else
            {!! Theme::partial('paging',['paging'=>$contents->appends(request()->input()) ]) !!}
          @endif

        </div>
      </div>
    </div>
  </div>
</main>
