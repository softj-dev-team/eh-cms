@php
    $idLogin = auth()->guard('member')->user()->id_login;

@endphp
<style>
    .table td,
    .table th {
        padding: .75rem;
        vertical-align: unset;
        border-top: none;
        text-align: center;
    }

    .table th {
        text-align: left;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    #background {
        position: absolute;
        z-index: 1 !important;
        background: transparent !important;
        /* display:flex; */
        top: 0px;
        flex-wrap: wrap;
        width: 100%;
    }

    .bg-text {
        color: lightgrey;
        font-size: 50px;
        /* transform:rotate(320deg);
        -webkit-transform:rotate(320deg); */
        margin-top: -10px;
        margin-bottom: 70px;
        opacity: 0.1 !important;
        text-align: center;
        /* width: 20%; */
    }

    .sidebar-template__content {
        position: relative;
    }

    .item__action {
        z-index: 3 !important;
    }

    .item__time {
        z-index: 3 !important;
    }

    .account__icon {
        z-index: 1059 !important;
    }

    .form-control--textarea {
        z-index: 1059 !important;
    }

    .form-submit {
        z-index: 3 !important;
    }

    .btn-cancel {
        color: #ffffff;
        background-color: #444444;
    }

    .btn-cancel:hover {
        color: #ffffff !important;
        background-color: #444444 !important;
        text-decoration: none;
    }

    .btn-ok {
        border: 1px solid transparent;
        background-color: #dddddd;
        color: #444444;
    }

    .btn-ok:hover {
        background-color: #dddddd !important;
        color: #444444 !important;
        text-decoration: none;
    }

    .single__info svg {
        color: #999999;
        position: relative;
    }

    #main-content {
        position: relative;
        z-index: 99;
    }

    .page-detail {
        position: relative;
        z-index: 99;
    }

    .like__area {
        position: absolute;
        right: 50px !important;
        padding-top: 6px;
    }

    .page-link {
        background: transparent
    }

    #detail {
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none
    }
</style>
<div class="loading-section hide">
    <img class="loading-image" src="/storage/uploads/back-end/logo/logo-spinner.png"/>
</div>
<main id="main-flare-detail" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->
            {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>$selectCategories->id]) !!}
            <!-- end of category menu -->
            </div>
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    @foreach (Theme::breadcrumb()->getCrumbs() as $i => $crumb)
                        @if ($i != (count(Theme::breadcrumb()->getCrumbs()) - 1))
                            <li>
                                <a href="{{ $crumb['url'] }}">{!! $crumb['label'] !!}</a>
                                <svg width="4" height="6" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                         xlink:href="#icon_arrow_right"></use>
                                </svg>
                            </li>
                        @else
                            <li class="active">{!! $crumb['label'] !!}
                                <svg width="4" height="6" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                         xlink:href="#icon_arrow_right"></use>
                                </svg>
                                공지사항
                            </li>
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
                    <div id="box-detail-page">
                        <div class="pt-4" style="position: relative;">
                            <div id="background" class="clearfix"></div>
                            <div class="editor">
                                {!!$notices->notices !!}
                            </div>
                        </div>

                        @if($notices->allow_comment == 1 )
                        <!-- comments -->
                        {!! Theme::partial('comments',[
                            'comments'=>$comments,
                            'countCmt'=>$notices->comments->count(),
                            'nameDetail'=>'notice_id',
                            'createBy'=>$notices->member_id,
                            'idDetail'=>$notices->id,
                            'route'=>'noticesFE.comments',
                            'routeDelete'=>'noticesFE.comments.delete',
                            'showEdit'=> !is_null(auth()->guard('member')->user()) && $notices->member_id == auth()->guard('member')->user()->id ? true :false,
                            'editItem'=> 'gardenFE.edit',
                            'deleteItem'=> 'gardenFE.delete',
                            'type_post'=> '3',
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                            'canCreateComment' => $canCreateComment,
                            'canDeleteComment' => $canDeleteComment,
                            'canViewComment' => $canViewComment,
                            'route_like' => route('noticesFE.likeComments'),
                            'route_dislike' => route('noticesFE.dislikeComments'),
                            'route_sympathy_permission_on_comment' => route('noticesFE.checkSympathyPermissionOnComment'),
                            'top_comments' => $top_comments,
                        ]) !!}
                        <!-- end of comments -->
                        @endif

                        {!! Theme::partial('garden.index_sub',[
                          'garden' => $subList['garden'],
                          'selectCategories' => $subList['selectCategories'],
                          'canCreate' => $subList['canCreate']
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    $(function () {
        const boxId = $('#box-detail-page');
        const pageDetailHeight = boxId.height();
        const pageBgrCount = Math.ceil(pageDetailHeight / 125);
        let html = '';
        for (let i = 0; i < pageBgrCount; i++) {
            html += '<div class="bg-text">{{ $idLogin }}</div>';
        }
        $('#background').append(html);
    })
</script>
<script type="text/javascript" src="{{ asset('js/loading.js') }}"></script>
