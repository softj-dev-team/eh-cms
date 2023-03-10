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
.item__image .table__image {
  height: 80px;
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
/* .table--content-middle .alert:not(:nth-of-type(1)){
    margin-top: 0 !important;
} */
.icon-label{
    margin-right: 1.2em !important;
}

.flare_market_list > div {
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    height: 65px;
}
.flare_market_list > div .alert {
    padding: 5px 1em !important;
}

</style>
<main id="main-content" data-view="advertisement" data-page="life" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            {!! Theme::partial('life.menu',['active'=>"flare_market_list"]) !!}
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    <li><a href="" title="{{__('life')}}">{{__('life')}}</a></li>
                    <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    <li>{{__('life.flea_market')}}</li>
                </ul>
                <div class="heading">
                    <div class="heading__title">
                        {{__('life.flea_market')}}
                    </div>
                    <div class="heading__description">
                        {!!$description->description ?? __('life.no_have_description') !!}
                    </div>
                </div>
                {{-- search Flare Market--}}
                {!! Theme::partial('switch_menu',['style' => $style, 'hidden' => 3 ]) !!}
                {!! Theme::partial('life.elements.search',[
                    'categories'=> $categories,
                    'have_like' => 1,
                ]) !!}

              @if(count($notices) > 0 )
                @foreach ($notices as $notice)
                  <div class="notice-alert">
                    <div class="notice-alert__title" style="white-space: nowrap;">{{__('life.part-time_job.notice')}}</div>
                    <div class="notice-alert__description">
                      <a href="{{route('life.flare_market.notices.detail',['id'=>$notice->id])}}">{!!$notice->name!!}</a>
                    </div>
                  </div>

                @endforeach
              @else
                <div class="notice-alert">
                  <div class="notice-alert__title" style="white-space: nowrap;">{{__('life.part-time_job.notice')}}</div>
                  <div class="notice-alert__description">
                    <span> {{__('life.part-time_job.no_notices')}}</span>
                  </div>
                </div>
              @endif

                {{-- ------------------------------}}
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
                {{-- content Flare Market --}}
                @switch($style)
                    @case(0)
                    <div class="content">
                        <div class=" table-responsive">
                            <table class="table table--content-middle table--event-comments">
                                <thead>
                                    <th style="text-align: center;">{{__('life.flea_market.classification')}}</th>
                                    <th style="text-align: center;">@if(request('type') == 1) {{__('life.flea_market.detail')}} @else {{__('life.flea_market.title')}} @endif</th>
                                    {{-- <th style="text-align: center;width: 13%;" >{{__('life.flea_market.writer')}}</th> --}}
                                    <th class="d-none d-lg-table-cell" style="text-align: center">{{__('life.flea_market.date')}}</th>
                                    <th style="text-align: center;width: 13%;">{{__('life.flea_market.lookup')}}</th>
                                    <th style="text-align: center;width: 13%;">{{__('life.flea_market.status')}}</th>
                                </thead>
                                <tbody>
                                    @if(count($flare) > 0 )
                                    @foreach ($flare as $item)
                                    <tr style="opacity: {{100 - ($item->dislikes_count ?? 0) *10 }}%;">
                                        <td style="width: 13%">
                                            {!! Theme::partial('classification',['categories'=>$item->categories ]) !!}
                                        </td>
                                        <td style="padding-right: 5px">
                                            <a href="{{route('life.flare_market_details',['id'=>$item->id])}}" title="{!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                            @if(request('type') > 0 )
                                                {!! Theme::partial('show_title',['text'=>$item->detail ?? "No have details", 'keyword' => request('keyword'), 'num' => 40 ]) !!}
                                                {{' ('.$item->comments->count().')' }}
                                                @if(getNew($item->published))  <span class="icon-label-new">N</span> @endif
                                            @else
                                                {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword'), 'num' => 40 ]) !!}
                                                {{' ('.$item->comments->count().')' }}
                                                @if(getNew($item->published))  <span class="icon-label-new">N</span> @endif
                                             @endif
                                            </a>
                                        </td>
                                        {{-- <td >
                                            <div style="display: flex; justify-content: left; align-items: center">
                                                @if($item->member_id ==null || $item->getNameMemberById($item->member_id) =="Anonymous" )
                                                    {!! getStatusWriter('real_name_certification')  !!}
                                                @else
                                                    {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                                                @endif
                                                {{$item->getNameMemberById($item->member_id)}}
                                            </div>
                                        </td> --}}
                                        <td class="d-none d-lg-table-cell" style="text-align: center;">{{getDateFlareMarket($item->published)}}</td>
                                        <td style="text-align: center;">{{$item->lookup ?? 0}}</td>
                                        <td style="text-align: center;">
                                        @if($item->status == "publish" ) {{__('life.flea_market.status.on_sale')}}
                                        @elseif($item->status == "pending" ) {{__('life.flea_market.status.in_transit')}}
                                        @elseif($item->status == "completed" ) {{__('life.flea_market.status.transaction_completed')}}
                                        @endif
                                      </td>
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
                                    <th style="text-align: center;" width="90px">{{__('life.flea_market.image')}}</th>
                                    <th style="text-align: center;">{{__('life.flea_market.classification')}}</th>
                                    <th style="text-align: center;">@if(request('type') == 1) {{__('life.flea_market.detail')}} @else {{__('life.flea_market.title')}} @endif</th>
                                   {{-- <th style="text-align: center;width:15%">{{__('life.flea_market.writer')}}</th> --}}
                                   <th class="d-none d-lg-table-cell" style="text-align: center;width:10%;white-space: nowrap;">{{__('life.flea_market.date')}}</th>
                                   <th style="text-align: center;width:7%;white-space: nowrap;">{{__('life.flea_market.lookup')}}</th>
                                </thead>
                                <tbody>
                                    @if(count($flare) > 0 )
                                    @foreach ($flare as $item)
                                    <tr style="opacity: {{100 - ($item->dislikes_count ?? 0) *10 }}%;">

                                        <td>
                                            <div class="item__image">
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
                                                        {!! highlightWords2($item->title,request('keyword'),50) !!}{{' ('.$item->comments->count().')' }}
                                                        @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                                                    </div>

                                                    <div>
                                                        {!! highlightWords2($item->detail ?? "No contents",request('keyword'), 50) !!}
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                        {{-- <td style="text-align: center;">
                                            <div style="display: flex; justify-content: left; align-items: center">
                                                @if($item->member_id ==null || $item->getNameMemberById($item->member_id) =="Anonymous" )
                                                    {!! getStatusWriter('real_name_certification')  !!}
                                                @else
                                                    {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                                                @endif
                                                {{$item->getNameMemberById($item->member_id)}}
                                            </div>
                                        </td> --}}
                                        <td class="d-none d-lg-table-cell" style="text-align: center;">{{getDateFlareMarket($item->published)}}</td>
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
                    <style>
                      .event-list .item__caption {
                        height: auto;
                        margin-bottom: 20px !important;
                      }
                    </style>
                    <div class="content event-list">
                        <div class="row mb-10 ml-0" @if(count($flare) <= 0 ) style="justify-content: center;"  @endif>
                            @if(count($flare) > 0 )
                                @foreach ($flare as $item)
                                <div class="col-lg-4 col-md-6 border mr-auto mb-3" style="opacity: {{100 - ($item->dislikes_count ?? 0) *10 }}%;flex-basis:32%">
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
                                            <div class="item__tag d-flex align-items-center justify-content-between mb-10 pr-1 pt-2">
                                                <div style=""> {!! Theme::partial('classification',['categories'=>$item->categories]) !!} </div>
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
                                                    @if( getDateFlareMarket($item->published) == "Today" ) @endif {{getDateFlareMarket($item->published)}}
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
