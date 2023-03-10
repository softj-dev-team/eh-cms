
<style>
    .table td, .table th {
        padding: .75rem;
        vertical-align: none;
        border-top: none;
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
                {!! Theme::partial('life.menu',['active'=>"flare_market_list"]) !!}
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
                        <h3 class="single__title title-main">{{$flare->title}}</h3>
                        <div class="single__info">
                          {{'작성자 : '.getNickName($flare->member_id)}}
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime( $flare->published) ) }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $flare->lookup}}
                            </div>
                            <div class="single__eye">

                                {!! Theme::partial('report' , [
                                    'type_report'=> '1',
                                    'type_post'=> '5',
                                    'id_post'=> $flare->id,
                                    'object' => $flare
                                ]) !!}

                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters justify-content-between">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                            <div class="single__limit" style="display: flex;align-items: center;justify-content: flex-end;"><label>{{__('life.flea_market.classification')}}:</label> {!! Theme::partial('classification',['categories'=>$flare->categories ]) !!}</div>
                        </div>
                    </div>
                    <br>
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
                    <div class="editor row justify-content-md-center ">
                        <div class="col-md-7 slick_banner">
                            @if($flare->images != "")
                                @foreach ($flare->images as $item)
                                    @if ($item !== null)
                                    <div class="block-img " style="width: 530px;height: 340px;">
                                        <div class="img-bg"
                                            style="width: 100%;height: 100%; background-image:url('{{  get_image_url($item, 'featured') }}"
                                            alt="{{$flare->title}}')">
                                            <img src="{{  get_image_url($item, 'featured') }}" alt="{{$flare->title}}">
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <div class="row row-padding">
                        <table class="table">
                            @if(!is_null($flare->product ))
                            <tr>
                                <th style="width: 1px;white-space: nowrap;">거래물품</th>
                                <td>{{$flare->product }}</td>
                            </tr>
                            @endif
                            @if(!is_null($flare->purchase_date ))
                            <tr>
                                <th style="width: 1px;white-space: nowrap;">구입시기</th>
                                <td>{{Carbon\Carbon::parse( $flare->purchase_date)->format('Y-m-d')}}</td>
                            </tr>
                            @endif
                            @if(!is_null($flare->purchasing_price ))
                            <tr>
                                <th style="width: 1px;white-space: nowrap;">{{__('life.flea_market.purchasing_price')}}</th>
                                <td>{{$flare->purchasing_price }}</td>
                            </tr>
                            @endif
                            @if(!is_null($flare->purchase_location ))
                            <tr>
                                <th style="width: 1px;white-space: nowrap;">구입장소</th>
                                <td>{{$flare->purchase_location }}</td>
                            </tr>
                            @endif
                            @if(!is_null($flare->quality ))
                            <tr>
                                <th style="width: 1px;white-space: nowrap;">물품상태</th>
                                <td>{{$flare->quality }}</td>
                            </tr>
                            @endif
                            @if(!is_null($flare->reason_selling ))
                              <tr>
                                <th style="width: 1px;white-space: nowrap;">{{__('life.flea_market.reason_selling')}}</th>
                                <td>{{$flare->reason_selling }}</td>
                              </tr>
                            @endif
                            @if(!is_null($flare->sale_price ))
                            <tr>
                                <th style="width: 1px;white-space: nowrap;">{{__('life.flea_market.sale_price')}}</th>
                                <td>{{$flare->sale_price}}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>{{__('life.flea_market.exchange')}}</th>
                                <td>{!! Theme::partial('life.elements.trade',['exchange'=>$flare->exchange ?? null]) !!}</td>
                            </tr>
                            <tr>
                                <th>{{__('life.flea_market.contact')}}</th>
                                <td>{{$flare->contact}}</td>
                            </tr>
                            <tr>
                                <th >{{__('life.flea_market.detail')}}</th>
                                <td style="word-break: break-word;">{!! $flare->detail !!}</td>
                            </tr>

                            <tr>
                              <th >{{__('life.flea_market.status')}}</th>
                              <td style="word-break: break-word;">@if($flare->status == "publish" ) {{__('life.flea_market.status.on_sale')}} @elseif($flare->status == "pending") {{__('life.flea_market.status.in_transit')}} @else {{__('life.flea_market.status.transaction_completed')}} @endif</td>
                            </tr>
                        </table>
                    </div>
                    {{-- {!! Theme::partial('life.dislike', [
                        'item' => $flare,
                        'route' => route('life.flea-market-details.dislike'),
                        'route_like' => route('life.flea-market-details.like'),
                        'route_sympathy_permission_on_post' => route('flareMarketFE.checkSympathyPermissionOnPost',['id'=>$flare->id]),
                    ]) !!} --}}
                    <div>
                        {!! Theme::partial('attachments',['link'=> $flare->link,'file_upload'=>$flare->file_upload]) !!}
                    </div>

                    <hr>
                    @if($canEdit ?? false || $canDelete ?? false)
                    <div class="post_action d-flex">
                      {!! Theme::partial('life.post_action', [
                        'idDetail'=>$flare->id,
                        'editItem'=> 'flareMarketFE.edit',
                        'deleteItem'=> 'flareMarketFE.delete',
                        'canEdit' => $canEdit,
                        'canDelete' => $canDelete,
                    ]) !!}
                     </div>
                    @endif


                    <div class="like_group mt-5">
                      {!! Theme::partial('life.dislike', [
                        'item' => $flare,
                        'route' => route('life.flea-market-details.dislike'),
                        'route_like' => route('life.flea-market-details.like'),
                        'route_sympathy_permission_on_post' => route('flareMarketFE.checkSympathyPermissionOnPost',['id'=>$flare->id]),
                    ]) !!}
                    </div>


                    <!-- comments -->
                        {!! Theme::partial('comments',[
                            'comments'=>$comments,
                            'countCmt'=>$flare->comments->count(),
                            'nameDetail'=>'flare_id',
                            'createBy'=>$flare->member_id,
                            'idDetail'=>$flare->id,
                            'route'=>'life.flare_market_details_comments.create',
                            'routeDelete'=>'life.flare_market_details_comments.delete',
                            'showEdit'=> !is_null(auth()->guard('member')->user()) && $flare->member_id == auth()->guard('member')->user()->id ? true : false,
                            'editItem'=> 'flareMarketFE.edit',
                            'deleteItem'=> 'flareMarketFE.delete',
                            'type_post'=> '5',
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                            'canCreateComment' => $canCreateComment,
                            'canDeleteComment' => $canDeleteComment,
                            'canViewComment' => $canViewComment,
                            'route_like' => route('flareMarketFE.likeComments'),
                            'route_dislike' => route('flareMarketFE.dislikeComments'),
                            'route_sympathy_permission_on_comment' => route('flareMarketFE.checkSympathyPermissionOnComment'),
                            'top_comments' => $top_comments
                        ]) !!}
                    <!-- end of comments -->

                    {!! Theme::partial('life.flee_market_index_sub',[
                      'flare' => $subList['flare'],
                      'categories' => $subList['categories'],
                      'style' => $subList['style'],
                      'canCreate' => $subList['canCreate']
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</main>
{!! Theme::partial('life.modal-dislike', [
    'item' => $flare,
]) !!}
{!! Theme::partial('modal-report' , [
  'type_report'=> '1',
  'type_post'=> '5',
  'id_post'=> $flare->id,
  'object' => $flare
]) !!}
