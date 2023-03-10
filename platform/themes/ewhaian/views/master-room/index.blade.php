<main id="main-content" data-view="home" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="sidebar-template__control">
        <!-- category menu -->
        <div class="category-menu">
          <h4 class="category-menu__title">{{__('master_room')}}</h4>
          <ul class="category-menu__links">
            @foreach ($categories as $item)
            <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
              <a href="{{route('masterRoomFE.list', ['idCategory'=>$item->id]) }}"
                title="{{$item->name}}">{{$item->name}}</a>
            </li>
            @endforeach
            <li class="category-menu__item">
              <a href="{{route('masterRoomFE.address.list')}}" title="{{__('master_room.address.list')}}">{{__('master_room.address.list')}}</a>
            </li>
          </ul>
        </div>
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
        <h3 class="title-main">{!!Theme::breadcrumb()->getCrumbs()[1]['label']!!}</h3> <br>
        {{-- --------------------------- --}}
        <!-- filter -->
        <form action="{{route('masterRoom.search',['idCategory'=>$idCategory])}}" method="GET" id="form-search-1">
          <div class="filter align-items-center">
            <div class="filter__item filter__title mr-3">{{__('master_room.search')}}</div>
            <div class="filter__item d-flex align-items-md-center justify-content-md-center  mr-3 ">
              <div class="d-flex align-items-center">
                <span class="arrow">
                  <svg width="6" height="15" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
                  </svg>
                </span>
                <input data-datepicker-start type="text" class="form-control form-control--date startDate"
                  id="startDate1" name="startDate" value="{{request('startDate') ?: getToDate() }}" autocomplete="off">
              </div>
              <span class="filter__connect">-</span>
              <div class="d-flex align-items-md-center">
                <input data-datepicker-end type="text" class="form-control form-control--date endDate" id="endDate1"
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
                <option value="0" @if(request('type')==0) selected @endif>{{__('master_room.title')}}</option>
                <option value="1" @if(request('type')==1) selected @endif>{{__('master_room.contents')}}</option>
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

            <input type="submit" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
              style="display:none">
        </form>
      </div>
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
      <table class="table table--custom table--event-comments">
        <thead>
          <tr>
            <th class="table__col table__col-title" style="text-align: center">
                    @if(request('type') > 0 )
                    {{__('master_room.contents')}}
                    @else
                    {{__('master_room.title')}}
                    @endif </th>
            <th class="table__col table__col--category " style="text-align: center">{{__('master_room.date')}}</th>
            <th style="text-align: center;">{{__('master_room.lookup')}}</th>
          </tr>
        </thead>
        <tbody>
          @if(count($masterRoom))
            @foreach ($masterRoom as $key => $item)
            <tr>
                <td class="table__col table__col--title"><a
                    href="{{route('masterRoomFE.detail',['idCategory'=>$idCategory,'id'=>$item->id])}}"
                    title="{{ strip_tags($item->title) }}{{' ('.$item->comments->count().')' }}">
                    @if(request('type') > 0 )
                        {!! Theme::partial('show_title',['text'=>$item->content ?? "No contents", 'keyword' => request('keyword') ]) !!}
                        {{' ('.$item->comments->count().')' }}
                        @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                    @else
                        {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword') ]) !!}
                        {{' ('.$item->comments->count().')' }}
                        @if(getNew($item->published)) <span class="icon-label">N</span>@endif

                    @endif
                    </a>
                </td>

                <td class="table__col table__col--category table__col--has-label" data-content="date"
                style="text-align: center;">{{ date('Y.m.d', strtotime($item->start)) }}</td>

                <td style="text-align: center">{!! $item->lookup !!}</td>
            </tr>
              @if($item->masterRoomReplies)
              @foreach ($item->masterRoomReplies as $key => $item)
                <tr>
                  <td class="table__col table__col--title">
                    &nbsp;&nbsp;┗<a href="{{route('masterRoomFE.reply.detail',['idCategory'=>$idCategory,'id'=>$item->id])}}"
                      title="{{ strip_tags($item->title) }}{{' ('.$item->comments->count().')' }}">
                      @if(request('type') > 0 )
                        {!! Theme::partial('show_title',['text'=>$item->content ?? "No contents", 'keyword' => request('keyword') ]) !!}
                        {{' ('.$item->comments->count().')' }}
                        @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                      @else
                        {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword') ]) !!}
                        {{' ('.$item->comments->count().')' }}
                        @if(getNew($item->published)) <span class="icon-label">N</span>@endif

                      @endif
                    </a>
                  </td>

                  <td class="table__col table__col--category table__col--has-label" data-content="date"
                      style="text-align: center;">{{ date('Y.m.d', strtotime($item->start)) }}</td>

                  <td style="text-align: center">{!! $item->lookup !!}</td>
                </tr>
              @endforeach
              @endif
            @endforeach
          @else
          <tr>
            <td colspan="4" style="text-align: center">{{__('master_room.no_contents')}}</td>
          </tr>
          @endif
        </tbody>
      </table>
      <!-- end of table -->

      <!-- filter -->
      <div id="form-search-2">
        <div class="filter filter--1 align-items-end">
          @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('masterRoomFE.create') )
          <a href="{{route('masterRoomFE.create',['idCategory'=>$idCategory])}}" title="{{__('master_room.create_master_room')}}" class="filter__item btn btn-primary mx-3 btn-reset-padding">
                <span>{{__('master_room.write')}}</span>
            </a>
          @endif
        </div>
      </div>
      <!-- end of filter -->
      {{-- --------------------------- --}}
      @if(isset($masterRoom) && $masterRoom!=null )
      {!! Theme::partial('paging',['paging'=>$masterRoom->appends(request()->input()) ]) !!}
      @else
      {!! Theme::partial('paging',['paging'=>$description->appends(request()->input()) ]) !!}
      @endif

      {{-- {!! Theme::partial('paging') !!} --}}
    </div>
  </div>
  </div>
  </div>
</main>
