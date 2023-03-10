<style>
    .table tr.disable {
        opacity: 0.4;
    }
    .flare_market_list {
        flex-direction: unset;
    }
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
.item__rectangle2 {
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
/* .item__image {
    overflow: hidden;
    position: relative;
} */
.item__image {
  overflow: hidden;
  border: 1px solid #e8e8e8;
  /* border-bottom: none; */
}

.icon-label-image {
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
    padding: 10px;
    margin-right: 10px;
  }
  .btn_active {
    border: 1px solid #EC1469;
    background: #d9356b;
    color: #ffffff!important;
  }
.item__rectangle  {
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
.icon-label {
    border: 1px solid #EC1469;
    color: #EC1469;
}
.event-list .item__title{
  font-size: 1em;
}

@media (max-width: 768px){
    .table--content-middle {
        overflow-x: scroll;
        width: 100%;
    }
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
          <h4 class="category-menu__title">{{__('contents')}}</h4>
          <ul class="category-menu__links">
            @foreach ($categories as $item)
            <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
              <a href="{{route('contents.contents_list', ['idCategory'=>$item->id]) }}"
                title="$item->name">{{$item->name}}</a>
            </li>
            @endforeach
          </ul>
        </div>
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
        <div class="heading" style="display: flex;">
            <div class="heading__title" style="white-space: nowrap;">
                {!!Theme::breadcrumb()->getCrumbs()[1]['label']!!}
            </div>
            <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                {!!$selectCategories->description ?? ""!!}
            </div>
        </div>
        {{-- --------------------------- --}}

        <!-- filter -->
        <form action="{{route('contents.search',['idCategory'=>$idCategory])}}" method="GET" class="form-search" id="form-search-1">
          <div class="filter align-items-center">
            <div class="filter__item filter__title mr-3">{{__('contents.search')}}</div>
            <div class="filter__item d-flex align-items-md-center justify-content-md-center  mr-3 ">
              <div class="d-flex align-items-center">
                <span class="arrow">
                  <svg width="6" height="15" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
                  </svg>
                </span>
                <input data-datepicker-start type="text" class="form-control form-control--date startDate"
                  id="startDate1" name="startDate" value="{{request('startDate') ?: getToDate() }}" autocomplete="off">
              </div>
              <span class="filter__connect">-</span>
              <div class="d-flex align-items-md-center">
                <input data-datepicker-end type="text" class="form-control form-control--date endDate" id="endDate1"
                  name="endDate" value="{{request('endDate') ?: getToDate() }}" autocomplete="off">
                <span class="arrow arrow--next">
                  <svg width="6" height="15" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
                  </svg>
                </a>
              </div>
            </div>

            <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
              <select class="form-control form-control--select mx-3" name="type" value="{{ request('type') }}">
                <option value="0" @if(request('type')==0) selected @endif>{{__('contents.title')}}</option>
                <option value="1" @if(request('type')==1) selected @endif>{{__('contents.description')}}</option>
              </select>
              <div class="form-group form-group--search  flex-grow-1  mx-3">
                <a href="javascript:{}" onclick="document.getElementById('form-search-1').submit();">
                    <span class="form-control__icon">
                    <svg width="14" height="14" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use>
                    </svg>
                    </span>
                </a>
                <input type="text" class="form-control" placeholder="{{__('search.title_or_content')}}" name="keyword"
                  value="{{ request('keyword') }}">
              </div>
            </div>

            <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
              style="display:none">
        </form>
      </div>

      @if(count($notices) > 0 )
        @foreach ($notices as $notice)
          <div class="notice-alert">
            <div class="notice-alert__title" style="white-space: nowrap;">{{__('life.part-time_job.notice')}}</div>
            <div class="notice-alert__description">
              <a href="{{route('contents.contents.notices.detail',['idCategory'=>$idCategory,'id'=>$notice->id])}}">{!!$notice->name!!}</a>
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
      <!-- end of filter -->
{{--        @if(!is_null( $selectCategories->notice))--}}
{{--            <div class="notice-alert">--}}
{{--                <div class="notice-alert__title" style="white-space: nowrap;">{{__('contents.notice')}}</div>--}}
{{--                <div class="notice-alert__description">--}}
{{--                    {!! $selectCategories->notice !!}--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}
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
        <!-- switch layout-->
        <div>
            <div style="display: flex; justify-content: flex-end;">

              <a id="btn-active-first" href="{{route('contents.contents_list', ['idCategory'=>$idCategory,'style' => 1]) }}" class="btn btn2 {{$style == 1 ? 'btn_active' : ''}}" title="{{__('layout.list')}}">
                <img src="{{$style == 1 ? Theme::asset()->url('img/menu_1_white.png') : Theme::asset()->url('img/menu_1.png') }}" style="width: 20px; height: 20px;" />
                {{__('layout.list')}}
              </a>
              <a id="btn-active-second" href="{{route('contents.contents_list', ['idCategory'=>$idCategory,'style' => 2]) }}" class="btn btn2 {{$style == 2 ? 'btn_active' : ''}}" title="{{__('layout.list_thumbnail')}}">
                <img src="{{$style == 2 ? Theme::asset()->url('img/menu_2_white.png') : Theme::asset()->url('img/menu_2.png') }}" style="width: 20px; height: 20px;" />
                {{__('layout.list_thumbnail')}}
              </a>
              <a id="btn-active-three" href="{{route('contents.contents_list', ['idCategory'=>$idCategory,'style' => 3]) }}" class="btn btn2 {{$style == 3 || is_null($style) ? 'btn_active' : ''}}" title="{{__('layout.album')}}">
                <img src="{{$style == 3 ? Theme::asset()->url('img/menu_3_white.png') : Theme::asset()->url('img/menu_3.png') }}" style="width: 20px; height: 20px;" />
                {{__('layout.album')}}
              </a>
            </div>

        </div>
        <!-- end switch layout-->

        <div class="content">
            @switch($style)
                @case(1)
                <div class=" table-responsive">
                        <table class="table table--content-middle">
                            <thead>
                                <th style="text-align: center;padding-left: 0px;">{{__('contents.title')}}
                                </th>
                                <th class="d-none d-lg-table-cell" style="text-align: center;padding-left: 0px;">{{__('contents.date')}}</th>
                                <th class="d-none d-lg-table-cell" style="text-align: center;width:15%;">{{__('contents.lookup')}}
                                </th>
                            </thead>
                            <tbody>
                                @if(count($contents) > 0 )
                                @foreach ($contents as $item)
                                <tr >
                                    <td>
                                        <a href="{{route('contents.details',['idCategory'=>$idCategory,'id'=>$item->id])}}"
                                            title="{{ strip_tags($item->title) }}{{' ('.$item->comments->count().')' }}">
                                          {!! Theme::partial('show_title',['text'=>$item->title]) !!}
                                            {{' ('.$item->comments->count().')' }}
                                            @if(getNew($item->published,1)) <span class="icon-label">N</span>@endif
                                        </a>
                                    </td>
{{--                                    @if($style == 0)--}}
                                    <td class="d-none d-lg-table-cell" style="text-align: center">
                                        {{getStatusDateByDate2($item->published)}}
                                    </td>
{{--                                    @endif--}}
                                    <td class="d-none d-lg-table-cell" style="text-align: center">{{$item->lookup ?? 0}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" style="text-align: center">
                                        {{__('contents.no_contents')}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @break
                @case(3)
                    <div class="event-list">
                        <div class="row">
                            @if(count($contents) > 0 )
                                @foreach ($contents as $item)
                                <a href="{{route('contents.details',['idCategory'=>$idCategory,'id'=>$item->id])}}"
                                    title="{{ strip_tags($item->title) }}">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="item">
                                            <div class="item__image">
                                                @if(getNew($item->published,1))
                                                <div class="item__new2">
                                                    <div class="item__rectangle2" style="z-index: 1">

                                                    </div>
                                                    <div class="item__eye" style="z-index: 2;margin-right: -9px;">
                                                        <span class="icon-label-image">N</span>
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
                                                    <a href="{{route('contents.details',['idCategory'=>$idCategory,'id'=>$item->id])}}"
                                                        title="{{ strip_tags($item->title).' ('.$item->comments->count().')' }}">
                                                        <div style="white-space: nowrap; display: flex;">
                                                            <div style=" overflow: hidden; text-overflow:ellipsis;">
                                                              {!! Theme::partial('show_title',['text'=>$item->title]) !!} </div>
                                                            <div style="margin-left: 5px;">
                                                                {{' ('.$item->comments->count().')' }}</div>
                                                        </div>
                                                    </a>
                                                </h4>

                                                <div class="item__addon d-flex justify-content-between mb-10 pr-1">
                                                    <div class="pr-2">
                                                        <span class="item__text-gray"> 날짜 |</span>
                                                      {{getStatusDateByDate2($item->published)}}
                                                    </div>
                                                    <div class="pl-2">
                                                        <span class="item__text-gray">{{__('event.lookup')}}
                                                            |</span>
                                                        {{$item->lookup ?? 0}}
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
                    </div>
                    @break
                @default
                    <div class=" table-responsive">
                        <table class="table table--content-middle"style="width:100%;">
                            <thead>
                                <th style="text-align: center;max-width:148px;min-width:70px;width:15%;""> {{__('contents.banner')}}</th>
                                <th style="text-align: center;padding-left: 0px;">
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
                            @if(count($contents))
                                @foreach ($contents as $key => $item)
                                <tr >
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

                                            <a href="{{route('contents.details',['idCategory'=>$idCategory,'id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                            <div class="table__image @if($item->status != 'publish') table__image--overlay d-flex align-items-center @endif"
                                                style="background-image: url('{{ geFirsttImageInArray([$item->banner],'featured')}}')">
                                            </div>
                                            </a>
                                        </div>
                                    </td>
                                     <td>
                                         <a style="display: block" href="{{route('contents.details',['idCategory'=>$idCategory,'id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
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
                                         </a>
                                     </td>
                                     <td class="d-none d-lg-table-cell" style="text-align: center;">
                                      <div style="display: flex;padding: 0px;justify-content: center;">
                                          <div  style="background: url('{{Theme::asset()->url('img/clock.png')}}') no-repeat center; background-size:contain; min-width: 15px;max-width: 15px;height: 15px;margin: 4px 4px 0 4px;"></div>
                                          {{getStatusDateByDate2($item->published)}}
                                      </div>
                                      </td>
                                     <td class="d-none d-lg-table-cell" style="text-align: center;">
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
                                <td colspan="4" style="text-align: center">{{__('contents.no_contents')}}</td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    @break
            @endswitch

        </div>

      <!-- filter -->
      <div id="form-search-2">
        <div class="filter filter--1 align-items-end">
          @if($canCreate)
          <a href="{{route('contentsFE.create',['idCategory'=>$idCategory])}}" title="{{__('contents.create_contents')}}" class="filter__item btn btn-primary mx-3 btn-reset-padding">
                <span>{{__('contents.write')}}</span>
            </a>
          @endif
        </div>
      </div>
      <!-- end of filter -->
      {{-- --------------------------- --}}
      @if(isset($contents) && $contents!=null )
      {!! Theme::partial('paging',['paging'=>$contents->appends(request()->input()) ]) !!}
      @else
      {!! Theme::partial('paging',['paging'=>$description->appends(request()->input()) ]) !!}
      @endif

      {{-- {!! Theme::partial('paging') !!} --}}
    </div>
  </div>
  </div>
  </div>
</main>
