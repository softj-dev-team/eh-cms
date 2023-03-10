
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
                {!! Theme::partial('life.menu',['active'=>"flare_market_list"]) !!}
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
                        <h3 class="single__title title-main">{{$flare->title}}</h3>
                        <div class="single__info">
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime(  today() ) ) }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $flare->lookup ?? 0 }}
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters justify-content-between">

                        <div class="col-md-12">
                            <div class="single__limit" style="display: flex;align-items: center;justify-content: flex-end;"><label>Classification:</label> {!! Theme::partial('classification',['categories'=>$flare->categories ]) !!}</div>
                        </div>
                    </div>
                    {!! Theme::partial('member_post',['item'=> auth()->guard('member')->user()->id]) !!}
                    <br>
                    <div class="editor row justify-content-md-center ">
                        <div class="col-md-7 slick_banner">
                            @if($flare->images != "")
                                @foreach ($flare->images as $item)
                                    @if ($item !== null)
                                    <div class="block-img " style="width: 530px;height: 340px;">
                                        <div class="img-bg"
                                            style="width: 100%;height: 100%; background-image:url('{{  $item }}"
                                            alt="{{$flare->title}}')">
                                            <img src="{{  $item }}" alt="{{$flare->title}}">
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <div class="row">
                        <table class="table">
                            <tr>
                                <th style="width: 1px;white-space: nowrap;">{{__('life.flea_market.reason_selling')}}</th>
                                <td>{{$flare->reason_selling}}</td>
                            </tr>
                            <tr>
                                <th>{{__('life.flea_market.purchasing_price')}}</th>
                                <td>{{$flare->purchasing_price}}</td>
                            </tr>
                            <tr>
                                <th>{{__('life.flea_market.sale_price')}}</th>
                                <td>{{$flare->sale_price}}</td>
                            </tr>
                            <tr>
                                <th>{{__('life.flea_market.exchange')}}</th>
                                <td>{!! Theme::partial('life.elements.trade',['exchange'=>$flare->exchange ?? null]) !!}</td>
                            </tr>
                            <tr>
                                <th>{{__('life.flea_market.contact')}}</th>
                                <td>{{$flare->contact}}</td>
                            </tr>
                            <tr>
                                <th >{{__('life.flea_market.detail')}}</th>
                                <td style="word-break: break-word;">{!! $flare->detail !!}</td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $flare->link,'file_upload'=>$flare->file_upload]) !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
