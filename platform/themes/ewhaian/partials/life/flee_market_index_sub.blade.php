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
  .item__rectangle2 {
    position: absolute;
    top: -59px;
    right: -62px;
    width: 120px;
    height: 120px;
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
    /* border: 1px solid #FFFFFF; */
    border-radius: 50%;
    /* color: #FFFFFF; */
    text-align: center;
    line-height: 2em;
    font-weight: 700;
    font-size: 0.61429em;
  }

  .item__image {
    overflow: hidden;
    position: relative;
  }
  .icon-label-new {
    width: 2em;
    height: 2em;
    display: inline-block;
    border: 1px solid #EC1469;
    border-radius: 50%;
    color: #EC1469;
    text-align: center;
    line-height: 2em;
    font-weight: 700;
    font-size: 0.71429em;
  }
  .item--custom{
    overflow: hidden;
    position: relative;
  }
</style>
<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="sidebar-template__content" style="width: 100%">
        {{-- content Flare Market --}}
        @switch($style)
          @case(0)
          <div class="content">
            <div class=" table-responsive">
              <table class="table table--content-middle">
                <thead>
                <th style="text-align: center;">{{__('life.flea_market.classification')}}</th>
                <th style="text-align: center;">@if(request('type') == 1) {{__('life.flea_market.detail')}} @else {{__('life.flea_market.title')}} @endif</th>
                <th style="text-align: center;width: 13%;" >{{__('life.flea_market.writer')}}</th>
                <th style="text-align: center;width: 13%;">{{__('life.flea_market.date')}}</th>
                <th style="text-align: center;width: 13%;">{{__('life.flea_market.lookup')}}</th>
                </thead>
                <tbody>
                @if(count($flare) > 0 )
                  @foreach ($flare as $item)
                    <tr style="opacity: {{100 - ($item->dislikes_count ?? 0) *10 }}%;">
                      <td style="width: 13%">
                        {!! Theme::partial('classification',['categories'=>$item->categories ]) !!}
                      </td>
                      <td><a href="{{route('life.flare_market_details',['id'=>$item->id])}}" title="{!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                          @if(request('type') > 0 )
                            {!! highlightWords2($item->detail ?? "No have details",request('keyword')) !!} {{' ('.$item->comments->count().')' }}
                            @if(getNew($item->published))  <span class="icon-label-new">N</span> @endif
                          @else
                            {!! highlightWords2($item->title,request('keyword')) !!}{{' ('.$item->comments->count().')' }}
                            @if(getNew($item->published))  <span class="icon-label-new">N</span> @endif
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

                      <td style="text-align: center;">{{getStatusDateByDate($item->published)}}</td>
                      <td style="text-align: center;">{{$item->lookup ?? 0}}</td>
                    </tr>

                  @endforeach
                @else
                  <tr>
                    <td colspan="5" style="text-align: center"> {{__('life.flea_market.no_flea_market')}}</td>
                  </tr>
                @endif
                </tbody>
              </table>
            </div>

          </div>
          @break
          @case(1)
          <div class="content">
            <div class=" table-responsive">
              <table class="table table--content-middle">
                <thead>
                <th style="text-align: center;" width="148px">{{__('life.flea_market.image')}}</th>
                <th style="text-align: center;">{{__('life.flea_market.classification')}}</th>
                <th style="text-align: center;">@if(request('type') == 1) {{__('life.flea_market.detail')}} @else {{__('life.flea_market.title')}} @endif</th>
                <th style="text-align: center;width:15%">{{__('life.flea_market.writer')}}</th>
                <th style="text-align: center;width:10%">{{__('life.flea_market.date')}}</th>
                <th style="text-align: center;width:10%">{{__('life.flea_market.lookup')}}</th>
                </thead>
                <tbody>
                @if(count($flare) > 0 )
                  @foreach ($flare as $item)
                    <tr style="opacity: {{100 - ($item->dislikes_count ?? 0) *10 }}%;">

                      <td>
                        <div class="item__image">
                          @if(getNew($item->published))
                            <div class="item__new">
                              <div class="item__rectangle" style="z-index: 1">

                              </div>
                              <div class="item__eye" style="z-index: 2;margin-right: -17px;margin-top: -7px">
                                <span class="icon-label">N</span>
                              </div>
                            </div>
                          @endif

                          <a href="{{route('life.flare_market_details',['id'=>$item->id])}}" title="{!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                            <div class="table__image @if($item->status != 'publish') table__image--overlay d-flex align-items-center @endif"
                                 style="background-image: url('{{geFirsttImageInArray($item->images,'featured')}}')"
                                 width="150">
                              @switch($item->status)
                                @case('completed')
                                <span class="overlay text-center">{{__('life.flea_market.transaction_completed')}}</span>
                                @break
                                @case('pending')
                                <span class="overlay text-center">{{__('life.flea_market.in_transit')}}</span>
                                @break
                                @default
                              @endswitch
                            </div>
                          </a>
                        </div>

                      </td>
                      <td style="width: 13%">
                        {!! Theme::partial('classification',['categories'=>$item->categories ]) !!}
                      </td>
                      <td >
                        <a style="display: block" href="{{route('life.flare_market_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                          <div  >
                            <div style="font-weight: bold;">
                              {!! highlightWords2($item->title,request('keyword'),15) !!}{{' ('.$item->comments->count().')' }}
                              @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                            </div>

                            <div>
                              {!! highlightWords2($item->detail ?? "No contents",request('keyword'), 40) !!}
                            </div>
                          </div>
                        </a>
                      </td>
                      <td style="text-align: center;">
                        <div>
                          @if($item->member_id ==null || $item->getNameMemberById($item->member_id) =="Anonymous" )
                            {!! getStatusWriter('real_name_certification')  !!}
                          @else
                            {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                          @endif
                          {{$item->members->nickname ?? __('comments.anonymous')}}
                        </div>
                      </td>
                      <td style="text-align: center;">{{getStatusDateByDate($item->published)}}</td>
                      <td style="text-align: center;">{{ $item->lookup ?? 0}}</td>
                    </tr>

                  @endforeach
                @else
                  <tr>
                    <td colspan="5" style="text-align: center"> {{__('life.flea_market.no_flea_market')}}</td>
                  </tr>
                @endif
                </tbody>
              </table>
            </div>
          </div>
          @break
          @default
          <div class="content event-list">
            <div class="row mb-10" @if(count($flare) <= 0 ) style="justify-content: center;"  @endif>
              @if(count($flare) > 0 )
                @foreach ($flare as $item)
                  <div class="col-lg-4 col-md-6" style="opacity: {{100 - ($item->dislikes_count ?? 0) *10 }}%;">
                    <div class="item item--custom">
                      <a  href="{{route('life.flare_market_details',['id'=>$item->id])}}" title="{{$item->title}}" style="display:block">
                        @if(getNew($item->published))
                          <div class="item__new">
                            <div class="item__rectangle2" style="z-index: 1">

                            </div>
                            <div class="item__eye" style="z-index: 2;margin-right: -9px;">
                              <span class="icon-label">N</span>
                              <div style="margin-left: -4px;">New</div>
                            </div>
                          </div>
                        @endif
                        <div class="table__image @if($item->status != 'publish') table__image--overlay d-flex align-items-center @endif"
                             style="background-size: cover;height: 180px;background-image: url('{{geFirsttImageInArray($item->images,'event-thumb')}}')">
                          @switch($item->status)
                            @case('completed')
                            <span class="overlay text-center">{{__('life.flea_market.transaction_completed')}}</span>
                            @break
                            @case('pending')
                            <span class="overlay text-center">{{__('life.flea_market.in_transit')}}</span>
                            @break
                            @default
                          @endswitch
                        </div>
                      </a>
                      <div class="item__tag d-flex align-items-center justify-content-between mb-10 pr-1">
                        <div style="margin: 10px 0 0 -10px ;"> {!! Theme::partial('classification',['categories'=>$item->categories]) !!} </div>
                        <div class="text-right">
                          <div style="display: flex;">
                            @if($item->member_id ==null || $item->getNameMemberById($item->member_id)
                            =="Anonymous" )
                              {!! getStatusWriter('real_name_certification')  !!}
                            @else
                              {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                            @endif
                            {{$item->getNameMemberById($item->member_id)}}
                          </div>
                        </div>
                      </div>
                      <div class="item__caption mb-10 pr-1">
                        <h4 class="item__title">
                          <a href="{{route('life.flare_market_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                            <div style="white-space: nowrap; display: flex;">
                              <div  style=" overflow: hidden; text-overflow:ellipsis;">

                                @if(request('type') > 0 )
                                  {!! highlightWords2($item->detail ?? "No have details",request('keyword')) !!}
                                @else
                                  {!! highlightWords2($item->title,request('keyword')) !!}

                                @endif
                              </div>
                              <div style="margin-left: 5px;">{{' ('.$item->comments->count().')' }}</div>
                            </div>

                          </a>
                        </h4>
                      </div>
                      <div class="item__addon d-flex justify-content-between mb-10 pr-1">
                        <div class="pr-2">
                          <span class="item__text-gray">{{__('life.flea_market.date')}} |</span>
                          @if( getStatusDateByDate($item->published) == "Today" ) @endif {{getStatusDateByDate($item->published)}}
                        </div>
                        <div class="pl-2">
                          <span class="item__text-gray">{{__('life.shelter_info.lookup')}} |</span>
                          {{$item->lookup ?? 0}}
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              @else
                <div > {{__('life.flea_market.no_flea_market')}}</div>
              @endif
            </div>
          </div>
        @endswitch

        <div id="form-search-2">
          <div class="filter filter--1 align-items-end">
            <button  type="button" class="filter__item btn btn-secondary"  onClick="window.location.href='{{route('life.flare_market_list')}}'">
              <svg width="18" height="20.781" aria-hidden="true">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
              </svg>
              <span>{{__('life.flea_market.last_list')}}</span>
            </button>

            @if($canCreate)
              <a
                href="{{route('flareMarketFE.create',['categoryId'=>request('parentCategories') ? request('parentCategories') : $categories->first()->id])}}"
                title="{{__('life.flea_market.create_flea_market')}}" class="filter__item btn btn-primary mx-3 btn3">
                <span style="white-space: nowrap;">{{__('life.flea_market.write')}}</span>
              </a>
            @endif
          </div>
        </div>
        {!! Theme::partial('paging',['paging'=>$flare->appends(request()->input()) ]) !!}
      </div>

    </div>

  </div>
</main>
