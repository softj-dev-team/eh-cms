
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

.custom-control-input:disabled~.custom-control-label {
    color: #000000;
}
.custom-checkbox .custom-control-input:disabled:checked~.custom-control-label::before {
    background-color: rgba(255, 255, 255, 0.5);
}
.form-control:disabled, .form-control[readonly] {
    background-color: transparent;
}


</style>
<main id="main-flare-detail" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->
                {!! Theme::partial('life.menu',['active'=>"shelter"]) !!}
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
                        <h3 class="single__title title-main">{{$shelter->title}}</h3>
                        <div class="single__info">
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime( $shelter->published) ) }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $shelter->lookup ?? 0}}
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters justify-content-between">

                        <div class="col-md-12">
                                {{-- <div class="single__limit" style="display: flex;align-items: center;justify-content: flex-end;"><label>Classification:</label> {!! Theme::partial('classification',['categories'=>$shelter->categories,'type'=>4 ]) !!}</div> --}}
                        </div>
                    </div>
                    {!! Theme::partial('member_post',['item'=> auth()->guard('member')->user()->id]) !!}
                    <br>
                    <div class="editor row justify-content-md-center @if( $shelter->right_click > 0 ) right_click @endif">
                        <div class="col-md-7 slick_banner">

                            @if(!is_null($shelter->images) && $shelter->images!= "" )
                                @foreach ($shelter->images as $item)
                                    @if ( !is_null($item))
                                        <div class="block-img " style="width: 530px;height: 340px;">
                                            <div class="img-bg"
                                                style="width: 100%;height: 100%; background-image:url({{$item}})"
                                                alt="{{$shelter->title}}')">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <div class="row @if( $shelter->right_click > 0 ) right_click @endif">
                        <table class="table">
                            @if(!empty($shelter->detail))
                            <tr>
                                <th style="width: 1px;white-space: nowrap;" >{{__('life.shelter_info.detail')}}</th>
                                <td>{!! $shelter->detail !!}</td>
                            </tr>
                            @endif
                            <tr>
                                <th style="width: 1px;white-space: nowrap;" >위치</th>
                                <td>{!! $shelter->location !!}</td>
                            </tr>
                            <tr>
                                <th style="width: 1px;white-space: nowrap;" >크기</th>
                                <td>{!! $shelter->size !!}</td>
                            </tr>
                            <tr>
                                <th style="width: 1px;white-space: nowrap;" >관리비</th>
                                <td>

                                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2 align-items-center">
                                        <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
                                            @if( empty($shelter->utility[7] ))
                                            <div class="custom-control mr-4" style="padding-left: 0px;">
                                                {{$shelter->utility[8] ?? 'empty' }}
                                            </div>
                                            @endif
                                            <div class="custom-control mr-4">
                                                <input type="checkbox"  value="1" class="custom-control-input" id="internet" @if( !empty($shelter->utility[1] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="internet">인터넷</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox"  value="2" class="custom-control-input"  id="tv" @if( !empty($shelter->utility[2] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="tv">유선TV</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox"value="3" class="custom-control-input" id="cleaning_fee" @if( !empty($shelter->utility[3] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="cleaning_fee">청소비</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox" value="4" class="custom-control-input" id="watere_bill" @if( !empty($shelter->utility[4] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="watere_bill">수도세</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox"value="5" class="custom-control-input" id="gas_bill" @if( !empty($shelter->utility[5] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="gas_bill">도시가스</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox" value="6" class="custom-control-input" id="electricity_bill"  @if(   !empty($shelter->utility[6] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="electricity_bill">전기세</label>
                                            </div>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 1px;white-space: nowrap;" >계약기간</th>
                                <td>{!! $shelter->lease_period !!}</td>
                            </tr>
                            <tr>
                                <th style="width: 1px;white-space: nowrap;" >건물형태</th>
                                <td>{!! $shelter->building_type !!}</td>
                            </tr>
                            <tr>
                                <th style="width: 1px;white-space: nowrap;" >입주가능일</th>
                                <td>{!! $shelter->possible_moving_date !!}</td>
                            </tr>
                            <tr>
                                <th style="width: 1px;white-space: nowrap;" >난방종류</th>
                                <td>{!! $shelter->heating_type !!}</td>
                            </tr>
                            <tr>
                                <th style="width: 1px;white-space: nowrap;" >옵션항목</th>
                                <td>
                                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2 align-items-center">
                                        <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
                                            <div class="custom-control mr-4">
                                                <input type="checkbox" class="custom-control-input" id="desk" @if( !empty($shelter->option[1] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="desk">책상</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox"  class="custom-control-input"  id="bed" @if( !empty($shelter->option[2] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="bed">침대</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox"class="custom-control-input" id="refrigerator" @if( !empty($shelter->option[3] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="refrigerator">냉장고</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox"  class="custom-control-input" id="laundry_machine" @if( !empty($shelter->option[4] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="laundry_machine">에어컨</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox" class="custom-control-input" id="ac" @if( !empty($shelter->option[5] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="ac">에어컨</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox" class="custom-control-input" id="closet" @if( !empty($shelter->option[6] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="closet">옷장</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox" class="custom-control-input" id="other" @if( !empty($shelter->option[7] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="other">기타</label>
                                            </div>
                                            @if( !empty($shelter->option[7] ))
                                            <div class="custom-control custom-checkbox mx-3 mr-4" style="padding-left: 0px">
                                               {{$shelter->option[8] ?? ''}}
                                            </div>
                                            @endif


                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @if($shelter->getNameCategories($shelter->categories)->name != 'Dragon')
                            <tr>
                                <th style="width: 1px;white-space: nowrap;">{{__('life.shelter_info.contact')}}</th>
                                <td>{{$shelter->contact}} </td>
                            </tr>
                            @endif
                            <tr>
                                <th style="width: 1px;white-space: nowrap;">중개업소</th>
                                <td>{{$shelter->real_estate}} </td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $shelter->link,'file_upload'=>$shelter->file_upload]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    $('.right_click').on('contextmenu',function(e){
        return false;
    })
</script>
