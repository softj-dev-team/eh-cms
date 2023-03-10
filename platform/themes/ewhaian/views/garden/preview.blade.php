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
                {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>$garden->categories_gardens_id]) !!}
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
                        <div class="title"> <h3 class="single__title title-main">{{$garden->title}}</h3></div>
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
                                {{ $garden->lookup ?? 0}}
                            </div>
                        </div>
                    </div>

                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <div class="row" @if($garden->right_click > 0 ) id="detail" @endif  style="margin: 0 10px;">
                        <table class="table">
                            <tr>
                                <td>
                                    <div style="word-break: break-all;">
                                        {!! $garden->detail !!}
                                    </div>

                                </td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $garden->link,'file_upload'=>$garden->file_upload]) !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
<script>
 $('#detail').on('contextmenu',function(e){
            return false;
})
</script>
<style>
#detail {
    -webkit-user-select:none;
    -khtml-user-select:none;
    -moz-user-select:none;
    -ms-user-select:none;
    user-select:none
}

</style>
