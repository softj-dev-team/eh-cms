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
    p {
        word-break: break-word;
    }
</style>

<main id="main-flare-detail" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                  <!-- category menu -->
                  <div class="category-menu">
                    <h4 class="category-menu__title">{{__('event.menu__title')}}</h4>
                    <ul class="category-menu__links">

                        @foreach ($category as $item)
                        <li class="category-menu__item">
                            <a href="{{route('event.event_list', ['idCategory'=>$item->id]) }}"
                                title="{{$item->name}}">{{$item->name}}</a>
                        </li>
                        @endforeach
                        <li class="category-menu__item active">
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
                            <h3 class="single__title title-main">{{$event->title}}</h3>
                        </div>
                        <div class="single__info">
                            <div class="single__date">
                                {{-- <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime( $event->created_at) ) }} --}}
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
                                    'type_post'=> '2',
                                    'id_post'=> $event->id,
                                    'object' => $event
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <hr>
                    <div class="row" @if($event->right_click > 0 ) id="detail" @endif style="margin: 0 30px;">
                        <table class="table">
                            <tr>
                                <td>{!! $event->detail !!}</td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $event->link,'file_upload'=>$event->file_upload]) !!}
                    </div>

                    <hr>
                    @if($canEdit ?? false || $canDelete ?? false)
                    <div class="post_action d-flex">
                      {!! Theme::partial('life.post_action', [
                        'idDetail'=>$event->id,
                        'editItem'=> 'eventsFE.cmt.edit',
                        'deleteItem'=> 'eventsFE.cmt.delete',
                        'canEdit' => $canEdit,
                        'canDelete' => $canDelete,
                    ]) !!}
                    </div>
                    @endif
                      <!-- comments -->
                      {!! Theme::partial('comments',[
                            'comments'=>$comments,
                            'countCmt'=>$event->comments->count(),
                            'nameDetail'=>'events_cmt_id',
                            'createBy'=>$event->member_id,
                            'idDetail'=>$event->id,
                            'route'=>'event.cmt.comment.create',
                            'routeDelete'=>'event.cmt.comment.delete' ,
                            'editItem'=> 'eventsFE.cmt.edit',
                            'deleteItem'=> 'eventsFE.cmt.delete',
                            'type_post'=> '2',
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                            'canCreateComment' => $canCreateComment,
                            'canDeleteComment' => $canDeleteComment,
                            'canViewComment' => $canViewComment,
                            'route_like' => route('eventsFE.cmt.like'),
                            'route_dislike' => route('eventsFE.cmt.dislike'),
                            'route_sympathy_permission_on_comment' => route('eventsFE.cmt.checkSympathyPermissionOnEventComment'),
                            'top_comments' => $top_comments
                        ]) !!}

                        {!! Theme::partial('event.comment_index_sub',[
                          'events' => $subList['events'],
                          'categories' => $subList['category'],
                        ]) !!}
                      <!-- end of comments -->
                </div>
            </div>
        </div>
    </div>
</main>
{!! Theme::partial('modal-report' , [
    'type_report'=> '1',
    'type_post'=> '2',
    'id_post'=> $event->id,
    'object' => $event
]) !!}
