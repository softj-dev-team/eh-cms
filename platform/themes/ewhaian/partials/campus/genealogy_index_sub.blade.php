<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="content" style="width:100%">
        <div class=" table-responsive">
          <table class="table">
            <thead>
            <th style="text-align: center;"  width="45%">@if(request('type') == 1) {{__('campus.genealogy.detail')}}  @else {{__('campus.genealogy.title')}} @endif</th>
            <th style="text-align: center;" >{{__('campus.genealogy.date')}}</th>
            <th style="text-align: center;" >{{__('campus.genealogy.lookup')}}</th>
            </thead>
            <tbody>
            @if(count($genealogy) > 0 )
              @foreach ($genealogy as $item)
                <tr>
                  <td> <a href="{{route('campus.genealogy_details',['id'=>$item->id])}}" title="{!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                      @if(request('type') > 0 )
                        {!! highlightWords2($item->detail ?? "No have details",request('keyword')) !!}{{' ('.$item->comments->count().')' }}
                      @else
                        {!! titleGenealogy($item,request('keyword')) !!}{{' ('.$item->comments->count().')' }}

                      @endif
                    </a>
                  </td>
                  <td style="text-align: center;">
                    @if( getStatusDateByDate($item->published) == "Today" )  <span class="icon-label">N</span> @else {{getStatusDateByDate($item->published)}}  @endif

                  </td>
                  <td style="text-align: center;">{{$item->lookup ?? 0}}</td>
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
