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
<main id="main-content" data-view="event-comments" class="home-page ewhaian-page">
  <div class="sidebar-template">
    <div class="sidebar-template__content" style="width: 100%">
      <div class="event-comments">
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
                <th class="d-none d-lg-block" style="text-align: center;width:13%;">{{__('life.open_space.date')}}</th>
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
                          {!! highlightWords2($item->detail,request('keyword'), 40 ) !!}{{' ('.$item->comments->count().')' }}
                          @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                        </a>
                      @else
                        <a href="{{route('life.open_space_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                          {!! highlightWords2($item->title,request('keyword'), 40 ) !!}{{' ('.$item->comments->count().')' }}
                          @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                        </a>
                      @endif
                    </td>

                    <td >
                      <div style="display: flex;justify-content: center;">
                        @if( !is_null($item->members)   )
                          {!! getStatusWriter($item->members->certification)  !!}
                        @else
                          {!! getStatusWriter('real_name_certification')  !!}
                        @endif
                        {{$item->members->nickname ?? __('life.open_space.admin')}}
                      </div>
                    </td>
                    <td class="d-none d-lg-block" style="text-align: center;">
                      {{ getStatusDateByDate2($item->created_at) }}
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

        @if(isset($openSpace) && $openSpace!=null )
          {!! Theme::partial('paging',['paging'=>$openSpace->appends(request()->input()) ]) !!}
        @else
          {!! Theme::partial('paging',['paging'=>$contents->appends(request()->input()) ]) !!}
        @endif


      </div>
    </div>
  </div>
</main>
