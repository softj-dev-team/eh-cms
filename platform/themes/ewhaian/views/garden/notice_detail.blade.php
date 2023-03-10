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
    z-index: 1;
    background:transparent;
    display:flex;
    top: 150px;
    flex-wrap: wrap;
  }

  .bg-text {
    color:lightgrey;
    font-size:50px;
    transform:rotate(320deg);
    -webkit-transform:rotate(320deg);
    margin-top: 50px;
    opacity: 0.35;
  }

  .sidebar-template__content {
    position: relative;
  }
  .item__action {
    z-index: 3!important;
  }
  .item__time {
    z-index: 3!important;
  }
  .account__icon {
    z-index: 1059!important;
  }
  .form-control--textarea {
    z-index: 1059!important;
  }
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

</style>
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
        <div id="background">
          @for ($i = 0; $i < 11; $i++)
            <p class="bg-text">{{auth()->guard('member')->user()->id_login}}</p>
          @endfor
        </div>
        <div class="event-details single" >
          <div class="single__head">
            <div class="title"> <h3 class="single__title title-main">{{$garden->title}}</h3></div>
            <div class="single__info">
              <div class="single__date">
                <svg width="15" height="17" aria-hidden="true" class="icon">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                </svg>
                {{ date("Y-m-d",strtotime( $garden->created_at) ) }}
              </div>
              <div class="single__eye">
                <svg width="16" height="10" aria-hidden="true" class="icon">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                </svg>
                {{ $garden->lookup}}
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
                                    {{ date('d M | H:i a', strtotime($garden->created_at)) }}
                                    </span>
              </p>
            </div>
          </div>

          {!! Theme::partial('member_post', ['garden'=> $garden]) !!}

          <div class="clearfix" style="padding-bottom: 40px;"></div>
          @if($garden->can_reaction > 0)
            <div style="position: relative;z-index: 1050;">
              {!! Theme::partial('life.dislike', [
                  'item' => $garden,
                  'route' => route('gardenFE.dislike',['id'=>$garden->id]),
                  'route_like' => route('gardenFE.like',['id'=>$garden->id]),
              ]) !!}
            </div>
          @endif
          <div style="position: relative;z-index: 2;">
            <div class="row" @if($garden->right_click > 0 ) id="detail" @endif  style="margin: 0 10px;">
              <table class="table">
                <tr>
                  <td>{!! html_entity_decode($garden->detail) !!}</td>
                </tr>
              </table>
            </div>
            <div class='block-link'>
              {!! Theme::partial('attachments',['link'=> $garden->link,'file_upload'=>$garden->file_upload]) !!}
            </div>
          </div>

          @if($garden->active_empathy > 0 )
          <!-- comments -->
            {!! Theme::partial('notice_comments',[
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
    'item' => $garden,
]) !!}
<style>
  #detail {
    -webkit-user-select:none;
    -khtml-user-select:none;
    -moz-user-select:none;
    -ms-user-select:none;
    user-select:none
  }

</style>
