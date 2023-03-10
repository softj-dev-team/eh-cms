<style>
  .event-comments .filter--1 {
    max-width: unset;
  }
</style>
<main id="main-content" data-view="event-comments" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="sidebar-template__content" style="width: 100%">
        <div class="event-comments">
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
                            {!! highlightWords2($item->detail,request('keyword' ),40)
                            !!}{{' ('.$item->comments->count().')' }}
                            @if(getNew($item->published,1)) <span class="icon-label">N</span>@endif
                          </a>
                        @else
                          <a href="{{route('event.cmt.detail',['id'=>$item->id])}}"
                             title="{{$item->title.' ('.$item->comments->count().')'   }}">
                            {!! highlightWords2($item->title,request('keyword') ,40)
                            !!}{{' ('.$item->comments->count().')' }}
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
                    <td colspan="4" style="text-align: center">{{__('event.event_comments.no_events')}}
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
          <div id="form-search-2">
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
