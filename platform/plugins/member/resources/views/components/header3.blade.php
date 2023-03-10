<!-- header -->
<header id="header" class="header">
    <div class="containerHeader">
        <div class="header__wrapper">
            @if (auth()->guard('member')->check())
            <!-- login -->
            <div class="account d-none d-md-block">
                {{-- <a href="{{ route('public.member.dashboard') }}"><img class="account__image" src="{{ getLvlImage()  }}" alt="account image"></a> --}}
                <img class="account__image" src="{{ getLvlImage() }}" alt="account image">
                <div class="account__overview">
                    <p class="d-flex justify-content-between account__info">
                        <span class="account__name">{{ auth()->guard('member')->user()->nickname }}</span>
                        <span class="account__level">Level {{ getLevelMember() }}  <b>({{getPercentLevelMember().'%'}}) </b></span>
                    </p>
                    <div class="account__process">
                        <span style="width: {{getPercentLevelMember()}}%"></span>
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
                    <a href="{{ route('public.member.settings') }}" title="Setting" target="_parent">
                        <svg width="17" height="17" aria-hidden="true">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_setting"></use>
                        </svg>
                        <span class="">Setting</span>
                    </a>
                </div>
                <div class="account__icon">
                    <a href="javascript:void(0)" title="Sign Out"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();" target="_parent">
                        <svg width="16" height="15" aria-hidden="true">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_logout"></use>
                        </svg>
                        <span class="">Sign Out</span>
                    </a>
                    <form id="logout-form-2" action="{{ route('public.member.logout') }}" method="POST"
                        style="display: block;" target="_parent">
                        @csrf
                    </form>
                </div>
                @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('masterRoomFE.list') )
                <div class="account__icon">
                    <a href="{{route('masterRoomFE.list')}}" title="Master Room" target="_parent">
                        <svg width="23" height="14" aria-hidden="true">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_alvar"></use>
                        </svg>
                        <span class="">Master Room</span>
                    </a>
                </div>
                @endif
                @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('newContentsFE.list') )
                <div class="account__icon">
                    <a href="{{route('newContentsFE.list')}}" title="Master Room" target="_parent">
                        <svg width="23" height="14" aria-hidden="true">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_alvar"></use>
                        </svg>
                        <span class="">New Contents</span>
                    </a>
                </div>
                @endif
                <!-- end of login -->

            </div>
            @endif

            <div class="header__logo" style="text-align: left">
                <a href="{{route('home.index')}}" title="Home" target="_parent">
                    <img src="{{Theme::asset()->url('img/ewha-logo.svg')}}" alt="">
                </a>
            </div>

            <ul class="header__menu menu">
                <li class="menu__logo d-md-none">
                    <a href="/" title="" >
                        <img src="{{Theme::asset()->url('img/ewha-logo.svg')}}" alt="">
                    </a>
                </li>
                @if (auth()->guard('member')->check())
                <li class="d-md-none">
                    <div class="account">
                        {{-- <a href="{{ route('public.member.dashboard') }}"><img class="account__image" src="{{ getLvlImage()  }}" alt="account image"></a> --}}
                        <img class="account__image" src="{{ getLvlImage() }}" alt="account image">
                        <div class="account__overview">
                            <p class="d-flex justify-content-between account__info">
                                <span class="account__name">{{ auth()->guard('member')->user()->nickname }}</span>
                                <span class="account__level">Level {{ getLevelMember() }}  <b>({{getPercentLevelMember().'%'}}) </b></span>
                            </p>
                            <div class="account__process">
                                <span style="width: {{getPercentLevelMember()}}%"></span>
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
                            <a href="{{ route('public.member.settings') }}" title="Setting" target="_parent">
                                <svg width="17" height="17" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_setting"></use>
                                </svg>
                                <span class="">Setting</span>
                            </a>
                        </div>
                        <div class="account__icon">
                            <a href="javascript:void(0)" title="Sign Out"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" target="_parent">
                                <svg width="16" height="15" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_logout"></use>
                                </svg>
                                <span class="">Sign Out</span>
                            </a>
                            <form id="logout-form-1" action="{{ route('public.member.logout') }}" method="POST"
                                style="display: block;" target="_parent">
                                @csrf
                            </form>
                        </div>
                        @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('masterRoomFE.list') )
                        <div class="account__icon">
                            <a href="{{route('masterRoomFE.list')}}" title="Master Room" target="_parent">
                                <svg width="23" height="14" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_alvar"></use>
                                </svg>
                                <span class="">Master Room</span>
                            </a>
                        </div>
                        @endif
                        @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('newContentsFE.list') )
                        <div class="account__icon">
                            <a href="{{route('newContentsFE.list')}}" title="Master Room" target="_parent">
                                <svg width="23" height="14" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_alvar"></use>
                                </svg>
                                <span class="">New Contents</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </li>
                @endif
                <li class="menu__item" style="position: relative;margin-left:0px;">
                    <a href="{{route('eh_introduction.list')}}" title="EWHAIAN" class="menu__link" target="_parent">EWHAIAN</a>
                    <ul class="sub-menu" style="right: unset;left: 0;">
                        @foreach ($composer_INTRODUCTION_SUB_MENU as $item)
                        <li class="sub-menu__item">
                            <a href="{{route('eh_introduction.detail',['id'=>$item->getFirstIntro()->id])}}" class="sub-menu__link"
                                title="{{$item->name}}" target="_parent">{{$item->name}}</a>
                        </li>
                        @endforeach

                        <li class="sub-menu__item">
                            <a href="{{route('eh_introduction.faq')}}" title="FAQs" class="sub-menu__link" target="_parent">FAQs</a>
                        </li>
                        <li class="sub-menu__item">
                            <a href="{{route('eh_introduction.contact')}}" class="sub-menu__link" title="Contact us" target="_parent">Contact us</a>
                        </li>
                        <li class="sub-menu__item">
                            <a href="{{route('eh_introduction.notices.list') }}" class="sub-menu__link"
                                title="Notices" target="_parent">Notices</a>
                        </li>
                    </ul>
                </li>
                <li class="menu__item" style="position: relative;">
                    <a href="{{route('event.event_list',['idCategory'=>$composer_EVENT_SUB_MENU->first()])}}" title="EVENT" class="menu__link" target="_parent">EVENT</a>
                    <ul class="sub-menu" style="right: unset;left: 0;">

                        @foreach( $composer_EVENT_SUB_MENU as $key => $item)
                        <li class="sub-menu__item">
                            <a href="{{route('event.event_list', ['idCategory'=>$item->id]) }}" class="sub-menu__link"
                                title="{{$item->name}}" target="_parent">{{$item->name}}</a>
                        </li>
                        @endforeach
                        <li class="sub-menu__item">
                            <a href="{{route('event.cmt.list') }}" class="sub-menu__link"
                                title="Event Comments" target="_parent">Event Comments</a>
                        </li>
                    </ul>
                </li>
                <li class="menu__item" style="position: relative;">
                    <a href="{{route('contents.contents_list', ['idCategory'=>$composer_CONTENTS_SUB_MENU->first() ]) }}"
                        title="CONTENTS" class="menu__link" target="_parent">CONTENTS</a>
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
                    <a href="{{route('life.open_space_list')}}" title="LIFE" class="menu__link" target="_parent">LIFE</a>
                    <ul class="sub-menu" style="left: unset;right: 0;">
                        <li class="sub-menu__item"><a href="{{route('life.open_space_list') }}" class="sub-menu__link"
                                title="Open Space" target="_parent">Open Space</a></li>
                        <li class="sub-menu__item"><a href="{{route('life.flare_market_list') }}" class="sub-menu__link"
                                title="Flea Market" target="_parent">Flea Market</a></li>
                        <li class="sub-menu__item"><a href="{{route('life.part_time_jobs_list') }}"
                                class="sub-menu__link" title="Part-time Job" target="_parent">Part-time Job</a></li>
                        <li class="sub-menu__item" ><a href="{{route('life.shelter_list')}}" class="sub-menu__link"
                                title="Shelter Info" target="_parent">Shelter Info</a></li>
                        <li class="sub-menu__item"><a href="{{route('life.advertisements_list')}}"
                                class="sub-menu__link" title="Advertisements" target="_parent">Advertisements</a></li>

                    </ul>
                </li>
                <li class="menu__item" style="position: relative;">
                    <a href="{{route('campus.evaluation_comments_major')}}" title="CAMPUS" class="menu__link" target="_parent">CAMPUS</a>
                    <ul class="sub-menu" style="left: unset;right: 0;">
                        @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('scheduleFE.list') )
                        <li class="sub-menu__item"><a href="{{route('scheduleFE.timeline.v2')}}" class="sub-menu__link"
                                title="Timetable" target="_parent">Timetable</a></li>
                        @endif
                        <li class="sub-menu__item"><a href="{{route('campus.evaluation_comments_major')}}" class="sub-menu__link"
                                title="Lecture evaluation" target="_parent">Lecture evaluation</a></li>
                        <li class="sub-menu__item"><a href="{{route('campus.genealogy_list')}}" class="sub-menu__link"
                                title="EH genealogy" target="_parent">EH genealogy</a></li>
                        <li class="sub-menu__item"><a href="{{route('campus.study_room_list')}}" class="sub-menu__link"
                                title="Study room" target="_parent">Study room</a></li>
                    </ul>
                </li>
            </ul>
            @if (!is_null( auth()->guard('member')->user() )
                    && auth()->guard('member')->user()->hasPermission('gardenFE.list')
                    && auth()->guard('member')->user()->certification !== "real_name_certification"
            )
            <a href="javascript:void(0)" data-toggle="modal" data-target="#confirmPopup" title="Garden List"
                class="header__key" target="_parent" style="color: #ec1469 !important;">
                <svg width="40" height="18" aria-hidden="true">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
                </svg>
            </a>
            @endif

            <button class="hamburger d-md-none" type="button">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </button>
        </div>
    </div>
</header>
<!-- end header -->
<script>
$('.hamburger').on('click', function () {
      var self = $(this);
      var header = $('#header');

      self.toggleClass('opened');
      header.toggleClass('opened');
      header.find('.menu').toggleClass('opened');
});
</script>
