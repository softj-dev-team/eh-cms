<style>
    /* span.icon-label.disable svg {
        transform: scale(0.8);
        top: -3px;
        position: relative;
    } */

    .single .comments .item__time span {
        font-size: 1.2em;
        color: #EC1469;
        width: 20px;
        height: 20px;
        margin-right: 0.71429em;
    }
    p {
        word-break: break-word;
    }
</style>
<main id="main-content" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->
                <div class="category-menu">
                    <h4 class="category-menu__title">{{__('event.menu__title')}}</h4>
                    <ul class="category-menu__links">
                        @foreach ($category as $item)
                        <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
                            <a href="{{route('event.event_list', ['idCategory'=>$item->id]) }}"
                                title="{{$item->name}}">{{$item->name}}</a>
                        </li>
                        @endforeach
                        <li class="category-menu__item">
                            <a href="{{route('event.cmt.list')}}" title="{{__('event.event_comments')}}">{{__('event.event_comments')}}</a>
                        </li>

                    </ul>
                </div>
                <!-- end of category menu -->
            </div>
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    @foreach (Theme::breadcrumb()->getCrumbs() as $i => $crumb)
                    @if ($i != (count(Theme::breadcrumb()->getCrumbs()) - 1))
                    <li>
                        <a href="{{ $crumb['url'] }}" title="{!! $crumb['label'] !!}">{!! $crumb['label'] !!}</a>
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
                        <h3 class="single__title title-main">{{$event->title}}</h3>
                        <div class="single__info">
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ $event->published }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $event->views}}
                            </div>
                            <div class="single__eye">

                                    {!! Theme::partial('report' , [
                                        'type_report'=> '1',
                                        'type_post'=> '1',
                                        'id_post'=> $event->id,
                                        'object' => $event
                                    ]) !!}

                            </div>
                        </div>
                      <div class="single__info">
                        <div class="single__eye">{{__('event.event_date')}}: {{getStatusDateByDate2($event->start)}} &nbsp;-&nbsp;{{getStatusDateByDate2($event->end)}} </div>

                      </div>
                      <div class="single__info">
                        <div class="single__eye">{{__('event.enrollment_limit_create')}} : {{$event->enrollment_limit ?? 0}}</div>
                      </div>

                    </div>


                    @if (session('msg'))
                        <div class="alert alert-success" role="alert">
                            {{ session('msg') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                        </div>
                    @endif
                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <hr>
                    <div class="editor">
                        <img src="{{  get_image_url($event->banner, 'featured') }}" alt="{{$event->title}}">
                        {!! $event->content !!}
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $event->link,'file_upload'=>$event->file_upload]) !!}
                    </div>

                    <hr>
                    @if($canEdit ?? false || $canDelete ?? false)
                    <div class="post_action d-flex">
                      {!! Theme::partial('life.post_action', [
                        'idDetail'=>$event->id,
                        'editItem'=> 'eventsFE.edit',
                        'deleteItem'=> 'eventsFE.delete',
                        'canEdit' => $canEdit,
                        'canDelete' => $canDelete,
                    ]) !!}
                    </div>
                    @endif
                  <div class="like_group mt-5">
                    {!! Theme::partial('life.dislike', [
                      'item' => $event,
                      'route' => route('eventsFE.dislikePost',['id'=>$event->id]),
                      'route_like' => route('eventsFE.likePost',['id'=>$event->id]),
                      'route_sympathy_permission_on_post' => route('eventsFE.checkSympathyPermissionOnPost',['id'=>$event->id]),
                    ]) !!}
                  </div>
                    <!-- comments -->
                        {!! Theme::partial('comments',[
                            'comments'=>$comments,
                            'countCmt'=>$event->comments->count(),
                            'nameDetail'=>'event_id',
                            'idDetail'=>$event->id,
                            'createBy'=>$event->member_id,
                            'route'=>'event.comment.create',
                            'routeDelete'=>'event.comment.delete',
                            'editItem'=> 'eventsFE.edit',
                            'deleteItem'=> 'eventsFE.delete',
                            'type_post'=> '1',
                            'canEdit' => $canEdit ,
                            'canDelete' => $canDelete,
                            'canCreateComment' => $canCreateComment,
                            'canDeleteComment' => $canDeleteComment,
                            'canViewComment' => $canViewComment,
                            'route_like' => route('eventsFE.like'),
                            'route_dislike' => route('eventsFE.dislike'),
                            'route_sympathy_permission_on_comment' => route('eventsFE.checkSympathyPermissionOnComment'),
                            'top_comments' => $top_comments
                        ]) !!}
                    <!-- end of comments -->

                  {!! Theme::partial('event.index_sub',[
                    'events' => $subList['events'],
                    'categories' => $subList['category'],
                    'idCategory' => $subList['idCategory'],
                    'selectCategories' => $subList['selectCategories'],
                    'style' => $subList['style'],
                    'canCreate' => $subList['canCreate']
                  ]) !!}
                </div>
            </div>
        </div>
    </div>
</main>
{!! Theme::partial('life.modal-dislike', [
      'item' => $event,
]) !!}
{!! Theme::partial('modal-report' , [
    'type_report'=> '1',
    'type_post'=> '1',
    'id_post'=> $event->id,
    'object' => $event
]) !!}
