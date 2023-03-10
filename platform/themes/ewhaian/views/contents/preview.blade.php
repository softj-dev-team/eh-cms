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
              <h3 class="single__title title-main">{{$contents->title}}</h3>
              <div class="single__info">
                <div class="single__date">
                  <svg width="15" height="17" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                  </svg>
                  {{Carbon\Carbon::parse( $contents->published)->format('Y-m-d')}}
                </div>
                <div class="single__eye">
                  <svg width="16" height="10" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                  </svg>
                  {{ $contents->lookup ?? 0}}
                </div>
              </div>
            </div>
            <div class="row no-gutters justify-content-between">
              <div class="col-md-6">
                <p class="single__datetime">
                  <span class="single__icon">
                    <svg width="10" height="10" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_datetime"></use>
                    </svg>
                  </span>
                  <span class="item__datetime__detail">{{Carbon\Carbon::parse( $contents->start)->format('d M | h:i a')}} -  {{ Carbon\Carbon::parse( $contents->end)->format('d M | h:i a')}}</span>
                </p>
              </div>
              <div class="col-md-6">
                <p class="single__limit">{!! __('contents.enrollment_limit',['enrollment_limit'=>$contents->enrollment_limit]) !!}</p>
              </div>
            </div>
            {!! Theme::partial('member_post',['item'=> auth()->guard('member')->user()->id]) !!}
            <div class="editor">
                <div class="editor row justify-content-md-center ">
                    <div class="col-md-7 slick_banner">
                        @if ($contents->banner !== null)
                        <div class="block-img " style="width: 530px;height: 340px;">
                            <div class="img-bg"
                                style="width: 100%;height: 100%; background-image:url({{$contents->banner}})"
                                alt="{{$contents->title}}')">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
              {!!  $contents->content !!}
            </div>
            <div>
                {!! Theme::partial('attachments',['link'=> $contents->link,'file_upload'=>$contents->file_upload]) !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
