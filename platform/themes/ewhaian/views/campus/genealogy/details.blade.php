
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
@php
@endphp
<main id="main-flare-detail" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->
                {!! Theme::partial('campus.menu',['active'=>"genealogy"]) !!}
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
                        <h3 class="single__title title-main">{{titleGenealogy($genealogy,'')}}</h3>
                        <div class="single__info">
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                              {{Carbon\Carbon::parse( $genealogy->publish)->format('Y-m-d')}}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $genealogy->lookup ?? 0}}
                            </div>
                            <div class="single__eye">
                              {!! Theme::partial('report' , [
                                  'type_report'=> '1',
                                  'type_post'=> '12',
                                  'id_post'=> $genealogy->id,
                                  'object' => $genealogy
                              ]) !!}
                            </div>
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

                    <hr>
                    <div class="editor row justify-content-md-center ">
                        <div class="col-md-7  slick_banner">
                            @if(is_array($genealogy->images) && count($genealogy->images))
                                @foreach ($genealogy->images as $item)
                                    @if ( !is_null($item))
                                        <div class="block-img " style="width: 530px;height: 340px;">
                                            <div class="img-bg"
                                                style="width: 100%;height: 100%; background-image:url('{{  get_image_url($item, 'featured') }}"
                                                alt="{{$genealogy->title}}')">
                                                <img src="{{  get_image_url($item, 'featured') }}" alt="{{$genealogy->title}}">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="clearfix" style="padding-bottom: 50px;"></div>
                    <div class="user-sel row-padding">
                        <div class="row" style="display: flex;align-items: center;">
                            <div class="col-md-12">
                                <label style="font-weight: bold;">{{__('campus.genealogy.categories')}} :</label>
                            </div>
                            @foreach ($genealogy->major()->get() as $item)
                            <div class="flare_market_list">
                                <span class="alert bg-green" style="display: block;margin-left: 10px;">{{$item->name}}</span>
                             </div>
                            @endforeach
                        </div>

                        <div class="clearfix" style="padding-bottom: 20px;"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label style="font-weight: bold;">{{__('campus.genealogy.detail')}} :</label>
                            </div>
                            <div class="col-md-12">
                                {!! $genealogy->detail !!}
                            </div>

                        </div>
                        <div class="clearfix" style="padding-bottom: 20px;"></div>
                        </table>
                    </div>

                    {{-- {!! Theme::partial('life.dislike', [
                      'item' => $genealogy,
                      'route' => route('genealogyFE.dislike',['id'=>$genealogy->id]),
                      'route_like' => route('genealogyFE.like',['id'=>$genealogy->id]),
                      'route_sympathy_permission_on_post' => route('genealogyFE.checkSympathyPermissionOnPost',['id'=>$genealogy->id]),
                  ]) !!} --}}

                    <div>
                        {!! Theme::partial('attachments',['link'=> $genealogy->link,'file_upload'=>$genealogy->file_upload]) !!}
                    </div>

                    <hr>
                        @if($canEdit ?? false || $canDelete ?? false)
                        <div class="post_action d-flex">
                          {!! Theme::partial('life.post_action', [
                            'idDetail'=>$genealogy->id,
                            'editItem'=> 'genealogyFE.edit',
                            'deleteItem'=> 'genealogyFE.delete',
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                        ]) !!}
                         </div>
                        @endif


                        <div class="like_group mt-5">
                              {!! Theme::partial('life.dislike', [
                                'item' => $genealogy,
                                'route' => route('genealogyFE.dislike',['id'=>$genealogy->id]),
                                'route_like' => route('genealogyFE.like',['id'=>$genealogy->id]),
                                'route_sympathy_permission_on_post' => route('genealogyFE.checkSympathyPermissionOnPost',['id'=>$genealogy->id]),
                            ]) !!}
                        </div>

                    <!-- comments -->
                        {!! Theme::partial('comments',[
                            'comments'=>$comments,
                            'countCmt'=>$genealogy->comments->count(),
                            'nameDetail'=>'genealogy_id',
                            'createBy'=>$genealogy->member_id,
                            'idDetail'=>$genealogy->id,
                            'route'=>'campus.genealogy_details_comments.create',
                            'routeDelete'=>'campus.genealogy_details_comments.delete',
                            'showEdit'=> !is_null(auth()->guard('member')->user()) && $genealogy->member_id == auth()->guard('member')->user()->id ? true :false,
                            'editItem'=> 'genealogyFE.edit',
                            'deleteItem'=> 'genealogyFE.delete',
                            'canEdit' => $canEdit ,
                            'canDelete' => $canDelete,
                            'canCreateComment' => $canCreateComment,
                            'canDeleteComment' => $canDeleteComment,
                            'canViewComment' => $canViewComment,
                            'route_like' => route('genealogyFE.likeComments'),
                            'route_dislike' => route('genealogyFE.dislikeComments'),
                            'route_sympathy_permission_on_comment' => route('genealogyFE.checkSympathyPermissionOnComment'),
                            'top_comments' => $top_comments
                        ]) !!}
                    <!-- end of comments -->

                    {!! Theme::partial('campus.genealogy_index_sub',[
                      'genealogy' => $subList['genealogy'],
                      'canCreate' => $subList['canCreate']
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</main>
{!! Theme::partial('life.modal-dislike', [
    'item' => $genealogy,
]) !!}
{!! Theme::partial('modal-report' , [
  'type_report'=> '1',
  'type_post'=> '12',
  'id_post'=> $genealogy->id,
  'object' => $genealogy
]) !!}
