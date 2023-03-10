<style>
.form-control {
    border-bottom: none;
}
.form-submit {
    height: 30px;
    border-radius: 5px;
    color: #ffffff;
    padding: 0 20px;
    font-size: 12px;
    background-color: #EC1469;
    border: none;
}
#totalVotes {
    z-index: 1;
}
#rateYo {
    z-index: 1;
}
</style>
<main id="main-content" data-view="lecture-evaluation" class="lecture-evaluation-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
          {!! Theme::partial('campus.menu',['active'=>"evaluation"]) !!}
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

          <div class="event-details single">
            <div class="single__head">
              <h3 class="single__title title-main">{{$notices->name}}</h3>
              <div class="single__info" style="white-space: nowrap;">
                <div class="single__eye">
                  <svg width="16" height="10" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                  </svg>
                  {{$notices->lookup  ?? 0}}
                </div>
              </div>
            </div>


            <div class="editor">
              {!!$notices->notices !!}
            </div>
          </div>


{{--          // page index--}}
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
                          {!! highlightWords2($item->title,request('keyword')) !!}{{' ('.$item->comments->count().')' }}
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
          <div action="{{route('evaluation.search')}}" method="GET" id="form-search-2">
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
