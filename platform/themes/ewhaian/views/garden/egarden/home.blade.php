<style>
  body {
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none
  }

  .bg-green {
    background-color: rgb(89, 198, 177);
    color: #ffffff;
  }

  .bg-main:hover,
  .bg-main:focus {
    color: #FFFFFF;
  }

  .icon-label {
    margin-right: 0.71429em;
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

  .item__new .icon-label {
    width: 2em;
    height: 2em;
    display: inline-block;
    border: 1px solid #FFFFFF;
    border-radius: 50%;
    color: #FFFFFF;
    text-align: center;
    line-height: 2em;
    font-weight: 700;
    font-size: 0.71429em;
  }

  .item--custom {
    overflow: hidden;
    position: relative;
  }

  .select2-container--default .select2-results > .select2-results__options {
    max-height: 800px;
  }

  .icon--gallery {
    width: 1.42857em;
    height: 1.42857em;
    display: inline-block;
    border: 1px solid #EC1469;
    color: #EC1469;
    text-align: center;
    line-height: 1.28571em;
  }
</style>

<div class="loading-section hide">
  <img class="loading-image" src="/storage/uploads/back-end/logo/logo-spinner.png"/>
</div>

<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>0,'egarden'=>1]) !!}
      <div class="sidebar-template__content">
        <ul class="breadcrumb">
          <li>
            <a href="{{route('egardenFE.home')}}" title="{{__('garden')}}">{{__('garden')}}</a>
            <svg width="4" height="6" aria-hidden="true" class="icon">
              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
            </svg>
          </li>
          <li>{{__('egarden')}}</li>
        </ul>

        <div class="notice-alert">
          <div class="notice-alert__title" style="white-space: nowrap;">{{__('egarden.notice')}}</div>
          @if($notices->count() > 0 )
            <div class="notice-alert__description">
              @foreach ($notices as $item)
                <div>
                  <a href="{{route('egardenFE.notice.details',['id'=>$item->id])}}">
                    {!!$item->title !!}
                  </a>
                </div>
              @endforeach
            </div>
          @else
            <div class="notice-alert__description">
              {!!__('egarden.no_have_notices')!!}
            </div>
          @endif
        </div>
{{--        {!! Theme::partial('garden.elements.searchHome') !!}--}}

        <div>
          <div class="row justify-content-between" style="padding-bottom:10px;">
            <div class="col-2">
              <div class="account__icon">
                <a href="{{route('egardenFE.room.list')}}" title="나의 E-화원">
                  <span class="">
                    나의 E-화원
                    <svg width="17" height="17" aria-hidden="true">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_setting">
                    </use>
                    </svg>
                  </span>
                </a>
              </div>
            </div>
          </div>

          @if($todayPopular->count() >0 )
            <div class="d-flex align-items-center popular-search">
              <span class="text-bold mr-3">{{__('garden.today_popular')}}: </span>
              <ul class="popular__list">

                @foreach ($todayPopular as $item)
                  <li class="popular__item">
                    <a class="alert bg-white-1" href="{{ route('egarden.search', 7) }}?keyword={{ $item->keyword }}"
                       title="{{$item->keyword}}">{{$item->keyword}}</a>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif

          @if($popular->count() >0 )
            <div class="d-flex justify-content-between">
              <div class="d-flex align-items-center popular-search">
                <span class="text-bold mr-3">{{__('garden.popular_searches')}}: </span>
                <ul class="popular__list">

                  @foreach ($popular as $item)
                    <li class="popular__item">
                      <a class="alert bg-white-1" href="{{ route('egarden.search', 7) }}?keyword={{ $item->keyword }}"
                         title="{{$item->keyword}}">{{$item->keyword}}</a>
                    </li>
                  @endforeach
                </ul>
              </div>

              @if($countAccess >0 )
                <div class="d-flex align-items-center popular-search">
                  <span class="text-bold mr-3">{{__('garden.current_member')}}: </span>
                  <ul class="popular__list">
                    <li>
                      <!-- <div class="popular__item alert bg-white-1">{{$countAccess}} {{__('garden.people')}}</div> -->
                       <div class="popular__item alert bg-white-1">{{$countAccess}}</div>
                    </li>
                  </ul>
                </div>
              @endif
            </div>
          @endif

        <div>
          <form action="{{ route('egarden.search', 7) }}" method="GET" id="form-search-1">
          <div class="filter align-items-center" style="margin-bottom: 0px; padding-bottom: 5px;">
            <div class="filter__item filter__title mr-3">{{__('garden.search')}}</div>
            <div class="filter__item d-flex align-items-md-center justify-content-md-center  mr-3 ">
              <div class="d-flex align-items-center">
                              <span class="arrow">
                                  <svg width="6" height="15" aria-hidden="true" class="icon">
                                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow">
                                      </use>
                                  </svg>
                              </span>
                <input data-datepicker-start type="text"
                       class="form-control form-control--date startDate" id="startDate" name="startDate"
                       value="{{request('startDate') ?: getToDate(1) }}" autocomplete="off">
              </div>
              <span class="filter__connect">-</span>
              <div class="d-flex align-items-md-center">
                <input data-datepicker-end type="text" class="form-control form-control--date endDate"
                       id="endDate" name="endDate" value="{{request('endDate') ?: getToDate(1) }}" autocomplete="off">
                <span class="arrow arrow--next">
                    <svg width="6" height="15" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow">
                        </use>
                    </svg>
                </span>
              </div>
            </div>

            <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1 pb-0">
              <select class="form-control form-control--select mx-3" style="padding-bottom: 0.425rem" name="type"
                      value="{{ request('type') }}">
                <option value="0" @if(request('type')==0) selected @endif>{{__('garden.title')}}</option>
                <option value="1" @if(request('type')==1) selected @endif>{{__('garden.detail')}}</option>
              </select>
              <div class="form-group form-group--search  flex-grow-1  mx-3">
                <a href="javascript:{}" onclick="document.getElementById('form-search-1').submit();">
                    <span class="form-control__icon">
                        <svg width="14" height="14" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use>
                        </svg>
                    </span>
                </a>
                <input type="text" class="form-control keyword" placeholder="제목 입력" style="padding-bottom: 0.425rem"
                       name="keyword" value="{{ request('keyword') }}" maxlength="50">
              </div>
            </div>

            <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
                   style="display:none">

          </div>
          </form>

          <div class="filter align-items-center" style="margin-bottom: 20px;">
            <div class="filter__item filter__title mr-4 pb-0">{{__('garden.filter')}}</div>
            <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
              <div class="form-group form-group--search flex-grow-1 mx-3">
                <form action="{{ route('egarden.search', 7) }}" method="GET">
                  <input type="text" class="form-control" placeholder="필터를 입력해주세요" name="filter"  style="padding-bottom: 0.425rem"
                         style="padding-bottom: 0" value="{{request('filter') ?? ''}}">
                </form>
              </div>
            </div>

            {!! Theme::partial('orderby',[
                'route'=> route('egarden.search', 7),
                'style'=>"width: 100px; margin-bottom: 0;",
                'have_like' => 1,
            ]) !!}
          </div>
      </div>

        @if (session('permission'))
          <div class="alert alert-danger" style="width: 100%">
            {{ session('permission') }}
          </div>
        @endif
        <div style="margin-bottom: 30px">
          {!! Theme::partial('garden.elements.circle',[
              'roomJoined' => $roomJoined,
              'roomCreated' => $roomCreated,
          ]) !!}
        </div>

        <div style="overflow-x: auto;">
          <table class="table table--content-middle" style="width: 880px">
            <thead>
            <tr>
              <th style="text-align: center;">룸</th>
              <th style="text-align: center;">{{__('life.open_space.title')}}</th>
              <th style="text-align: center;">{{__('life.open_space.author')}}</th>
              <th style="text-align: center;">{{__('life.open_space.date')}}</th>
              <th style="text-align: center;">{{__('life.open_space.lookup')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($egarden as $item)
              <tr style="opacity: 100%;">
                <td style="text-align: center;">
                  <span class="alert bg-purple" title="공동구매">{{$item->room->name}}</span>
                </td>
                <td>
                  <a href="{{route('egardenFE.details',['id'=>$item->id])}}"
                     title="{{ strip_tags($item->title) }}{{' ('.$item->comments->count().')' }}">
                    <div class="garden-title">
                      @if(request('type') > 0 )
                        {!! highlightWords2($item->detail ?? "No have details",request('keyword'),40)
                        !!}{{' ('.$item->comments->count().')' }}
                      @else
                        {!! highlightWords2($item->title,request('keyword'),40) !!}{{' ('.$item->comments->count().')' }}

                      @endif
                      @if(!is_null($item->banner))
                        <span class="icon icon--gallery">
                                                    <svg width="15" height="13" aria-hidden="true">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         xlink:href="#icon_gallery"></use>
                                                    </svg>
                                                </span>
                      @endif
                    </div>
                  </a>
                </td>
                <td>
                  <div style="display: flex">
                    @if($item->member_id ==null || @$item->member->nickname =="Anonymous" )
                      {!! getStatusWriter('real_name_certification') !!}
                    @else
                      {!! getStatusWriter(@$item->member->certification)  !!}
                    @endif
                    {{ @$item->member->nickname ?? 'admin' }}
                  </div>
                </td>
                <td style="text-align: center;">
                  {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->published ?? today())->format('Y-m-d') }}
                </td>
                <td style="text-align: center;">
                  {{$item->lookup ?? 0}}
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        {!! Theme::partial('paging',['paging'=>$egarden->appends(request()->input()) ]) !!}
      </div>

    </div>

  </div>
</main>

{{-- popup show image --}}
{!! Theme::partial('popup.showImage',['slides'=> $slides]) !!};
{{--  popup show image --}}

<script type="text/javascript" src="{{ asset('js/loading.js') }}"></script>
