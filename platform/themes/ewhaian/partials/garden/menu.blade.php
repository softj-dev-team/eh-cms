<div class="sidebar-template__control">
  <div class="nav nav-left">
    <ul class="nav__list">
      <p class="nav__title">
        <svg width="40" height="18" aria-hidden="true">
          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
        </svg>
      </p>
      @foreach ($categories as $item)
        @if($item->special_garden == 6 || $item->special_garden == 7)
          @continue
        @endif
        <li class="nav__item">
          <a @if($item->id == $id) class="active" @endif href="{{route('gardenFE.show',['id'=>$item->id])}}"
             title="{{$item->name}}">{{$item->name}}</a>
        </li>
      @endforeach

      @foreach ($categories as $item)
        @if($item->special_garden != 7)
          @continue
        @endif
        <li class="nav__item">
          <a @if(isset($egarden)) class="active" @endif href="{{route('egardenFE.home')}}"
             title="{{$item->name}}">{{$item->name}}</a>
        </li>
      @endforeach
      @foreach ($categories as $item)
        @if($item->special_garden != 6)
          @continue
        @endif
        <li class="nav__item">
          <a @if($item->id == $id) class="active" @endif href="{{route('gardenFE.show',['id'=>$item->id])}}"
             title="{{$item->name}}">{{$item->name}}</a>
        </li>
      @endforeach
      <li class="nav__item">
        <a @if (!empty($isGardenBookmark)) class="active" @endif href="{{ route('gardenFE.bookmarks') }}"
           title="{{ __('garden.bookmarks') }}">{{ __('garden.bookmarks') }}</a>
      </li>
      <li>
        @if ($composer_SLIDES_ACCOUNT != null )
          {!! Theme::partial('left_banner',[
              'class' => '',
              'composer_SLIDES_ACCOUNT' => $composer_SLIDES_ACCOUNT,
              'route' => route('gardenFE.clicker')
          ]) !!}
        @endif
      </li>
    </ul>
  </div>
</div>
