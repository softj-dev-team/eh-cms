
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
                {!! Theme::partial('campus.menu',['active'=>"oldGenealogy"]) !!}
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
                        <h3 class="single__title title-main">{{$oldGenealogy->title}}</h3>
                        <div class="single__info">
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime( $oldGenealogy->created_at) ) }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $oldGenealogy->lookup}}
                            </div>
                            <div class="single__eye">
                              {!! Theme::partial('report' , [
                                  'type_report'=> '1',
                                  'type_post'=> '11',
                                  'id_post'=> $oldGenealogy->id,
                                  'object' => $oldGenealogy
                              ]) !!}
                            </div>
                        </div>
                    </div>



                    <hr>
                    <div class="editor row justify-content-md-center ">
                        <div class="col-md-7  slick_banner">
                            @if(!is_null($oldGenealogy->images) && $oldGenealogy->images!= "" )
                                @foreach ($oldGenealogy->images as $item)
                                    @if ( !is_null($item))
                                        <div class="block-img " style="width: 530px;height: 340px;">
                                            <div class="img-bg"
                                                style="width: 100%;height: 100%; background-image:url('{{  get_image_url($item, 'featured') }}"
                                                alt="{{$oldGenealogy->title}}')">
                                                <img src="{{  get_image_url($item, 'featured') }}" alt="{{$oldGenealogy->title}}">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="row row-padding">
                        {!! $oldGenealogy->detail !!}
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $oldGenealogy->link,'file_upload'=>$oldGenealogy->file_upload]) !!}
                    </div>

                    <hr>
                    @if($canEdit ?? false || $canDelete ?? false)
                        <div class="post_action d-flex">
                          {!! Theme::partial('life.post_action', [
                            'idDetail'=>$oldGenealogy->id,
                            'editItem'=> 'oldGenealogyFE.edit',
                            'deleteItem'=> 'oldGenealogyFE.delete',
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                        ]) !!}
                         </div>
                    @endif

                    <!-- comments -->
                        {!! Theme::partial('comments',[
                            'comments'=>$comments,
                            'countCmt'=>$oldGenealogy->comments->count(),
                            'nameDetail'=>'old_genealogy_id',
                            'createBy'=>$oldGenealogy->member_id,
                            'idDetail'=>$oldGenealogy->id,
                            'route'=>'campus.old.genealogy.details.comments.create',
                            'routeDelete'=>'campus.old.genealogy.details.comments.delete',
                            'showEdit'=>  auth()->guard('member')->user()->hasPermission('oldGenealogyFE.edit') && $oldGenealogy->member_id == auth()->guard('member')->user()->id ? true :false,
                            'editItem'=> 'oldGenealogyFE.edit',
                            'deleteItem'=> 'oldGenealogyFE.delete',
                            'canEdit' => $canEdit ,
                            'canDelete' => $canDelete,
                            'canCreateComment' => $canCreateComment,
                            'canDeleteComment' => $canDeleteComment,
                            'canViewComment' => $canViewComment,
                            'route_like' => route('oldGenealogyFE.likeComments'),
                            'route_dislike' => route('oldGenealogyFE.dislikeComments'),
                            'route_sympathy_permission_on_comment' => route('oldGenealogyFE.checkSympathyPermissionOnComment'),
                            'top_comments' => $top_comments
                        ]) !!}
                    <!-- end of comments -->

                    {!! Theme::partial('campus.old_genealogy_index_sub',[
                      'oldGenealogy' => $subList['oldGenealogy'],
                      'canCreate' => $subList['canCreate']
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</main>
{!! Theme::partial('modal-report' , [
    'type_report'=> '1',
    'type_post'=> '11',
    'id_post'=> $oldGenealogy->id,
    'object' => $oldGenealogy
]) !!}
