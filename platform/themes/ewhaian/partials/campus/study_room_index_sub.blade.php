<style>
  .table tr.disable {
    opacity: 0.4;
  }
  .flare_market_list {

    flex-direction: unset;
  }
  .item__image {
    overflow: hidden;
    position: relative;
  }
</style>
<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="sidebar-template__content" style="width:100%">
        <div class="content">
          <div class="table-responsive">
            <table class="table table--content-middle">
              <thead>
              @if($style > 0)<th style="text-align: center; width:148px">{{__('campus.study_room.images')}}</th> @endif
              <th style="text-align: center;">{{__('campus.study_room.classification')}}</th>
              <th style="text-align: center;">@if(request('type') == 1) {{__('campus.study_room.detail')}} @else {{__('campus.study_room.title')}} @endif</th>
              <th style="text-align: center;">{{__('campus.study_room.writer')}}</th>
              <th style="text-align: center;">{{__('campus.study_room.date')}}</th>
              <th style="text-align: center; ">{{__('campus.study_room.lookup')}}</th>
              </thead>
              <tbody>
              @if(count($studyRoom) > 0 )
                @foreach ($studyRoom as $item)
                  <tr>
                    @if($style > 0)
                      <td>
                        <div class="item__image">
                          @if(getNew($item->published,1))
                            <div class="item__new">
                              <div class="item__rectangle" style="z-index: 1">

                              </div>
                              <div class="item__eye" style="z-index: 2;margin-right: -10px;margin-top: -10px">
                                <span class="icon-label-image">N</span>
                              </div>
                            </div>
                          @endif

                          <a href="{{route('campus.study_room_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                            <div class="table__image @if($item->status != 'publish') table__image--overlay d-flex align-items-center @endif"
                                 style="background-image: url('{{geFirsttImageInArray($item->images,'thumb')}}')"
                                 width="150">
                            </div>
                          </a>
                        </div>
                      </td>
                    @endif
                    <td >
                      {!!
                      Theme::partial('classification',['categories'=>[$item->categories],'type'=>5,'link'=>'campus.elements.showCategories'
                      ]) !!}
                    </td>
                    <td> <a href="{{route('campus.study_room_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                        @if(request('type') > 0 )
                          {!! highlightWords2($item->detail ?? "No have details",request('keyword'),40) !!}
                          {{' ('.$item->comments->count().')' }}
                          @if(getNew($item->published,1)) <span class="icon-label">N</span>@endif
                        @else
                          {!! highlightWords2($item->title,request('keyword'),40) !!}{{' ('.$item->comments->count().')' }}
                          @if(getNew($item->published,1)) <span class="icon-label">N</span> @endif
                        @endif
                      </a>
                    </td>
                    <td >
                      <div style="display: flex;">
                        @if($item->member_id ==null || $item->getNameMemberById($item->member_id)
                        =="Anonymous" )
                          {!! getStatusWriter('real_name_certification')  !!}
                        @else
                          {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                        @endif
                        {{$item->getNameMemberById($item->member_id)}}
                      </div>
                    </td>
                    <td style="text-align: center;">
                      {{getStatusDateByDate($item->published)}}

                    </td>
                    <td style="text-align: center;">{{$item->lookup ?? 0}}</td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="5" style="text-align: center">{{__('campus.study_room.no_study_room')}}</td>
                </tr>
              @endif

              </tbody>
            </table>
          </div>

        </div>
        <div id="form-search-2">
          <div class="filter filter--1 align-items-end">
            <button type="button" class="filter__item btn btn-secondary"
                    onClick="window.location.href='{{route('campus.study_room_list')}}'">
              <svg width="18" height="20.781" aria-hidden="true">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
              </svg>
              <span>{{__('campus.study_room.last_list')}}</span>
            </button>

            @if($canCreate )
              <a href="{{route('studyRoomFE.create')}}" title="{{__('campus.study_room.create_study_room')}}"
                 class="filter__item btn btn-primary mx-3 btn3">
                <span style="white-space: nowrap;">{{__('campus.study_room.write')}}</span>
              </a>
            @endif
          </div>
        </div>
        {!! Theme::partial('paging',['paging'=>$studyRoom->appends(request()->input()) ]) !!}
      </div>

    </div>

  </div>
</main>
