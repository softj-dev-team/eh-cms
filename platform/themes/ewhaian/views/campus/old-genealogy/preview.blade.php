
<style>
    .table td, .table th {
        padding: .75rem; */
        vertical-align: none;
        border-top: none;
        text-alid
    }
    .table th {
    text-align: left;
    }
    .table tr:last-child td {
    border-bottom: none;
}
</style>

<main id="main-flare-detail" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->
                {!! Theme::partial('campus.menu',['active'=>"oldGenealogy"]) !!}
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
                        <h3 class="single__title title-main">{{$oldGenealogy->title}}</h3>
                        <div class="single__info">
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime( today() ) ) }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $oldGenealogy->lookup ?? 0}}
                            </div>
                        </div>
                    </div>

                    {!! Theme::partial('member_post',['item'=> auth()->guard('member')->user()->id]) !!}
                    <br>
                    <div class="editor row justify-content-md-center ">
                        <div class="col-md-7  slick_banner">
                            @if(!is_null($oldGenealogy->images) && $oldGenealogy->images!= "" )
                                @foreach ($oldGenealogy->images as $item)
                                    @if ( !is_null($item))
                                        <div class="block-img " style="width: 530px;height: 340px;">
                                            <div class="img-bg"
                                                style="width: 100%;height: 100%; background-image:url('{{ $item}}"
                                                alt="{{$oldGenealogy->title}}')">
                                                <img src="{{ $item }}" alt="{{$oldGenealogy->title}}">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        {!! $oldGenealogy->detail !!}
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $oldGenealogy->link,'file_upload'=>$oldGenealogy->file_upload]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
