<style>
  .item__new {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
  }

  .item__rectangle {
    position: absolute;
    top: -59px;
    right: -60px;
    width: 118px;
    height: 118px;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
    background: #EC1469;
    transform: rotate(45deg);
  }

  .icon-label {
    width: 2em;
    height: 2em;
    display: inline-block;
    border: 1px solid #EC1469;
    color: #EC1469;
    border-radius: 50%;
    text-align: center;
    line-height: 2em;
    font-weight: 700;
    font-size: 0.71429em;
    margin-left: 0px !important;
  }

  .item__image {
    overflow: hidden;
    border: 1px solid #e8e8e8;
    border-bottom: none;
  }

  .item__new2 {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
  }

  .item__rectangle2 {
    position: absolute;
    top: -59px;
    right: -60px;
    width: 92px;
    height: 92px;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
    background: #EC1469;
    transform: rotate(45deg);
  }

  .icon-label-image2 {
    width: 2em;
    height: 2em;
    display: inline-block;
    border: 1px solid #FFFFFF;
    border-radius: 50%;
    color: #FFFFFF;
    text-align: center;
    line-height: 2em;
    font-weight: 700;
    font-size: 0.61429em;
  }

  .btn2:hover {
    border: 1px solid #EC1469;
  }
  .btn2 {
    border: 1px solid #EC1469;
    background-color: white;
    color: #EC1469 !important;
    padding: 10px;
    margin-right: 10px;
  }
  .btn_active {
    border: 1px solid #EC1469;
    background: #e8e8e8;
  }
  .table-w thead th{
    padding-right:0;
    padding-left:0;
  }
  .table-w tbody tr td:nth-child(3) div{
    word-break: keep-all;
  }
</style>
<main id="main-content" data-view="home" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
        <div class="event-list" style="width: 100%">
          @switch($style)
            @case(1)
            <div class=" table-responsive">
              <table class="table table--content-middle table-w">
                <thead>
                <th style="text-align: center;">{{__('event.title')}}
                </th>
                <th style="text-align: center;width: 10%;min-width: 80px;">{{__('event.deadline')}}
                </th>
                <th style="text-align: center;width: 18%;min-width: 80px;">{{__('event.writer')}}
                </th>
                <th style="text-align: center;width: 10%;min-width: 50px;">{{__('event.lookup')}}
                </th>
                </thead>
                <tbody>
                @if(count($events) > 0 )
                  @foreach ($events as $item)
                    <tr @if (getDeadlineDate( $item->end ) == "Expired") class="disable" @endif>
                      <td>
                        <a href="{{route('event.details',['id'=>$item->id,'idCategory'=>$item->category_events->id])}}"
                           title="{{ strip_tags($item->title) }}{{' ('.$item->comments->count().')' }}">
                          {{ strip_tags($item->title) }}{{' ('.$item->comments->count().')' }}
                          @if(getNew($item->published,1)) <span class="icon-label" >N</span>@endif
                        </a>
                      </td>
                      <td class="@if(getDeadlineDate( $item->end )  == " D-day") main-color @endif
                        text-center">{{ getDeadlineDate( $item->end ) }}</td>
                      <td>
                        <div style="display: flex">
                          @if($item->member_id ==null || $item->getNameMemberById($item->member_id)
                          =="Anonymous" )
                            {!! getStatusWriter('real_name_certification') !!}
                          @else
                            {!! getStatusWriter($item->getStatusMember($item->member_id)) !!}
                          @endif
                          {{$item->getNameMemberById($item->member_id)}}
                        </div>
                      </td>
                      @if($style == 0)
                        <td style="text-align: center">
                          {{getStatusDateByDate($item->published)}}
                        </td>
                      @endif
                      <td style="text-align: center">{{$item->views ?? 0}}</td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="6" style="text-align: center">
                      {{__('event.no_events')}}</td>
                  </tr>
                @endif
                </tbody>
              </table>
            </div>
            @break
            @case(2)
            <div class=" table-responsive">
              <table class="table table--content-middle">
                <thead>
                <th  style="text-align: center;width:148px"> {{__('contents.banner')}}</th>
                <th  style="text-align: center;padding-left: 0px;">
                  @if(request('type') > 0 )
                    {{__('contents.description')}}
                  @else
                    {{__('contents.title')}}
                  @endif
                </th>
                </thead>
                <tbody>
                @if(count($events))
                  @foreach ($events as $key => $item)
                    <tr @if( getDeadlineDate($item->end) == "Expired") style=" -webkit-filter: grayscale(100%); filter: grayscale(100%);" @endif>
                      <td>
                        <div class="item__image">
                          @if(getNew($item->published))
                            <div class="item__new2">
                              <div class="item__rectangle2" style="z-index: 1">

                              </div>
                              <div class="item__eye" style="z-index: 2;margin-right: -10px;margin-top: -10px">
                                <span class="icon-label-image2">N</span>
                              </div>
                            </div>
                          @endif

                          <a href="{{route('event.details',['id'=>$item->id,'idCategory'=>$item->category_events->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                            <div class="table__image @if($item->status != 'publish') table__image--overlay d-flex align-items-center @endif"
                                 style="background-image: url('{{ geFirsttImageInArray([$item->banner],'featured')}}')">
                            </div>
                          </a>
                        </div>
                      </td>
                      <td >
                        <a style="display: block" href="{{route('event.details',['idCategory'=>$idCategory,'id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                          <div class="row" style="margin: 0px">
                            <div class="col-5">
                              <div  >
                                <div style="font-weight: bold;">
                                  {!! highlightWords2($item->title,request('keyword'),20) !!}{{' ('.$item->comments->count().')' }}
                                  @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                                </div>

                                <div>
                                  {!! highlightWords2($item->content ?? "No contents",request('keyword'), 25) !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-3">
                              <div>
                                @if($item->member_id ==null || $item->getNameMemberById($item->member_id) =="Anonymous" )
                                  {!! getStatusWriter('real_name_certification')  !!}
                                @else
                                  {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                                @endif
                                {{$item->members->nickname ?? __('comments.anonymous')}}
                              </div>
                            </div>

                            <div class="col-2" style="display: flex;padding: 0px;">
                              <div  style="background: url('{{Theme::asset()->url('img/clock.png')}}') no-repeat center; background-size:contain; width: 15px;height: 15px;margin: 4px 4px 0 4px;"></div>
                              {{getStatusDateByDate($item->published)}}
                            </div>
                            <div class="col-2" style="display: flex">
                              <div class="single__info">
                                <div class="single__eye">
                                  <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                  </svg>
                                  {{ $item->lookup ?? 0}}
                                </div>
                              </div>
                            </div>

                          </div>
            </div>
            </a>
            </td>
            </tr>
            @endforeach
            @else
              <tr>
                <td colspan="4" style="text-align: center">{{__('event.no_events')}}</td>
              </tr>
              @endif
              </tbody>
              </table>
        </div>
        @break
        @default
        <div class="row">
          @if(count($events) > 0 )
            @foreach ($events as $item)
              <a href="{{route('event.details',['id'=>$item->id,'idCategory'=>$item->category_events->id])}}"
                 title="{{ strip_tags($item->title) }}">
                <div class="col-lg-4 col-md-6">
                  <div class="item" @if( getDeadlineDate($item->end) == "Expired") style=" -webkit-filter:
                                        grayscale(100%); filter: grayscale(100%);" @endif>
                    <div class="item__image">
                      @if(getNew($item->published,1))
                        <div class="item__new">
                          <div class="item__rectangle" style="z-index: 1">

                          </div>
                          <div class="item__eye" style="z-index: 2;margin-right: -9px;">
                            <span class="icon-label" style="border: 1px solid white;color: white">N</span>
                            <div style="margin-left: -4px;">New</div>
                          </div>
                        </div>
                      @endif

                      <div
                        style="background: url('{{ get_image_url($item->banner, 'event-thumb')  }}') no-repeat center; background-size:auto; height: 180px;">
                      </div>

                      {{-- <img src="{{ $item->banner }}" alt="" /> --}}
                      <div class="item__info">
                        <div class="item__date">
                        </div>
                        <div class="item__eye">
                          <svg width="16" height="10" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                 xlink:href="#icon_eye"></use>
                          </svg>
                          {{ $item->lookup}}
                        </div>
                      </div>
                    </div>

                    <div class="item__caption" @if( getDeadlineDate($item->end) == "Expired") style=" -webkit-filter:
                                            grayscale(100%); filter: grayscale(100%);" @endif>
                      <h4 class="item__title">
                        <a href="{{route('event.details',['id'=>$item->id,'idCategory'=>$item->category_events->id])}}"
                           title="{{ strip_tags($item->title).' ('.$item->comments->count().')' }}">
                          <div style="white-space: nowrap; display: flex;">
                            <div style=" overflow: hidden; text-overflow:ellipsis;">
                              {{ strip_tags($item->title)}} </div>
                            <div style="margin-left: 5px;">
                              {{' ('.$item->comments->count().')' }}</div>
                          </div>
                        </a>
                      </h4>
                      <div style="display: flex; margin-bottom: 10px">
                        <div style="margin-right: 5px ">
                          @if($item->member_id ==null || $item->getNameMemberById($item->member_id) =="Anonymous" )
                            {!! getStatusWriter('real_name_certification') !!}
                          @else
                            {!! getStatusWriter($item->getStatusMember($item->member_id)) !!}
                          @endif
                        </div>

                        {{$item->getNameMemberById($item->member_id)}}
                      </div>
                      <div class="item__addon d-flex justify-content-between mb-10 pr-1">
                        <div class="pr-2">
                          <span class="item__text-gray"> {{__('event.event_date')}} |</span>
                          {{Carbon\Carbon::parse( $item->published)->format('M d')}}
                        </div>
                        <div class="pl-2">
                                                    <span class="item__text-gray">{{__('event.lookup')}}
                                                        |</span>
                          {{$item->views ?? 0}}
                        </div>
                      </div>
                      {{-- <div class="item__datetime">
          <span class="item__icon">
          <svg width="10" height="10" aria-hidden="true" class="icon">
              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_datetime"></use>
          </svg>
          </span>
          <span class="item__datetime__detail">{{Carbon\Carbon::parse( $item->start)->format('d M | h:i a')}}
                      - {{ Carbon\Carbon::parse( $item->end)->format('d M | h:i a')}}</span>
                  </div> --}}
                    </div>
                  </div>
                </div>
              </a>
            @endforeach
          @else
            <div class="col-lg-4 col-md-6">
              <div class="item__desc">
                <br>
                <h3>{{__('event.no_events')}}</h3>
              </div>
            </div>
          @endif
        </div>
        @endswitch
        {!! Theme::partial('paging',['paging'=>$events->appends(request()->input()) ]) !!}
        @if($canCreate )
          <div style="text-align: right; margin-top: 20px;">
            <a href="{{route('eventsFE.create',['idCategory'=>$idCategory])}}"
               title="{{__('event.create_events')}}" class="filter__item btn btn-primary mx-3 btn-reset-padding">
              <span>{{__('event.write')}}</span>
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
  </div>
</main>
