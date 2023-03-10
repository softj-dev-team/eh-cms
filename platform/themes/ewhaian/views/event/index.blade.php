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
        /* border-bottom: none; */
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

    .btn_active:hover {
        border: 1px solid #EC1469;
        color: #EC1469!important;
    }
    .btn2 {
        border: 1px solid #EC1469;
        background-color: white;
        color: #EC1469 !important;
        padding: 8px 10px;
        margin-right: 10px;
    }

    .btn_active {
        border: 1px solid #EC1469;
        background: #d9356b;
        color: #ffffff!important;
    }
.table-w thead th{
    padding-right:0;
    padding-left:0;
}
.table-w tbody tr td:nth-child(3) div{
    word-break: keep-all;
}
.row-col-box{
  display: -ms-flexbox;
  display: flex;
  align-items: center;
  justify-content: center;
}
.event-list .item__title{
  font-size: 1em;
}
@media (max-width: 576px){
  .table__image {
    height: 5.14286em;
  }
  .row-col-box .col-5,.row-col-box .col-3,.row-col-box .col-2{
    padding-right: 7px;
    padding-left: 7px;
  }
}
</style>
<script>
$(document).ready(function (){
  $('#btn-active-first.btn_active').hover(function (){
    $('#btn-active-first img').attr('src', '{{Theme::asset()->url('img/menu_1.png')}}')
  }, function (){
    $('#btn-active-first img').attr('src', '{{Theme::asset()->url('img/menu_1_white.png')}}')
  })
  $('#btn-active-second.btn_active').hover(function (){
    $('#btn-active-second img').attr('src', '{{Theme::asset()->url('img/menu_2.png')}}')
  }, function (){
    $('#btn-active-second img').attr('src', '{{Theme::asset()->url('img/menu_2_white.png')}}')
  })
  $('#btn-active-three.btn_active').hover(function (){
    $('#btn-active-three img').attr('src', '{{Theme::asset()->url('img/menu_3.png')}}')
  }, function (){
    $('#btn-active-three img').attr('src', '{{Theme::asset()->url('img/menu_3_white.png')}}')
  })
})
</script>
<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->
                <div class="category-menu">
                    <h4 class="category-menu__title">{{__('event.menu__title')}}</h4>
                    <ul class="category-menu__links">
                        @foreach ($category as $item)
                        <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
                            <a href="{{route('event.event_list', ['idCategory'=>$item->id]) }}"
                                title="{{$item->name }}">{{$item->name}}</a>
                        </li>
                        @endforeach
                        <li class="category-menu__item">
                            <a href="{{route('event.cmt.list')}}"
                                title="{{__('event.event_comments')}}">{{__('event.event_comments')}}</a>
                        </li>

                    </ul>
                </div>
                <!-- end of category menu -->
            </div>
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    @foreach (Theme::breadcrumb()->getCrumbs() as $i => $crumb)
                    @if ($i != (count(Theme::breadcrumb()->getCrumbs()) - 1))
                    <li>
                        <a href="{{ $crumb['url'] }}" title="{!! $crumb['label'] !!}">{!! $crumb['label'] !!}</a>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    @else
                    <li class="active">{!! $crumb['label'] !!}</li>
                    @endif
                    @endforeach
                </ul>
                <h3 class="title-main">{{$selectCategories->name}}</h3>
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
                <div style="margin: 15px 0;">
                    <div style="display: flex; justify-content: flex-end;">

                        <a id="btn-active-first" href="{{route('event.event_list', ['idCategory'=>$idCategory,'style' => 1]) }}" class="btn btn2 {{$style == 1 ? 'btn_active' : ''}}" title="{{__('layout.list')}}">
                            <img src="{{$style == 1 ? Theme::asset()->url('img/menu_1_white.png') : Theme::asset()->url('img/menu_1.png') }}" style="width: 20px; height: 20px;" />
                            {{__('layout.list')}}
                        </a>
                        <a id="btn-active-second" href="{{route('event.event_list', ['idCategory'=>$idCategory,'style' => 2]) }}" class="btn btn2 {{$style == 2 ? 'btn_active' : ''}}" title="{{__('layout.list_thumbnail')}}">
                            <img src="{{$style == 2 ? Theme::asset()->url('img/menu_2_white.png') : Theme::asset()->url('img/menu_2.png') }}" style="width: 20px; height: 20px;" />
                            {{__('layout.list_thumbnail')}}
                        </a>
                        <a id="btn-active-three" href="{{route('event.event_list', ['idCategory'=>$idCategory,'style' => 3]) }}" class="btn btn2 {{$style == 3 || is_null($style) ? 'btn_active' : ''}}" title="{{__('layout.album')}}">
                            <img src="{{$style == 3 ? Theme::asset()->url('img/menu_3_white.png') : Theme::asset()->url('img/menu_3.png') }}" style="width: 20px; height: 20px;" />
                            {{__('layout.album')}}
                        </a>
                    </div>

                </div>
                <div class="event-list">
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
                                          {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword') ]) !!}
                                          {{' ('.$item->comments->count().')' }}
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
                                        {{getStatusDateByDate2($item->published)}}
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
                                    <th  style="text-align: center;max-width:148px;min-width:70px;width:15%;"> {{__('contents.banner')}}</th>
                                    <th  style="text-align: center;padding-left: 10px;">
                                            @if(request('type') > 0 )
                                            {{__('contents.description')}}
                                            @else
                                            {{__('contents.title')}}
                                            @endif
                                    </th>
                                    <th class="d-none d-lg-table-cell" style="text-align: center;padding-left: 0px;">{{__('contents.date')}}</th>
                                    <th class="d-none d-lg-table-cell" style="text-align: center;padding-left: 0px; width:13%;">{{__('contents.lookup')}}</th>
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
                                        <td style="text-align: left;padding-left: 10px;">
                                            <a style="display: block" href="{{route('event.details',['idCategory'=>$idCategory,'id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                                <div style="text-align: left;padding-left: 10px;margin: 0px;width: 100%;">
                                                  <div>
                                                    <div class="font-weight-bold">
                                                      {!! highlightWords2($item->title,request('keyword'), 30) !!}{{' ('.$item->comments->count().')' }}
                                                          @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                                                      </div>
                                                      @if (!empty($item->content) && highlightWords2($item->content, request('keyword'), 25) != '')
                                                      <div class="mt-2">
                                                          {!! highlightWords2($item->content, request('keyword'), 40) !!}
                                                      </div>
                                                      @endif
                                                  </div>
                                              </div>
                                            </a>
                                        </td>
                                        {{-- <div class="col-3">
                                            <div>
                                                @if($item->member_id ==null || $item->getNameMemberById($item->member_id) =="Anonymous" )
                                                    {!! getStatusWriter('real_name_certification')  !!}
                                                @else
                                                    {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                                                @endif
                                                {{$item->members->nickname ?? __('comments.anonymous')}}
                                            </div>
                                        </div> --}}
                                        <td class="d-none d-lg-table-cell">
                                            <div style="display: flex;padding: 0px;justify-content: center;">
                                                <div  style="background: url('{{Theme::asset()->url('img/clock.png')}}') no-repeat center; background-size:contain; min-width: 15px;max-width: 15px;height: 15px;margin: 4px 4px 0 4px;"></div>
                                                {{getStatusDateByDate2($item->published)}}
                                            </div>
                                        </td>
                                      <td class="d-none d-lg-table-cell">
                                        <div style="display: flex;justify-content: center;">
                                            <div class="single__info">
                                                <div class="single__eye">
                                                    <svg width="16" height="10" aria-hidden="true" class="icon">
                                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                                    </svg>
                                                    {{ $item->views ?? 0}}
                                                </div>
                                            </div>
                                        </div>

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
                                                    {{ $item->views ?? 0}}
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
                                                          {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword') ]) !!}
                                                        </div>
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
                                                    <span class="item__text-gray"> {{__('contents.date')}} |</span>
                                                  {{getStatusDateByDate2($item->published)}}
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
