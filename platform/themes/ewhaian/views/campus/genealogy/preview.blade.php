
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
                {!! Theme::partial('campus.menu',['active'=>"genealogy"]) !!}
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
                        <h3 class="single__title title-main">{{titleGenealogy($genealogy,'')}}</h3>
                        <div class="single__info">
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{Carbon\Carbon::parse( today())->format('d M | h:i a')}}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $genealogy->lookup}}
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="editor row justify-content-md-center ">
                        <div class="col-md-7  slick_banner">
                            @if(!is_null($genealogy->images) && $genealogy->images!= "" )
                                @foreach ($genealogy->images as $item)
                                    @if ( !is_null($item))
                                        <div class="block-img " style="width: 530px;height: 340px;">
                                            <div class="img-bg"
                                                style="width: 100%;height: 100%; background-image:url('{{ $item}}"
                                                alt="{{titleGenealogy($genealogy,'')}}">
                                                <img src="{{ $item }}" alt="{{titleGenealogy($genealogy,'')}}">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    {!! Theme::partial('member_post',['item'=> auth()->guard('member')->user()->id]) !!}
                    <div class="clearfix" style="padding-bottom: 50px;"></div>
                    <div>
                        <div class="row" style="display: flex;align-items: center;">
                            <div class="col-md-12">
                                <label style="font-weight: bold;">{{__('campus.genealogy.categories')}} :</label>
                            </div>
                            @foreach ($major as $item)
                            <div class="flare_market_list">
                                <span class="alert bg-green" style="display: block;margin-left: 10px;">{{$item}}</span>
                             </div>
                            @endforeach
                        </div>

                        <div class="clearfix" style="padding-bottom: 20px;"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label style="font-weight: bold;">{{__('campus.genealogy.detail')}} :</label>
                            </div>
                            <div class="col-md-12">
                                {!! $genealogy->detail !!}
                            </div>

                        </div>
                        <div class="clearfix" style="padding-bottom: 20px;"></div>
                        <div>
                            {!! Theme::partial('attachments',['link'=> $genealogy->link,'file_upload'=>$genealogy->file_upload]) !!}
                        </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
