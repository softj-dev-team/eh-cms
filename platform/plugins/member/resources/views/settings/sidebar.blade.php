<div class="col-12 col-lg-3">
  <div class="list-group mb-3 br2" style="box-shadow: rgb(204, 204, 204) 0px 1px 1px;">
    <div class="list-group-item fw6 bn light-gray-text">
      {{ trans('plugins/member::dashboard.sidebar_title') }}
    </div>
    <a href="{{ route('public.member.settings') }}"
       class="list-group-item list-group-item-action bn @if (Route::currentRouteName() == 'public.member.settings') active @endif">
      <i class="fas fa-user-circle mr-2"></i>
      <span>{{ trans('plugins/member::dashboard.sidebar_information') }}</span>
    </a>
    <a href="{{ route('public.member.get.freshman.sprout') }}"
       class="list-group-item list-group-item-action bn @if (Route::currentRouteName() == 'public.member.get.freshman.sprout') active @endif">
      <i class="fas fa-user-circle mr-2"></i>
      <span>새내기 인증</span>
    </a>
    <a href="{{ route('public.member.get.freshman.ewhain') }}"
       class="list-group-item list-group-item-action bn @if (Route::currentRouteName() == 'public.member.get.freshman.ewhain') active @endif">
      <i class="fas fa-user-circle mr-2"></i>
      <span>이화인 인증</span>
    </a>
    <a href="{{ route('public.member.security') }}"
       class="list-group-item list-group-item-action bn @if (Route::currentRouteName() == 'public.member.security') active @endif">
      <i class="fas fa-user-lock mr-2"></i>
      <span>보안</span>
    </a>
    <a href="{{ route('public.member.notification') }}"
       class="list-group-item list-group-item-action bn @if (Route::currentRouteName() == 'public.member.notification') active @endif">
      <i class="fas fa-bell mr-2"></i>
      <span>알림 수신 설정</span>
    </a>
  </div>
</div>
