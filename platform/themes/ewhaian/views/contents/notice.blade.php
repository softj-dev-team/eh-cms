<main id="main-content" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
         <!-- category menu -->
         <div class="category-menu">
            <h4 class="category-menu__title">{{__('contents')}}</h4>
            <ul class="category-menu__links">
              @foreach ($category as $item)
              <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
                <a href="{{route('contents.contents_list', ['idCategory'=>$item->id]) }}" title="$item->name"  >{{$item->name}}</a>
              </li>
              @endforeach
            </ul>
          </div>
          <!-- end of category menu -->
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

          <div class="event-details single">
            <div class="single__head">
              <h3 class="single__title title-main">{{$notices->name}}</h3>
              <div class="single__info" style="white-space: nowrap;">
                <div class="single__eye">
                  <svg width="16" height="10" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                  </svg>
                  {{$notices->lookup  ?? 0}}
                </div>
              </div>
            </div>

            <div class="clearfix" style="padding-bottom: 40px;"></div>
            <div class="editor">
              {!!$notices->notices !!}
            </div>
          </div>
          {!! Theme::partial('contents.index_sub',[
              'contents' => $subList['contents'],
              'categories' => $subList['categories'],
              'idCategory' => $subList['idCategory'],
              'selectCategories' => $subList['selectCategories'],
              'style' => $subList['style'],
              'canCreate' => $subList['canCreate']
            ]) !!}
        </div>
      </div>
    </div>
  </main>
