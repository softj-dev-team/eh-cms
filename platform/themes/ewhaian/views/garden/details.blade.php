@php
  $idLogin = auth()->guard('member')->user()->id_login;
@endphp
@section('styles')

@stop
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
    position:absolute;
    z-index: 1 !important;
    background:transparent !important;
    /* display:flex; */
    top: 0px;
    flex-wrap: wrap;
    width: 100%;
}

.bg-text {
    color: lightgrey;
    font-size:50px;
    /* transform:rotate(320deg);
    -webkit-transform:rotate(320deg); */
    margin-top: -10px;
    margin-bottom: 70px;
    opacity: 0.1 !important;
    text-align: center;
    /* width: 20%; */
}
/* .sidebar-template {
  padding-top: 0.5em;
} */
.sidebar-template__content {
    position: relative;
}
.item__action {
    z-index: 3!important;
    padding-top: 6px;
}
.item__time {
    z-index: 3!important;
}
.account__icon {
    z-index: 99!important;
}

/* .form-control--textarea {
    z-index: 1059!important;
} */
.form-submit {
    z-index: 3!important;
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
.marker {
    background-color: Yellow;
}
#detail {
    -webkit-user-select:none;
    -khtml-user-select:none;
    -moz-user-select:none;
    -ms-user-select:none;
    user-select:none
}
#main-content {
  position: relative;
  z-index: 99;
  /* margin-top: 5em; */
}
.sidebar-template {
  padding: 1.57143em 0 2.85714em 0;
}
.like_group .single__date {
  padding-top: 6px
}
.report_post {
  padding-top: 12px
}
.page-detail {
  position: relative;
  z-index: 99;
}
.like__area {
  position: absolute;
  right: 50px!important;
  padding-top: 6px;
}
.page-link {
  background: transparent
}
.single .comments {
  margin-top: 5.2em !important;
}

@media (max-width: 991px) {
  #main-content {
    padding-top: 0
  }
  .like__area {
    padding-top: 0
  }
  .like_group .report_post {
    padding-top: 0;
  }
  .single__info.like__area {
    margin-top: 0
  }
  .single__info .single__eye {
    margin-bottom: 10px
  }
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
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    @else
                    <li class="active">{!! $crumb['label'] !!}</li>
                    @endif
                    @endforeach
                </ul>

                <div class="event-details single" >
                        <div class="single__head">

                            <div class="title"> <h3 class="single__title title-main">{{$garden->title}}</h3></div>
                            <div class="single__info">
                                <div class="single__date">
                                @if (isset($garden))
                                  {{--      <p class="single__limit">{{ $garden->i rd . $garden->member_id . sprintf('%02d', mt_rand(00,99)) }}</p>--}}
                                  <p class="single__limit">번호 : {{substr((string)$garden->id, -5, 5)}}</p>
                                @elseif (isset($item))
                                  <p class="single__limit">번호 : {{ sprintf('%06d', $item). sprintf('%02d', mt_rand(00,99)) }}</p>
                                @endif
                                </div>

                                <div class="single__date" style="margin-right:10px">
                                    <svg width="15" height="17" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                    </svg>
                                    {{ date("Y-m-d H:i:s",strtotime( $garden->created_at) ) }}
                                </div>
                                <div class="single__eye" style="margin-right:10px">
                                    <svg width="16" height="10" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                    </svg>
                                    {{ $garden->lookup}}
                                </div>


{{--                              <div class="single__eye">--}}
{{--                                {!! Theme::partial('report' , [--}}
{{--                                    'type_report'=> '1',--}}
{{--                                    'type_post'=> '9',--}}
{{--                                    'id_post'=> $garden->id,--}}
{{--                                    'object' => $garden--}}
{{--                                ]) !!}--}}
{{--                              </div>--}}

                              <div class="single__eye" style="margin-right:10px">
{{--                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M15 9h-2V7h2v2m7 11v2h-7a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1H2v-2h7a1 1 0 0 1 1-1h1v-2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-4v2h1a1 1 0 0 1 1 1h7M9 5H7v10h2V5m2 10h2v-4h2a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-4v10Z"/></svg>--}}
                                @if(!is_null($ip_address))
                                  <a target="_parent" onclick="ipPopClick('{{$ip_address}}')" >
                                    @php
                                      $randomIp = mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);
                                    @endphp
                                    <span class="icon-label span_hidden_id" style="cursor: pointer;font-size: 10px;line-height: 2em;width: 100%;height: 2em;border-radius: 5px;display: none;background-color: #f4f4f4;border: 1px solid #f4f4f4;margin-left: 0.71429em;margin-right: 0px;">
                                    {{ '(' . $randomIp . ')' }}
                                    </span>
                                    <span class="icon-label span_show_id" style="cursor: pointer;font-size: 10px;line-height: 2em;width: 5em;height: 2em;border-radius: 5px;background-color: #f4f4f4;border: 1px solid #f4f4f4;margin-left: 0.71429em;margin-right: 0px;">IP</span>
                                  </a>
                                @endif
                              </div>
                              <div class="single__eye">
                                <form action="{{ route('gardenFE.addOrRemoveBookmark', ['id' => $id]) }}" method="post">
                                  <button type="submit" class="btn btn-primary btn-reset-padding">{{ $isBookmarkByMember ? __('garden.remove_bookmark') : __('garden.add_bookmark') }}</button>
                                </form>
                              </div>
                            </div>
                            <input type="hidden" id="gardenID" name="gardenID" value="{{$garden->id}}">
                        </div>

                    @if (session('msg'))
                      <div class="alert alert-success" role="alert">
                        {{ session('msg') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                    @endif
                    @if (session('bookmark_err'))
                    <div class="alert alert-danger d-block" role="alert">
                        {{ session('bookmark_err') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
{{--                        <div class="row no-gutters justify-content-between">--}}
{{--                            <div class="col-md-6">--}}
{{--                                <p class="single__datetime">--}}
{{--                                    <span class="single__icon">--}}
{{--                                        <svg width="10" height="10" aria-hidden="true" class="icon">--}}
{{--                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_datetime">--}}
{{--                                            </use>--}}
{{--                                        </svg>--}}
{{--                                    </span>--}}
{{--                                    <span class="single__datetime__detail">--}}
{{--                                    {{ date('d M | H:i a', strtotime($garden->created_at)) }}--}}
{{--                                    </span>--}}
{{--                                </p>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        {!! Theme::partial('member_post', ['garden'=> $garden]) !!}

                        {{-- @if($garden->can_reaction > 0)
                            <div style="position: relative;z-index: 1050;">
                                {!! Theme::partial('life.dislike', [
                                    'item' => $garden,
                                    'route' => route('gardenFE.dislike',['id'=>$garden->id]),
                                    'route_like' => route('gardenFE.like',['id'=>$garden->id]),
                                    'route_sympathy_permission_on_post' => route('gardenFE.checkSympathyPermissionOnPost',['id'=>$garden->id]),
                                ]) !!}
                            </div>
                        @endif --}}
                        <div id="box-detail-page">
                            <div class="pt-lg-4" style="position: relative;z-index: 2;">
                              <div id="background" class="clearfix">
                                {{-- @php
                                  $idLogin = auth()->guard('member')->user()->id_login;
                                @endphp
                                  @for ($i = 0; $i < 10; $i++)
                                      <div class="bg-text">{{$idLogin}}</div>
                                  @endfor --}}
                              </div>
                                <div class="page-detail"@if($garden->right_click > 0 ) id="detail"@endif>{!! html_entity_decode($garden->detail) !!}</div>
                            </div>


                            <div class='block-link'>
                                {!! Theme::partial('attachments',['link'=> $garden->link,'file_upload'=>$garden->file_upload]) !!}
                            </div>
                            <hr>
                            @if($garden->can_reaction > 0)
                            <div class="like_group h-0">
                                {!! Theme::partial('life.dislike', [
                                    'item' => $garden,
                                    'route' => route('gardenFE.dislike',['id'=>$garden->id]),
                                    'route_like' => route('gardenFE.like',['id'=>$garden->id]),
                                    'route_sympathy_permission_on_post' => route('gardenFE.checkSympathyPermissionOnPost',['id'=>$garden->id]),
                                ]) !!}
                                {!! Theme::partial('report' , [
                                  'type_report'=> '1',
                                  'type_post'=> '9',
                                  'id_post'=> $garden->id,
                                  'object' => $garden
                              ]) !!}
                            </div>
                            @endif
                            @if($canEdit ?? false || $canDelete ?? false)
                            <div class="post_action d-flex mt-5">
                              {!! Theme::partial('life.post_action', [
                                'idDetail'=>$garden->id,
                                'editItem'=> 'gardenFE.edit',
                                'deleteItem'=> 'gardenFE.delete',
                                'canEdit' => $canEdit,
                                'canDelete' => $canDelete,
                            ]) !!}
                            </div>
                            @endif
                        @if($garden->active_empathy > 0 )
                        <!-- comments -->
                            {!! Theme::partial('comments',[
                                'object' => $garden,
                                'comments'=>$comments,
                                'countCmt'=>$garden->comments->count(),
                                'nameDetail'=>'gardens_id',
                                'createBy'=>$garden->member_id,
                                'idDetail'=>$garden->id,
                                'route'=>'gardenFE.comments',
                                'routeDelete'=>'gardenFE.comments.delete',
                                'showEdit'=> true,
                                'editItem'=> 'gardenFE.edit',
                                'deleteItem'=> 'gardenFE.delete',
                                'show_pwd_post' => 0,
                                'hint' => $garden->hint ?? '',
                                'canEdit' => $canEdit,
                                'canDelete' => $canDelete,
                                'canCreateComment' => $canCreateComment,
                                'canDeleteComment' => $canDeleteComment,
                                'canViewComment' => $canViewComment,
                                'route_like' => route('gardenFE.likeComments'),
                                'route_dislike' => route('gardenFE.dislikeComments'),
                                'route_sympathy_permission_on_comment' => route('gardenFE.checkSympathyPermissionOnComment'),
                                'top_comments' => $top_comments,
                                'type_post'=> '9',
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
{!! Theme::partial('life.modal-dislike', [
    'item' => $garden,
]) !!}
@if($garden->can_reaction > 0)
{!! Theme::partial('modal-report' , [
    'type_report'=> '1',
    'type_post'=> '9',
    'id_post'=> $garden->id,
    'object' => $garden
]) !!}
@endif
<script type="text/javascript" src="{{ asset('js/loading.js') }}"></script>
<script>
  $(function() {
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
{{-- <link href="{{ asset('css\ckeditor\contents.css') }}" rel="stylesheet"> --}}
