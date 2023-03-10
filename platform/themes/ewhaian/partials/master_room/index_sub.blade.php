<main id="main-content" data-view="home" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="sidebar-template__content" style="width:100%">
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
                    {!! highlightWords2($item->content ?? "No contents",request('keyword')) !!}{{' ('.$item->comments->count().')' }}
                    @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                  @else
                    {!! highlightWords2($item->title,request('keyword')) !!}{{' ('.$item->comments->count().')' }}
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
                        {!! highlightWords2($item->content ?? "No contents",request('keyword')) !!}{{' ('.$item->comments->count().')' }}
                        @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                      @else
                        {!! highlightWords2($item->title,request('keyword')) !!}{{' ('.$item->comments->count().')' }}
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
