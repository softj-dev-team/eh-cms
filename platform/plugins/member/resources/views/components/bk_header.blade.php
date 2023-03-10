<nav class="navbar navbar-expand-md navbar-light bg-white bb b--black-10">
  <div class="container">
    <div class="navbar-header page-logo">
        <a class="navbar-brand" href="{{ url('/') }}" title="Home">
            <img src="{{ url(setting('admin_logo', config('core.base.general.logo'))) }}" alt="logo" class="logo-default" style="max-width: 90px;"/>
        </a>
      </div>

    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <!-- Right Side Of Navbar -->
      <ul class="navbar-nav ml-auto my-2">
        <!-- Authentication Links -->
        @if (!auth()->guard('member')->check())
          <li>
            <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db" style="text-decoration: none; line-height: 32px;" href="{{ route('public.member.login') }}">
              {{ trans('plugins/member::dashboard.login-cta') }}
            </a>
          </li>
          <li>
            <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db" style="text-decoration: none; line-height: 32px;" href="{{ route('public.member.register') }}">
              {{ trans('plugins/member::dashboard.register-cta') }}
            </a>
          </li>
        @else
          <li>
            <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db mr2" style="text-decoration: none; line-height: 32px;" href="{{ route('public.member.dashboard') }}" title="{{ trans('plugins/member::dashboard.header_profile_link') }}">
              <span>
                <img src="{{ auth()->guard('member')->user()->avatar }}" class="br-100 v-mid mr1" style="width: 30px;">
                <span>{{ auth()->guard('member')->user()->nickname }}</span>
              </span>
            </a>
          </li>
          <li>
            <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db mr2" style="text-decoration: none; line-height: 32px;" href="{{ route('public.member.settings') }}" title="{{ trans('plugins/member::dashboard.header_settings_link') }}">
              <i class="fas fa-cogs mr1"></i>{{ trans('plugins/member::dashboard.header_settings_link') }}
            </a>
          </li>
          {!! apply_filters(MEMBER_TOP_MENU_FILTER, null) !!}
          <li>
            <a class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db" style="text-decoration: none; line-height: 32px;" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="{{ trans('plugins/member::dashboard.header_logout_link') }}">
              <i class="fas fa-sign-out-alt mr1"></i><span >{{ trans('plugins/member::dashboard.header_logout_link') }}</span>
            </a>
            <form id="logout-form" action="{{ route('public.member.logout') }}" method="POST" style="display: block;">
              @csrf
            </form>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
