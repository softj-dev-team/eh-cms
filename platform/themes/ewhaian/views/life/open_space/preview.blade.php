<style>
    .table td,
    .table th {
        padding: .75rem;
        */ vertical-align: none;
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

                  {!! Theme::partial('life.menu',['active'=>"open_space"]) !!}

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
                        <div class="title">
                            <h3 class="single__title title-main">{{ $openSpace->title}}</h3>
                        </div>
                        <div class="single__info">
                            <div class="single__date">
                                {{-- <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime( $openSpace->created_at) ) }} --}}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $openSpace->views}}
                            </div>
                        </div>
                    </div>

                    {!! Theme::partial('member_post',['item'=> auth()->guard('member')->user()->id]) !!}
                    <div class="editor row justify-content-md-center ">
                        <div class="col-md-7 slick_banner">
                            @if($openSpace->images != "")
                                @foreach ($openSpace->images as $item)
                                    @if ($item !== null)
                                    <div class="block-img " style="width: 530px;height: 340px;">
                                        <div class="img-bg"
                                        style="width: 100%;height: 100%; background-image:url({{$item}})"
                                        alt="{{$openSpace->title}}')">
                                    </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <div class="row" @if( $openSpace->right_click > 0 ) id="detail" @endif style="margin: 0 10px;">
                        <table class="table">
                            <tr>
                                <td>{!! $openSpace->detail !!}</td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $openSpace->link,'file_upload'=>$openSpace->file_upload]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
