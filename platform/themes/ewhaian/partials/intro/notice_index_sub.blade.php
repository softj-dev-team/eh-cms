<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="sidebar-template__content" style="width:100%">
        <div class="content">
          <div class="table-responsive">
            <table class="table">
              <thead>
              <th class="d-none d-lg-block" style="text-align: center; min-width:53px;">{{__('eh-introduction.notices.no')}}</th>
              <th style="text-align: center;">{{__('eh-introduction.notices.title')}}</th>
              <th style="text-align: center; min-width:105px;">{{__('eh-introduction.notices.date')}}</th>
              <th class="d-none d-lg-block" style="text-align: center; min-width:74px; ">{{__('eh-introduction.notices.lookup')}}</th>
              </thead>
              <tbody>
              @if (count($notices) > 0 )
                @foreach ($notices as $item)
                  <tr>
                    <td class="d-none d-lg-block" style="text-align: center;">{{$item->id}}</td>
                    <td><a href="{{route('eh_introduction.notices.detail',['id'=>$item->id])}}"
                           title="{{$item->name}}">
                        @if(request('type') > 0 )
                          {!! highlightWords2($item->notices ?? "No have details",request('keyword'))
                          !!}
                          @if(getNew($item->created_at)) <span class="icon-label">N</span>@endif
                        @else
                          {!! highlightWords2($item->name,request('keyword')) !!}
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
