<style>
  .table td,
  .table th {
    padding: .75rem;
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
          <div class="row no-gutters justify-content-between">
            <div class="col-md-6">
              <p class="single__datetime">
                <span class="single__icon">
                    <svg width="10" height="10" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_datetime">
                        </use>
                    </svg>
                </span>
                <span class="single__datetime__detail">
                  {{date('d M | H:i a', strtotime($egarden->created_at))}}
                </span>
              </p>
            </div>
          </div>

          <div class="clearfix" style="padding-bottom: 40px;"></div>

          <div class="editor" @if($egarden->right_click > 0 ) id="detail" @endif style="margin: 0 10px;">
            @if(!is_null($egarden->banner))
              <img src="{{  get_image_url($egarden->banner, 'featured') }}" alt="{{$egarden->title}}">
            @endif()
            {!! Theme::partial('life.dislike', [
                'item' => $egarden,
                'route' => route('egardenFE.dislike',['id'=>$egarden->id]),
                'route_like' => route('egardenFE.like',['id'=>$egarden->id]),
            ]) !!}
            {!!  $egarden->detail !!}
          </div>
          <div>
            {!! Theme::partial('attachments',['link'=> $egarden->link,'file_upload'=>$egarden->file_upload]) !!}
          </div>
        @if($egarden->active_empathy > 0 )
          <!-- comments -->
          {!! Theme::partial('notice_comments',[
              'comments'=>$comments,
              'countCmt'=>$egarden->comments->count(),
              'nameDetail'=>'egardens_id',
              'createBy'=>$egarden->member_id,
              'idDetail'=>$egarden->id,
              'route'=>'egardenFE.comments',
              'routeDelete'=>'egardenFE.comments.delete',
              'showEdit'=> !is_null(auth()->guard('member')->user()) && $egarden->member_id == auth()->guard('member')->user()->id ? true :false,
              'editItem'=> 'egardenFE.edit',
              'deleteItem'=> 'egardenFE.delete',
              'idRoom' =>$egarden->room_id,
              'canEdit' => $canEdit,
              'canDelete' => $canDelete,
              'canCreateComment' => $canCreateComment,
              'canDeleteComment' => $canDeleteComment,
              'canViewComment' => $canViewComment,
              'route_like' => route('egardenFE.likeComments'),
              'route_dislike' => route('egardenFE.dislikeComments'),
              'top_comments' => $top_comments
          ]) !!}
          <!-- end of comments -->
          @endif

        </div>
      </div>
    </div>
  </div>
</main>
{!! Theme::partial('life.modal-dislike', [
    'item' => $egarden,
]) !!}
<style>
  #detail {
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none
  }
</style>
