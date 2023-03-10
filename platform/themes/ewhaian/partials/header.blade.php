<style>
  @media screen and (max-width: 991px) {
    .header .menu .sub-menu {
      display: none !important;
    }

    .header .menu .sub-menu.open-mobile {
      display: block !important;
    }

    .header .menu .sub-menu.close-mobile {
      display: none !important;
    }
  }

  @media screen and (min-width: 992px) {
    .menu__item > i.fas {
      display: none !important;
    }
  }
  .account__icon > a {
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
  }
  .header .account__icon svg {
      position: relative;
      top: 3px;
      margin-bottom: 4px;
  }
  .header .account__icon span {
      font-size: 10px;
      color: #444444;
      display: inline-block;
      width: 100%;
      -webkit-transform: scale(0.9);
  }

  .header .menu__item {
    margin-left: 10px;
    width: 100px;
    text-align: center;
  }

  .header .menu__link {
    width: 100px;
    display: block;
  }
</style>
<script>
  $(document).ready(function () {
    $('#hide-button').click(() => {
        $('#hide-button').hide();
        $('.account__overview').hide();
        $('#show-button').show();
      })

      $('#show-button').click(() => {
        $('#hide-button').show();
        $('.account__overview').show();
        $('#show-button').hide();
      })
  });
</script>

<!-- header -->
<header id="header" class="header">
  <div class="container">
    <div class="header__wrapper">
      @php
        $routeName = Illuminate\Support\Facades\Route::currentRouteName();
      @endphp

      <!-- login -->
        <div class="account d-none d-lg-block">
          @if (auth()->guard('member')->check() && $routeName != 'home.index')
            <a href="{{ route('public.member.dashboard') }}"><img class="account__image" src="{{ getLvlImage()  }}"
                                                                  alt="account image"></a>
            {{-- <img class="account__image" src="{{ getLvlImage() }}" alt="account image"> --}}
            <span id="show-button" style="cursor: pointer;">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                   class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                <path
                  d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
              </svg>
            </span>
            <span id="hide-button" style="cursor: pointer;display:none">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                   class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                <path
                  d="M10.79 12.912l-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.027 7.027 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.088z"/>
                <path
                  d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708l-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6l-12-12 .708-.708 12 12-.708.707z"/>
              </svg>
            </span>
            <div class="account__overview" style="display:none">
              <p class="d-flex justify-content-between account__info">
                <span class="account__name">{{ auth()->guard('member')->user()->nickname }}</span>
                <span class="account__level">{{__('header.level')}} {{ getLevelMember() }}  <b>({{getPercentLevelMember().'%'}}) </b></span>
              </p>
              <div class="account__process">
                <span style="width: {{getPercentLevelMember()}}%"></span>
              </div>
            </div>
            <div class="account__icon">
              <a href="javascript:void(0)" title="{{__('header.setting')}}" data-toggle="modal" class="open-AddBookDialog"
                 data-target="#confirmPopupSetting" data-id="notify">
                <svg width="17" height="17" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_setting"></use>
                </svg>
                <span class="">{{__('header.setting')}}</span>
              </a>
            </div>
            <div class="account__icon">
              <a href="javascript:void(0)" title="{{__('header.sign_out')}}"
                 onclick="event.preventDefault(); document.getElementById('logout-form-1').submit();" target="_parent">
                <svg width="16" height="15" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_logout"></use>
                </svg>
                <span class="">{{__('header.sign_out')}}</span>
              </a>
              <form id="logout-form-1" action="{{ route('public.member.logout') }}" method="POST"
                    style="display: block;" target="_parent">
                @csrf
              </form>
            </div>
          @endif

          @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('masterRoomFE.list') )
            <div class="account__icon">
              <a href="{{route('masterRoomFE.list')}}" title="{{__('header.master_room')}}" target="_parent">
                <svg width="23" height="14" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_alvar"></use>
                </svg>
                <span class="">{{__('header.master_room')}}</span>
              </a>
            </div>
          @endif

          @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('newContentsFE.list') )
            <div class="account__icon">
              <a href="{{route('newContentsFE.list')}}" title="{{__('header.new_contents')}}" target="_parent">
                <svg width="23" height="14" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_alvar"></use>
                </svg>
                <span class="">{{__('header.new_contents')}}</span>
              </a>
            </div>
          @endif
        </div>
        <!-- end of login -->

      {{-- Mobile --}}
      <div class="header__logo" style="text-align: left">
        <a href="{{route('home.index')}}" title="{{__('header.ewhaian')}}" target="_parent">
          <div class="block-img " style="width: 102px;height: 22px;">
            <div class="img-bg"
                 style="width: 100%;height: 100%; background-image:url('{{  setting('ewhaian_logo', Theme::asset()->url('img/ewha-logo.svg')  )}}')"
                 alt="Ewhaian">
            </div>
          </div>
        </a>
      </div>

      <ul class="header__menu menu">
        <li class="menu__logo d-lg-none">
          <a href="/" title="{{__('header.ewhaian')}}">
            <div class="block-img " style="width: 102px;height: 22px;">
              <div class="img-bg"
                   style="width: 100%;height: 100%; background-image:url('{{  setting('ewhaian_logo', Theme::asset()->url('img/ewha-logo.svg')  )}}')"
                   alt="Ewhaian">
              </div>
            </div>
          </a>
        </li>
        @if (auth()->guard('member')->check())
          <li class="d-lg-none">
            <div class="account">
              <a href="{{ route('public.member.dashboard') }}"><img class="account__image" src="{{ getLvlImage()  }}"
                                                                    alt="account image"></a>
              {{-- <img class="account__image" src="{{ getLvlImage() }}" alt="account image"> --}}
              <div class="account__overview">
                <p class="d-flex justify-content-between account__info">
                  <span class="account__name">{{ auth()->guard('member')->user()->nickname }}</span>
                  <span
                    class="account__level">Level {{ getLevelMember() }}  <b>({{getPercentLevelMember().'%'}}) </b></span>
                </p>
                <div class="account__process">
                  <span style="width: 33%"></span>
                </div>
              </div>
              {{-- <div class="account__icon">
                  <a href="#" title="">
                      <svg width="15" height="17" aria-hidden="true">
                          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_alarm"></use>
                      </svg>
                      <span class="">Notice</span>
                  </a>
              </div> --}}
              <div class="account__icon">
                <a href="javascript:void(0)" title="{{__('header.setting')}}" data-toggle="modal"
                   data-target="#confirmPopupSetting">
                  <svg width="17" height="17" aria-hidden="true">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_setting"></use>
                  </svg>
                  <span class="">{{__('header.setting')}}</span>
                </a>
              </div>
              <div class="account__icon">
                <a href="javascript:void(0)" title="{{__('header.sign_out')}}"
                   onclick="event.preventDefault(); document.getElementById('logout-form-2').submit();"
                   target="_parent">
                  <svg width="16" height="15" aria-hidden="true">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_logout"></use>
                  </svg>
                  <span class="">{{__('header.sign_out')}}</span>
                </a>
                <form id="logout-form-2" action="{{ route('public.member.logout') }}" method="POST"
                      style="display: block;" target="_parent">
                  @csrf
                </form>
              </div>
              @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('masterRoomFE.list') )
                <div class="account__icon">
                  <a href="{{route('masterRoomFE.list')}}" title="{{__('header.master_room')}}" target="_parent">
                    <svg width="23" height="14" aria-hidden="true">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_alvar"></use>
                    </svg>
                    <span class="">{{__('header.master_room')}}</span>
                  </a>
                </div>
              @endif
              @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('newContentsFE.list') )
                <div class="account__icon">
                  <a href="{{route('newContentsFE.list')}}" title="{{__('header.new_contents')}}" target="_parent">
                    <svg width="23" height="14" aria-hidden="true">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_alvar"></use>
                    </svg>
                    <span class="">{{__('header.new_contents')}}</span>
                  </a>
                </div>
              @endif
            </div>
          </li>
        @endif
        <li class="menu__item" style="position: relative;margin-left:0px;">
          <a href="{{route('eh_introduction.list')}}" title="{{__('header.ewhaian')}}" class="menu__link"
             target="_parent">{{__('header.ewhaian')}}</a>

          <i class="fas fa-angle-right" style="margin-left: 15px; cursor: pointer" onclick="collapseMenu(event)"></i>

          <ul class="sub-menu" style="right: unset;left: 0;">
            @foreach ($composer_INTRODUCTION_SUB_MENU as $item)
              <li class="sub-menu__item">
                <a href="{{route('eh_introduction.detail',['id'=>$item->getFirstIntro()->id])}}" class="sub-menu__link"
                   title="{{$item->name}}" target="_parent">{{$item->name}}</a>
              </li>
            @endforeach

            <li class="sub-menu__item">
              <a href="{{route('eh_introduction.faq')}}" title="{{__('header.faqs')}}" class="sub-menu__link"
                 target="_parent">{{__('header.faqs')}}</a>
            </li>
            <li class="sub-menu__item">
              <a href="{{route('eh_introduction.contact')}}" class="sub-menu__link" title="{{__('header.contact_us')}}"
                 target="_parent">{{__('header.contact_us')}}</a>
            </li>
            <li class="sub-menu__item">
              <a href="{{route('eh_introduction.notices.list') }}" class="sub-menu__link"
                 title="{{__('header.notices')}}" target="_parent">{{__('header.notices')}}</a>
            </li>
          </ul>
        </li>
        <li class="menu__item" style="position: relative;">
          <a href="{{route('event.event_list',['idCategory'=>$composer_EVENT_SUB_MENU->first()])}}"
             title="{{__('header.event')}}" class="menu__link" target="_parent">{{__('header.event')}}</a>

          <i class="fas fa-angle-right" style="margin-left: 15px; cursor: pointer" onclick="collapseMenu(event)"></i>

          <ul class="sub-menu" style="right: unset;left: 0;">
            @foreach( $composer_EVENT_SUB_MENU as $key => $item)
              <li class="sub-menu__item">
                <a href="{{route('event.event_list', ['idCategory'=>$item->id]) }}" class="sub-menu__link"
                   title="{{$item->name}}" target="_parent">{{$item->name}}</a>
              </li>
            @endforeach
            <li class="sub-menu__item">
              <a href="{{route('event.cmt.list') }}" class="sub-menu__link"
                 title="Event Comments" target="_parent">{{__('header.event_comments')}}</a>
            </li>
          </ul>
        </li>
        <li class="menu__item" style="position: relative;">
          <a href="{{route('contents.contents_list', ['idCategory'=>$composer_CONTENTS_SUB_MENU->first() ]) }}"
             title="{{__('header.contents')}}" class="menu__link" target="_parent">{{__('header.contents')}}</a>

          <i class="fas fa-angle-right" style="margin-left: 15px; cursor: pointer" onclick="collapseMenu(event)"></i>

          <ul class="sub-menu" style="right: unset;left: 0;">
            @foreach($composer_CONTENTS_SUB_MENU as $key => $item)
              <li class="sub-menu__item">
                <a href="{{route('contents.contents_list', ['idCategory'=>$item->id]) }}"
                   class="sub-menu__link" title="{{$item->name }}" target="_parent">{{$item->name }}</a>
              </li>
            @endforeach
          </ul>
        </li>
        <li class="menu__item" style="position: relative;">
          <a href="{{route('life.open_space_list')}}" title="{{__('header.life')}}" class="menu__link"
             target="_parent">{{__('header.life')}}</a>

          <i class="fas fa-angle-right" style="margin-left: 15px; cursor: pointer" onclick="collapseMenu(event)"></i>

          <ul class="sub-menu" style="left: unset;right: -2px;">
            <li class="sub-menu__item"><a href="{{route('life.open_space_list') }}" class="sub-menu__link"
                                          title="{{__('header.open_space')}}"
                                          target="_parent">{{__('header.open_space')}}</a></li>
            <li class="sub-menu__item"><a href="{{route('life.flare_market_list') }}" class="sub-menu__link"
                                          title="{{__('header.flea_market')}}"
                                          target="_parent">{{__('header.flea_market')}}</a></li>

            <li class="sub-menu__item"><a href="{{route('life.part_time_jobs_list') }}"
                                          class="sub-menu__link" title="{{__('header.part-time_job')}}"
                                          target="_parent">{{__('header.part-time_job')}}</a></li>
            <li class="sub-menu__item"><a href="{{route('life.shelter_list')}}" class="sub-menu__link"
                                                        title="{{__('header.shelter_info')}}"
                                                        target="_parent">{{__('header.shelter_info')}}</a></li>
            <li class="sub-menu__item"><a href="{{route('life.advertisements_list')}}"
                                          class="sub-menu__link" title="{{__('header.advertisements')}}"
                                          target="_parent">{{__('header.advertisements')}}</a></li>

          </ul>
        </li>
        <li class="menu__item" style="position: relative;">
{{--          <a href="{{route('campus.campus.evaluation_list')}}" title="{{__('header.campus')}}" class="menu__link"--}}
{{--             target="_parent">{{__('header.campus')}}</a>--}}
          <a href="{{route('campus.evaluation_comments_major')}}" title="{{__('header.campus')}}" class="menu__link"
             target="_parent">{{__('header.campus')}}</a>

          <i class="fas fa-angle-right" style="margin-left: 15px; cursor: pointer" onclick="collapseMenu(event)"></i>

          <ul class="sub-menu" style="left: unset;right: -2px;">
            @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('scheduleFE.list') )
              <li class="sub-menu__item"><a href="{{route('scheduleFE.timeline.v2')}}" class="sub-menu__link"
                                            title="{{__('header.timetable')}}"
                                            target="_parent">{{__('header.timetable')}}</a></li>
            @else
              <li class="sub-menu__item"><a href="javascript:void()" class="sub-menu__link"
                                            onclick="alert('로그인 후 이용 가능한 게시판 입니다')"
                                            title="{{__('header.timetable')}}"
                                            target="_parent">{{__('header.timetable')}}</a></li>
            @endif
            @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('campus.calculator_list') )
              <li class="sub-menu__item"><a href="{{route('campus.calculator_list')}}" class="sub-menu__link"
                                            title="평점계산기" target="_parent">평점계산기</a></li>
            @endif
{{--            <li class="sub-menu__item"><a href="{{route('campus.campus.evaluation_list')}}" class="sub-menu__link"--}}
            <li class="sub-menu__item"><a href="{{route('campus.evaluation_comments_major')}}" class="sub-menu__link"
                                          title="{{__('header.lecture_evaluation')}}"
                                          target="_parent">{{__('header.lecture_evaluation')}}</a></li>
            <li class="sub-menu__item"><a href="{{route('campus.genealogy_list')}}" class="sub-menu__link"
                                          title="{{__('header.eh_genealogy')}}"
                                          target="_parent">{{__('header.eh_genealogy')}}</a></li>
            @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('oldGenealogyFE.list') )
              <li class="sub-menu__item"><a href="{{route('campus.old.genealogy')}}" class="sub-menu__link"
                                            title="{{__('header.old_data_genealogy')}}"
                                            target="_parent">{{__('header.old_data_genealogy')}}</a></li>
            @endif
            <li class="sub-menu__item"><a href="{{route('campus.study_room_list')}}" class="sub-menu__link"
                                          title="{{__('header.study_room')}}"
                                          target="_parent">{{__('header.study_room')}}</a></li>
          </ul>
        </li>
      </ul>
      @if (!is_null( auth()->guard('member')->user() )
              && auth()->guard('member')->user()->hasPermission('gardenFE.list')
              && auth()->guard('member')->user()->certification !== "real_name_certification"
      )
        <a href="{{route('gardenFE.passwd')}}" title="{{__('header.garden_list')}}"
           class="header__key" target="_parent">
          <svg width="40" height="18" aria-hidden="true">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
          </svg>
        </a>
      @endif

      <button class="hamburger d-lg-none @php if (!auth()->guard('member')->user()) echo 'hamburger-without-login' @endphp" type="button">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
      </button>
    </div>
  </div>
</header>
<!-- end header -->

<script>
  function collapseMenu(event) {
    console.log(event.target.nextElementSibling.style.display);
    if (!event.target.nextElementSibling.className.includes('open-mobile')) {
      event.target.nextElementSibling.className = 'sub-menu open-mobile';
      event.target.className = 'fas fa-angle-down';
    } else {
      event.target.nextElementSibling.className = 'sub-menu close-mobile';
      event.target.className = 'fas fa-angle-right';
    }
  }
</script>
