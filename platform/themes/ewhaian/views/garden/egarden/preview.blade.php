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
                {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>0,'egarden'=>1]) !!}
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
                            <h3 class="single__title title-main">{{$egarden->title}}</h3>
                        </div>
                        <div class="single__info">
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime( $egarden->created_at) ) }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $egarden->lookup}}
                            </div>
                        </div>
                    </div>

                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <div  class="editor" @if($egarden->right_click > 0 ) id="detail" @endif style="margin: 0 10px;">
                        @if(!is_null($egarden->banner))
                            <div class="block-img " style="width: 530px;height: 340px;margin: 0 auto;">
                                <div class="img-bg"
                                    style="width: 100%;height: 100%; background-image:url('{{$egarden->banner}}')"
                                    alt="{{$egarden->title}}')">
                                </div>
                            </div>
                        @endif()

                        {!!  $egarden->detail !!}
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $egarden->link,'file_upload'=>$egarden->file_upload]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<script>
    $(function(){
        $('#detail').on('contextmenu',function(e){
            return false;
        })


    });
</script>
<style>
    #detail {
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none
    }
</style>
