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

                  {!! Theme::partial('life.menu',['active'=>"open_space"]) !!}

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
                            <h3 class="single__title title-main">{{ $openSpace->title}}</h3>
                        </div>
                        <div class="single__info">
                          {{'작성자 : '.getNickName($openSpace->member_id)}}
                            <div class="single__date">
                                 <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime( $openSpace->published) ) }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $openSpace->views}}
                            </div>
                            <div class="single__eye">

                                {!! Theme::partial('report' , [
                                    'type_report'=> '1',
                                    'type_post'=> '4',
                                    'id_post'=> $openSpace->id,
                                    'object' => $openSpace
                                ]) !!}

                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters justify-content-between">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                            <div class="single__limit" style="display: flex;align-items: center;justify-content: flex-end;">
                                <label style="margin-right: 5px">분류</label>
                                @switch($openSpace->categories_id)
                                            @case(0)
                                                 <span class="alert bg-purple" title="공동구매">공동구매</span>
                                                @break
                                            @case(1)
                                                 <span class="alert  bg-green" title="분실">분실</span>
                                                 @break
                                             @case(2)
                                                 <span class="alert  bg-yellow-1" title="기타">기타</span>
                                                 @break

                                            @default
                                                 <span class="alert bg-white" title="All">All</span>
                                @endswitch
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
                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <hr>
                    <div class="editor row justify-content-md-center @if( $openSpace->right_click > 0 ) right_click @endif">
                        <div class="col-md-7 slick_banner">
                            @if($openSpace->images != "")
                                @foreach ($openSpace->images as $item)
                                    @if ($item !== null)
                                    <div class="block-img " style="width: 530px;height: 340px;">
                                        <div class="img-bg"
                                            style="width: 100%;height: 100%; background-image:url('{{  get_image_url($item, 'featured') }}"
                                            alt="{{$openSpace->title}}')">
                                            <img src="{{  get_image_url($item, 'featured') }}" alt="{{$openSpace->title}}">
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <div class="row-padding row  @if( $openSpace->right_click > 0 ) right_click @endif">
                        <table class="table">
                            <tr>
                                <td>{!! html_entity_decode($openSpace->detail) !!}</td>
                            </tr>
                        </table>
                    </div>
                    {{-- {!! Theme::partial('life.dislike', [
                        'item' => $openSpace,
                        'route' => route('life.open_space_details.dislike'),
                        'route_like' => route('life.open_space_details.like'),
                        'route_sympathy_permission_on_post' => route('openSpaceFE.checkSympathyPermissionOnPost',['id'=>$openSpace->id]),
                    ]) !!} --}}
                    <div>
                        {!! Theme::partial('attachments',['link'=> $openSpace->link,'file_upload'=>$openSpace->file_upload]) !!}
                    </div>

                    <hr>
                        @if($canEdit ?? false || $canDelete ?? false)
                        <div class="post_action d-flex">
                          {!! Theme::partial('life.post_action', [
                            'idDetail'=>$openSpace->id,
                            'editItem'=> 'openSpaceFE.edit',
                            'deleteItem'=> 'openSpaceFE.delete',
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                        ]) !!}
                         </div>
                        @endif


                        <div class="like_group mt-5">
                          {!! Theme::partial('life.dislike', [
                              'item' => $openSpace,
                              'route' => route('life.open_space_details.dislike'),
                              'route_like' => route('life.open_space_details.like'),
                              'route_sympathy_permission_on_post' => route('openSpaceFE.checkSympathyPermissionOnPost',['id'=>$openSpace->id]),
                          ]) !!}
                        </div>


                    @if($openSpace->active_empathy > 0 )
                    <!-- comments -->
                      {!! Theme::partial('comments',[
                          'comments'=>$comments,
                          'countCmt'=>$openSpace->comments->count(),
                          'nameDetail'=>'open_space_id',
                          'createBy'=>$openSpace->member_id,
                          'idDetail'=>$openSpace->id,
                          'route'=>'life.open_space_comments.create',
                          'routeDelete'=>'life.open_space_comments.delete',
                          'showEdit'=> !is_null(auth()->guard('member')->user()) && $openSpace->member_id == auth()->guard('member')->user()->id ? true :false,
                          'editItem'=> 'openSpaceFE.edit',
                          'deleteItem'=> 'openSpaceFE.delete',
                          'type_post'=> '4',
                          'canEdit' => $canEdit,
                          'canDelete' => $canDelete,
                          'canCreateComment' => $canCreateComment,
                          'canDeleteComment' => $canDeleteComment,
                          'canViewComment' => $canViewComment,
                          'route_like'=> route('openSpaceFE.likeComments'),
                          'route_dislike'=> route('openSpaceFE.dislikeComments'),
                          'route_sympathy_permission_on_comment' => route('openSpaceFE.checkSympathyPermissionOnComment'),
                          'top_comments' => $top_comments
                        ]) !!}
                    <!-- end of comments -->
                    @endif

                    {!! Theme::partial('life.open_space_index_sub',[
                      'openSpace' => $subList['openSpace'],
                      'style' => $subList['style'],
                      'notices' => $subList['notices'],
                      'description' => $subList['description'],
                      'canCreate' => $subList['canCreate']
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</main>
{!! Theme::partial('life.modal-dislike', [
    'item' => $openSpace,
]) !!}
{!! Theme::partial('modal-report' , [
  'type_report'=> '1',
  'type_post'=> '4',
  'id_post'=> $openSpace->id,
  'object' => $openSpace
]) !!}
<script>
    $(document).ready(function(){
        $('.right_click').on('contextmenu',function(e){
            return false;
        })
    });
    function setImgWidth(){

    }
</script>
