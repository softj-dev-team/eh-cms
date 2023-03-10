<style>
    span.icon-label.disable svg {
        transform: scale(0.8);
        top: -3px;
        position: relative;
    }

    .single .comments .item__time span {
        font-size: 1.2em;
        color: #EC1469;
        width: 20px;
        height: 20px;
        margin-right: 0.71429em;
    }
    p {
        word-break: break-word;
    }
</style>
<main id="main-content" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->
                <div class="category-menu">
                    <h4 class="category-menu__title">{{__('event.menu__title')}}</h4>
                    <ul class="category-menu__links">
                        @foreach ($category as $item)
                        <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
                            <a href="{{route('event.event_list', ['idCategory'=>$item->id]) }}"
                                title="{{$item->name}}">{{$item->name}}</a>
                        </li>
                        @endforeach
                        <li class="category-menu__item">
                            <a href="{{route('event.cmt.list')}}" title="{{__('event.event_comments')}}">{{__('event.event_comments')}}</a>
                        </li>

                    </ul>
                </div>
                <!-- end of category menu -->
            </div>
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    @foreach (Theme::breadcrumb()->getCrumbs() as $i => $crumb)
                    @if ($i != (count(Theme::breadcrumb()->getCrumbs()) - 1))
                    <li>
                        <a href="{{ $crumb['url'] }}" title="{!! $crumb['label'] !!}">{!! $crumb['label'] !!}</a>
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
                        <h3 class="single__title title-main">{{$event->title}}</h3>
                        <div class="single__info">
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ $event->published }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $event->views ?? 0}}
                            </div>
                        </div>
                    </div>

                    {!! Theme::partial('member_post',['item'=> auth()->guard('member')->user()->id]) !!}
                    <div class="editor">
                        <div class="editor row justify-content-md-center ">
                            <div class="col-md-7 slick_banner">
                                @if ($event->banner !== null)
                                <div class="block-img " style="width: 530px;height: 340px;">
                                    <div class="img-bg"
                                        style="width: 100%;height: 100%; background-image:url({{$event->banner}})"
                                        alt="{{$event->title}}')">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        {{-- <img src="{{  get_image_url($event->banner, 'featured') }}" alt="{{$event->title}}"> --}}
                        {!! $event->content !!}
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $event->link,'file_upload'=>$event->file_upload]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
