@php
  $user = auth()->guard('member')->user();
  $bypassPermission = isset($user) ? $user->hasPermission('gardenFE.bypass_password_requirement') : 0;
@endphp

<style>
  body {
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none
  }

  @media (max-width: 768px) {
    .date {
      padding: 0 18px;
      white-space: nowrap;
    }

    .garden-title {
      padding: 0 20px;
      white-space: nowrap;
    }

    .mobile-hide {
      display: none;
    }
    .garden-title {
      overflow: hidden;
      max-width: 43%;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  }

  #background {
    position: absolute;
    z-index: -1;
    background: white;
    display: flex;
    top: 150px;
    flex-wrap: wrap;
  }

  .bg-text {
    color: lightgrey;
    font-size: 50px;
    transform: rotate(320deg);
    -webkit-transform: rotate(320deg);
    margin-top: 50px;
    opacity: 4%;
  }

  .sidebar-template__content {
    position: relative;
  }

  .form-control::placeholder {
    color: #999999;
  }

  .avoid-click {
    pointer-events: none;
  }

  .form-control--select{
    background-position: bottom 12px right;
  }
  @media (max-width: 992px) {
    #form-search-2 .filter {
        padding: 0 !important;
    }
  }
  @media (max-width: 576px) {
    .filter-group-one > .btn {
      width: 50%
    }
    .filter-group-one .filter__item:nth-child(2) {
      margin-left: 3% !important;
    }
    .filter__connect {
      margin-left: 0
    }
    .form-group--search .form-control__icon {
      padding-bottom: 4px
    }
  }
</style>
@isset($permission)
<script>
  alert({{ $permission }})
</script>
@endisset
<div class="loading-section hide">
  <img class="loading-image" src="/storage/uploads/back-end/logo/logo-spinner.png"/>
</div>

<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>$selectCategories->id]) !!}
      <div class="sidebar-template__content" style="z-index: 1">
        {{-- {!! Theme::partial('slides',['slides'=> $composer_SLIDES_HOME ?? null ]) !!}
        <br> --}}
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

        @if (isset($no_permission))
          <div class="alert alert-danger" style="width: 100%; margin-top: 10px;">
            {{ $no_permission }}
          </div>
        @else
          <div class="heading" style="display: flex;">
            <div class="heading__title" style="white-space: nowrap;">
              {{$selectCategories->name}}
            </div>
            <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
              {!!$description->description ?? '' !!}
            </div>
          </div>
          {{-- Search --}}
          {{--        @if($todayPopular->count() >0 )--}}
          {{--          <div class="d-flex align-items-center popular-search">--}}
          {{--            <span class="text-bold mr-3">{{__('garden.today_popular')}}: </span>--}}
          {{--            <ul class="popular__list">--}}

          {{--              @foreach ($todayPopular as $item)--}}
          {{--                <li class="popular__item">--}}
          {{--                  <a class="alert bg-white-1" href="{{ route('garden.search', ['idCategories'=>$selectCategories->id]) }}?keyword={{ $item->keyword }}"--}}
          {{--                     title="{{$item->keyword}}">{{$item->keyword}}</a>--}}
          {{--                </li>--}}
          {{--              @endforeach--}}
          {{--            </ul>--}}
          {{--          </div>--}}
          {{--        @endif--}}

          @if($popular->count() >0 )
            <div class="d-flex justify-content-between gar-flex-box">
              <div class="d-flex align-items-center popular-search">
                <span class="text-bold mr-3 mem-text1">{{__('garden.popular_searches')}}: </span>
                <ul class="popular__list">

                  @foreach ($popular as $item)
                    <li class="popular__item">
                      <a class="alert bg-white-1" href="{{ route('garden.search', ['idCategories'=>$selectCategories->id]) }}?keyword={{ $item->keyword }}"
                         title="{{$item->keyword}}">{{$item->keyword}}</a>
                    </li>
                  @endforeach
                </ul>
              </div>

              @if($countAccess >0 )
                <div class="d-flex align-items-center popular-search">
                  <span class="text-bold mr-3 mem-text2">{{__('garden.current_member')}}: </span>
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
        <!-- filter -->
          <div>
            <form action="{{route('garden.search',['idCategories'=>$selectCategories->id])}}" method="GET" id="form-search-1">
              <div class="filter align-items-center" style="margin-bottom: 0px; padding-bottom: 5px;">
                <div class="filter__item filter__title mr-3 mo-float-L">{{__('garden.search')}}</div>
                <div class="filter__item d-flex align-items-md-center justify-content-md-center mr-3 ">
                  <div class="d-flex align-items-center">
                                <span class="arrow">
                                    <svg width="6" height="15" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow">
                                        </use>
                                    </svg>
                                </span>
                    <input data-datepicker-start type="text"
                           class="form-control form-control--date startDate" id="startDate" name="startDate"
                           value="{{request('startDate') ? request('startDate') : (request('startDate1') ? request('startDate1') : getToDate(1))}}" autocomplete="off" readonly>
                  </div>
                  <span class="filter__connect">-</span>
                  <div class="d-flex align-items-md-center">
                    <input data-datepicker-end type="text" class="form-control form-control--date endDate"
                           id="endDate" name="endDate" value="{{request('endDate') ? request('endDate') : (request('endDate1') ? request('endDate1') : getToDate(1))}}" autocomplete="off" readonly>
                    <span class="arrow arrow--next">
                                    <svg width="6" height="15" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow">
                                        </use>
                                    </svg>
                                </span>
                  </div>
                </div>

                <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1 pb-0">
                  <select class="form-control form-control--select mx-3" name="type"
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
                    <input type="text" class="form-control keyword" placeholder="{{__('search.title_or_content')}}" style="padding-bottom: 0.425rem"
                           name="keyword" value="{{ request('keyword') }}" maxlength="50">
                  </div>
                </div>

                <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
                       style="display:none">
            </form>
          </div>
          <div class="filter align-items-center gar-filter d-none d-sm-flex" style="margin-bottom: 20px;">
            <div class="filter__item filter__title mr-4 pb-2 gar-filter-tit">{{__('garden.filter')}}</div>
            <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1 gar-filter-in">
              <div class="form-group form-group--search flex-grow-1 mx-3">
                <form action="{{route('garden.filter',['idCategories'=>$selectCategories->id])}}"
                      method="GET">
                  <div class="input-group mb-3">
                    <input type="hidden" name="startDate1" id="startDate1" value="">
                    <input type="hidden" name="endDate1" id="endDate1" value="">

                    <input type="text" class="form-control" placeholder="필터를 입력해주세요" name="filter"
                           style="padding-bottom: 0; padding-top:8px;" value="{{request('filter') ?? ''}}" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        @php
                            $backgroundColorButton = Route::currentRouteName() == 'garden.filter' ? 'background-color: #EC1469' : 'background-color: #b3b3b3';
                        @endphp
                      <button id="submit_filter" class="btn btn-primary mx-3" style="padding: 0.7em 1em;{{$backgroundColorButton}}" type="submit">설정</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            {!! Theme::partial('orderby',[
                'route'=> route('garden.search',['idCategories'=>$selectCategories->id]),
                'style'=>"width: 200px; margin-bottom: 0;",
                'have_like' => 1,
                'fullwidth' => true
            ]) !!}
            <a href="javascript:;" id="btn-add-bookmarks" class="btn btn-primary btn-reset-padding">{{ __('garden.bookmark') }}</a>
            <a href="{{ route('gardenFE.bookmarks') }}" class="btn btn-primary btn-reset-padding">{{ __('garden.bookmarks') }}</a>
          </div>

          <div class="d-sm-none">
              <div class="filter align-items-center gar-filter py-0 mb-0">
                <div class="filter__item filter__title mr-4 pb-2 gar-filter-tit">{{__('garden.filter')}}</div>
                  <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1 gar-filter-in">
                    <div class="form-group form-group--search flex-grow-1 mx-3">
                      <form action="{{route('garden.filter',['idCategories'=>$selectCategories->id])}}"
                            method="GET">
                        <div class="input-group mb-3">
                          <input type="hidden" name="startDate1" id="startDate1" value="">
                          <input type="hidden" name="endDate1" id="endDate1" value="">

                          <input type="text" class="form-control" placeholder="필터를 입력해주세요" name="filter"
                                style="padding-bottom: 0; padding-top:8px;" value="{{request('filter') ?? ''}}" aria-describedby="basic-addon2">
                          <div class="input-group-append">
                              @php
                                  $backgroundColorButton = Route::currentRouteName() == 'garden.filter' ? 'background-color: #EC1469' : 'background-color: #b3b3b3';
                              @endphp
                            <button id="submit_filter" class="btn btn-primary mx-3" style="padding: 0.7em 1em;{{$backgroundColorButton}}" type="submit">설정</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>

                  {!! Theme::partial('orderby',[
                      'route'=> route('garden.search',['idCategories'=>$selectCategories->id]),
                      'style'=>"width: 200px; margin-bottom: 0;",
                      'have_like' => 1,
                      'fullwidth' => true
                  ]) !!}
              </div>
              <div class="filter filter--1 align-items-end filter-group-two px-0 py-0 d-block text-center">
                <div class="filter__item d-inline-block align-items-end justify-content-md-center">
                  <a href="javascript:;" id="btn-add-bookmarks" class="btn btn-primary btn-reset-padding">{{ __('garden.bookmark') }}</a>
                </div>
                <div class="filter__item d-inline-block align-items-end justify-content-md-center flex-grow-1">
                  <a href="{{ route('gardenFE.bookmarks') }}" class="btn btn-primary btn-reset-padding">{{ __('garden.bookmarks') }}</a>
                </div>
              </div>
            </div>
        @endif
      </div>
      <!-- end of filter -->

      @if (!isset($no_permission))
        @if(count($notices) > 0 )
          @foreach ($notices as $notice)
            <div class="notice-alert">
              <div class="notice-alert__title" style="white-space: nowrap;">{{__('garden.notice')}}</div>
              <div class="notice-alert__description">
                <a href="{{route('garden.notices.detail',['idCategory'=>$selectCategories->id,'id'=>$notice->id])}}">{!!$notice->name!!}</a>
              </div>
            </div>

          @endforeach
        @else
          <div class="notice-alert">
            <div class="notice-alert__title" style="white-space: nowrap;">{{__('garden.notice')}}</div>
            <div class="notice-alert__description">
              <span> {{__('garden.no_have_notices')}}</span>
            </div>
          </div>
        @endif

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
        {{-- end search --}}
        <div class="content">
          <div class=" table-responsive">
            <table class="table table--content-middle">
              <thead>
                <th></th>
                <th style="text-align: center;" class="mobile-hide">{{__('garden.id')}}</th>
                @if(request('type') > 0 )
                  <th style="text-align: center;">{{__('garden.detail')}}</th>
                @else
                  <th style="text-align: center;">{{__('garden.title')}}</th>
                @endif
                <th style="text-center: center;" class="mobile-hide">{{__('garden.date')}}</th>
                <th style="text-align: center;min-width: 60px">{{__('garden.lookup')}}</th>
              </thead>
              <tbody>
                @foreach ($garden as $key => $item)
                  @php
                    $diff = $item->dislikes_count - $item->likes_count;
                    $className = '';

                    if ($diff < 5) {
                        $opacity = 100;
                    } elseif ($diff < 10) {
                        $opacity = 80;
                    } elseif ($diff < 20) {
                        $opacity = 60;
                    } elseif ($diff < 60) {
                        $opacity = 40;
                    } else {
                        $opacity = 20;
                        $className = 'avoid-click';
                    }

                    $showFormPassword = 1;
                    $isAuthor = $item->member_id == $user->id;

                    if (!is_null($item->pwd_post)){
                        if ($bypassPermission || $isAuthor){
                            $showFormPassword = 0;
                        }
                    } else {
                        $showFormPassword = 0;
                    }

                  @endphp
                  @if($key >= 0)
                    @if($item->status == 'publish')
                  <tr style="opacity: {{ $opacity }}%;">
                    <td class="text-center align-middle">
                      <input type="checkbox" class="item-select" value="{{ $item->id }}"@if ($item->isBookmarkedBy(auth()->guard('member')->user())) disabled @endif>
                    </td>
                    <td style="text-align: center;" class="mobile-hide">
                      {{substr((string)$item->id, -5, 5)}}
                    </td>
                    <td class="{{ $className }}">
                      <a
                        data-url="{{route('gardenFE.details',['id'=>$item->id, 'idCategories' => $selectCategories->id])}}"
                        title="{{ strip_tags($item->title) }}{{' ('.$item->comments->count().')' }}"
                        href="javascript:void(0)"
                        class="garden-show-popup"
                        data-show="{{ $showFormPassword }}"
                        data-id="{{!is_null($item->id) ? $item->id : 0}}"
                        data-hint="{{!is_null($item->hint) ? $item->hint : ''}}"
                      >
                        <div class="garden-title">
                          @if(request('type') > 0 )
                            {!! Theme::partial('show_title',['text'=>$item->detail ?? "No have details", 'keyword' => request('keyword') ]) !!}
                            {{' ('.$item->comments->count().')' }}
                            @if($item->hot_garden == '2') <span class="icon-label">H</span>@endif
                          @else
                            {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword') ]) !!}
                            {{' ('.$item->comments->count().')' }}
                            @if($item->hot_garden == '2') <span class="icon-label">H</span>@endif

                          @endif
                        </div>
                      </a>
                    </td>
                    <td class="date mobile-hide" style="text-align: center;">
                      {{getStatusDateByDate2($item->published)}}
                      {{-- {{getStatusDateByDate($item->published)}}--}}

                      {{-- @php--}}
                      {{-- $randomIp = mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);--}}
                      {{-- @endphp--}}
                      {{-- <div>IP: {{ $randomIp }}</div>--}}
                    </td>
                    <td style="text-align: center;">
                      <div style="white-space: nowrap;">
                        <div style="display: flex;justify-content: center;">
                          <div style="margin-right: 10px">
                            <div class="d-flex align-items-center">
                            {{-- <svg class="mr-2" width="20" height="11" aria-hidden="true"--}}
                                {{-- class="icon">--}}
                              {{-- <use xmlns:xlink="http://www.w3.org/1999/xlink"--}}
                            {{--         xlink:href="#icon_lookup"></use>--}}
                            {{--  </svg>--}}
                            {{--  {{__('garden.lookup')}}--}}
                            </div>
                          </div>
                          <div>{{$item->lookup ?? 0}}</div>
                        </div>
                        {{-- <div style="display: flex;justify-content: center;">--}}
                          {{-- <div style="margin-right: 10px">--}}
                            {{-- <div class="d-flex align-items-center">--}}
                              {{-- <svg class="mr-2" width="20" height="16.56" aria-hidden="true"--}}
                                  {{-- class="icon">--}}
                                {{-- <use xmlns:xlink="http://www.w3.org/1999/xlink"--}}
                                    {{-- xlink:href="#icon_like"></use>--}}
                              {{-- </svg>--}}
                              {{-- {{__('garden.sympathy')}}--}}
                            {{-- </div>--}}
                          {{-- </div>--}}
                          {{-- <div>--}}
                            {{-- @if($item->can_reaction > 0)--}}
                              {{-- {{ $item->likes_count ?? 0 }}--}}
                              {{-- ( {{ $item->dislikes_count - $item->likes_count }} )--}}
                            {{-- @else--}}
                              {{-- -----}}
                            {{-- @endif--}}
                          {{-- </div>--}}
                        {{-- </div>--}}

                      </div>
                    </td>
                  </tr>
                    @endif
                  @else
                    <tr>
                      <td colspan="4" class="text-center">{{__('garden.no_item')}}</td>
                    </tr>
                  @endif
                @endforeach

              </tbody>
            </table>
          </div>

        </div>
          <div id="form-search-2">
            <div class="filter filter--1 align-items-end">
              <button type="button" class="filter__item btn btn-secondary" onClick="window.location.href='{{route('gardenFE.list')}}'" style="min-width:6.5em">
                <span>{{__('garden.last_list')}}</span>
              </button>

              @if($canCreate )
              <a href="{{route('gardenFE.create',['id'=>$selectCategories->id])}}" title="{{__('garden.create_garden')}}"
                class="filter__item btn btn-primary btn-reset-padding">
                <span style="white-space: nowrap;">{{__('garden.write')}}</span>
              </a>
              @endif
            </div>
          </div>
      @endif
    </div>
    @if (!isset($no_permission))
      @if(isset($gardenPaging))
        {!! Theme::partial('paging',['paging'=>$gardenPaging->appends(request()->input()) ]) !!}
      @else
        {!! Theme::partial('paging',['paging'=>$garden->appends(request()->input()) ]) !!}
      @endif
    {{-- {!! Theme::partial('paging',['paging'=>$garden->appends(request()->input()) ]) !!} --}}
    @endif
  </div>

  </div>

  </div>
</main>
{{-- popup show image --}}
{!! Theme::partial('popup.showImage',['slides'=> $slides]) !!}
{{--  popup show image --}}
<div class="modal fade modal--confirm" id="confirmPwdPost" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header align-items-center justify-content-lg-center">
            <span class="modal__key">
                <svg width="40" height="18" aria-hidden="true">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
                </svg>
            </span>
      </div>
      <div class="modal-body">
        <div class="d-lg-flex mx-lg-2">
          정답을 입력해야 보실 수 있습니다.
        </div>
        <div class="d-lg-flex align-items-center mx-3">
          <div class="d-lg-flex align-items-start flex-grow-1">
            <div class="form-group form-group--1 flex-grow-1 mr-lg-30 mb-3">
              <label for="hint" class="form-control">
                <input type="text" id="hint" value="" placeholder="&nbsp;"
                       maxlength="120" readonly style="cursor: unset;">
                <span class="form-control__label" style="cursor: unset;">{{__('garden.question_password')}}</span>
              </label>
            </div>
            <div class="form-group form-group--1 flex-grow-1 mb-3">
              <label for="passwordPostEdit" class="form-control form-control--hint">
                <input type="password" id="passwordPostEdit" name="pwd_post_edit" placeholder="&nbsp;"
                       value="" maxlength="16">
                <span class="form-control__label">
                <!-- 비밀번호를 입력하세요 -->
                {{__('garden.type_password')}}
                </span>
              </label>
              <span class="form-control__hint" id="msg">
              <!-- 질문 정답 입력 -->
              {{__('garden.answer_password')}}
              </span>
            </div>
          </div>
          <input type="hidden" id="url-detail" value="">
          <input type="hidden" id="id-detail" value="1">
          <div class="button-group mb-2" style="width: 168px;">
            <button type="button" class="btn btn-primary submitByPwdPostPopup">확인</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="{{ asset('js/loading.js') }}"></script>
<script>
  //document.addEventListener('contextmenu', event => event.preventDefault());
  $(function () {
      $("#submit_filter").click(function(){
          var startDate = $('#startDate').val();
          $('#startDate1').val(startDate);

          var endDate = $('#endDate').val();
          $('#endDate1').val(endDate);
      });
    $('.banner').slick({
      dots: false,
      nextArrow:
        '<button type="button" class="slick-next"><svg class="icon" xmlns="http://www.w3.org/2000/svg" width="8" height="15" viewBox="0 0 8 15"><g><g><path fill="#fff" d="M.85 14.169a.483.483 0 0 1-.35.145.495.495 0 0 1-.35-.845l6.155-6.155L.15 1.159a.495.495 0 0 1 .7-.7l6.505 6.505a.495.495 0 0 1 0 .7z"/></g></g></svg></button>',
      prevArrow:
        '<button type="button" class="slick-prev"><svg class="icon" xmlns="http://www.w3.org/2000/svg" width="8" height="15" viewBox="0 0 8 15"><g><g><path fill="#fff" d="M6.65 14.169a.483.483 0 0 0 .35.145.495.495 0 0 0 .35-.845L1.195 7.314 7.35 1.159a.495.495 0 0 0-.7-.7L.145 6.964a.495.495 0 0 0 0 .7z"/></g></g></svg></button>',
      autoplay: true,
      autoplaySpeed: 3000,
      variableWidth: true,
    });

    $(document.body).on('click', '.garden-show-popup', function (e) {
      e.preventDefault();
      e.stopPropagation();
      let $this = $(this);
      let $id = $this.attr('data-id');
      let $url = $this.attr('data-url');
      let $is_show = $this.attr('data-show');
      let $hint = $this.attr('data-hint');

      if ($is_show == 1) {
        $('#hint').val($hint);
        $('#passwordPostEdit').val('');
        $('#msg').html('질문 정답 입력');
        $('#url-detail').val($url);
        $('#id-detail').val($id);
        $('#confirmPwdPost').modal('show');
      } else {
        console.log($url);
        window.location.href = $url;
      }
    });

    $(document.body).on('click', '#btn-add-bookmarks', function (e) {
      var ids = $("input.item-select:checked").map(function() {
          return $(this).val();
        }).get();
        if (ids.length == 0) {
          alert('{{ __("garden.select_at_least") }}');
          return false;
        }

        var data = {'ids':ids, '_token':$('meta[name="csrf-token"]').attr('content')};
        $.ajax({
            type: 'POST',
            url: '{{ route("gardenFE.addMultipleBookmark") }}',
            data: data
        }).done(function (response) {
            alert(response.message);
            setTimeout(function () {
                window.location.reload();
            }, 500);
        }).fail(function (response) {
            var errors = $.parseJSON(response.responseText);
            $.each(errors, function(index, value) {
                alert(value);
            });
        }).always(function () {
            //
        });
    });

    $(document.body).on('click', '.submitByPwdPostPopup', function (e) {
      e.preventDefault();
      sendPassword();
    });

    $("#passwordPostEdit").keypress(function(e){
      let keycode = e.keyCode ? e.keyCode : e.which;
      if (keycode == '13'){
        sendPassword();
      }
    })
    function sendPassword(){
      if ($('[name="pwd_post_edit"]').val() == '') {
        $('#msg').html('<div style="color: #EC1469;">Password is required</div>');
        $('[name="pwd_post_edit"]').focus();
        return;
      }
      let $url = $('#url-detail').val();
      $.ajax({
        type: 'POST',
        url: '{{route('gardenFE.ajaxPasswdPost')}}',
        data: {
          _token: "{{ csrf_token() }}",
          'pwd_post': $('[name="pwd_post_edit"]').val(),
          'id': $('#id-detail').val(),
          'is_create': 0,
        },
        success: function (data) {
          if (data.check == true) {
            window.location.href = $url;
          } else {
            $('#msg').html(data.msg);
          }
        },
      });
    }
  })
</script>
