
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
                {!! Theme::partial('campus.menu',['active'=>"studyRoom"]) !!}
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
                      <li><a href="{{route('campus.evaluation_comments_major')}}" title="{{__('campus')}}">{{__('campus')}}</a></li>
                      <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                      </li>
                    <li class="active">{!! $crumb['label'] !!}</li>
                    @endif
                    @endforeach
                </ul>

                <div class="event-details single">
                    <div class="single__head">
                        <h3 class="single__title title-main">{{$studyRoom->title}}</h3>
                        <div class="single__info">
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime( $studyRoom->created_at) ) }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $studyRoom->lookup}}
                            </div>
                            <div class="single__eye">
                              {!! Theme::partial('report' , [
                                  'type_report'=> '1',
                                  'type_post'=> '10',
                                  'id_post'=> $studyRoom->id,
                                  'object' => $studyRoom
                              ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters justify-content-between">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                                <div class="single__limit" style="display: flex;align-items: center;justify-content: flex-end;"><label>{{__('campus.study_room.classification')}} : </label> {!! Theme::partial('classification',['categories'=>[$studyRoom->categories],'type'=>5,'link'=>'campus.elements.showCategories' ]) !!}</div>
                        </div>
                    </div>

                    <br>
                    <div class="editor row justify-content-md-center ">
                        <div class="col-md-7  slick_banner">
                            @if(!is_null($studyRoom->images) && $studyRoom->images!= "" )
                                @foreach ($studyRoom->images as $item)
                                    @if ( !is_null($item))
                                        <div class="block-img " style="width: 530px;height: 340px;">
                                            <div class="img-bg"
                                                style="width: 100%;height: 100%; background-image:url('{{  get_image_url($item, 'featured') }}"
                                                alt="{{$studyRoom->title}}')">
                                                <img src="{{  get_image_url($item, 'featured') }}" alt="{{$studyRoom->title}}">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="row row-padding">
                        <table class="table">
                            <tr>
                                <th style="width: 10%">{{__('campus.study_room.contact')}}</th>
                                <td>{{$studyRoom->contact}}</td>
                            </tr>
                            <tr>
                                <th style="word-break: break-word" >{{__('campus.study_room.detail')}}</th>
                                <td>{!! $studyRoom->detail !!}</td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $studyRoom->link,'file_upload'=>$studyRoom->file_upload]) !!}
                    </div>


                    <hr>
                        @if($canEdit ?? false || $canDelete ?? false)
                        <div class="post_action d-flex">
                          {!! Theme::partial('life.post_action', [
                            'idDetail'=>$studyRoom->id,
                            'editItem'=> 'studyRoomFE.edit',
                            'deleteItem'=> 'studyRoomFE.delete',
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                        ]) !!}
                         </div>
                        @endif

                    <!-- comments -->
                        {!! Theme::partial('comments',[
                            'comments'=>$comments,
                            'countCmt'=>$studyRoom->comments->count(),
                            'nameDetail'=>'study_room_id',
                            'createBy'=>$studyRoom->member_id,
                            'idDetail'=>$studyRoom->id,
                            'route'=>'campus.study_room_details_comments.create',
                            'routeDelete'=>'campus.study_room_details_comments.delete',
                            'showEdit'=> !is_null(auth()->guard('member')->user()) && $studyRoom->member_id == auth()->guard('member')->user()->id ? true :false,
                            'editItem'=> 'studyRoomFE.edit',
                            'deleteItem'=> 'studyRoomFE.delete',
                            'canEdit' => $canEdit ,
                            'canDelete' => $canDelete,
                            'canCreateComment' => $canCreateComment,
                            'canDeleteComment' => $canDeleteComment,
                            'canViewComment' => $canViewComment,
                            'route_like' => route('studyRoomFE.likeComments'),
                            'route_dislike' => route('studyRoomFE.dislikeComments'),
                            'route_sympathy_permission_on_comment' => route('studyRoomFE.checkSympathyPermissionOnComment'),
                            'top_comments' => $top_comments
                        ]) !!}
                    <!-- end of comments -->

                    {!! Theme::partial('campus.study_room_index_sub',[
                      'studyRoom' => $subList['studyRoom'],
                      'categories' => $subList['categories'],
                      'style' => $subList['style'],
                      'canCreate' => $subList['canCreate']
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</main>
{!! Theme::partial('modal-report' , [
    'type_report'=> '1',
    'type_post'=> '10',
    'id_post'=> $studyRoom->id,
    'object' => $studyRoom
]) !!}
