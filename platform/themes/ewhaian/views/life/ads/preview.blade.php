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
                {!! Theme::partial('life.menu',['active'=>"advertisements"]) !!}
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
                        <h3 class="single__title title-main">{{$ads->title}}</h3>
                        <div class="single__info">
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime(  today()  ) ) }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $ads->lookup ?? 0}}
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters justify-content-between">

                        <div class="col-md-12">
                            <div class="single__limit"
                                style="display: flex;align-items: center;justify-content: flex-end;">
                                <label>Classification:</label> {!!
                                Theme::partial('classification',['categories'=>[$ads->categories],'type'=>3 ]) !!}</div>
                        </div>
                    </div>
                    {!! Theme::partial('member_post',['item'=> auth()->guard('member')->user()->id]) !!}
                    <br>
                    <div class="editor row justify-content-md-center ">
                        <div class="col-md-7 slick_banner">
                            @if ($ads->images !== null)
                            <div class="block-img " style="width: 530px;height: 340px;">
                                <div class="img-bg"
                                    style="width: 100%;height: 100%; background-image:url('{{$ads->images}}')"
                                    alt="{{$ads->title}}')">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <div class="row">
                        <table class="table">
                            <th >활동기간 </th>
                                <td>{!! $ads->duration2 !!}</td>
                            <tr>
                                <th> {{__('life.advertisements.recruitment_no')}}</th>
                                <td>{!! $ads->recruitment !!}</td>
                            </tr>
                            <tr>
                                <th> {{__('life.advertisements.contact')}} </th>
                                <td>{!! $ads->contact !!}</td>
                            </tr>
                            <tr>
                                <th> {{__('life.advertisements.belong')}} </th>
                                <td>{{ $ads->member() ? $ads->member()->nickname : 'Admin'}}</td>
                            </tr>

                            <tr>
                                <th>  {{__('life.advertisements.introduce')}} </th>
                                <td>{!! $ads->details !!}</td>
                            </tr>
                            <tr>
                                <th style="width: 100px;">동아리 특성</th>
                                <td>
                                    <!-- club-->
                                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2 align-items-center">
                                        <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
                                            <div class="custom-control mr-4">
                                                <input type="checkbox" name="club[1]" value="1" class="custom-control-input" id="club1" @if( !empty($ads->club[1] )) checked @endif disabled="disabled" >
                                                <label class="custom-control-label" for="club1">친목</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox" name="club[2]" value="2" class="custom-control-input"  id="club2" @if( !empty($ads->club[2] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="club2">스터디</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox" name="club[3]" value="3" class="custom-control-input" id="club3" @if( !empty($ads->club[3] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="club3">공모전</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mx-3 mr-4">
                                                <input type="checkbox" name="club[5]" value="5" class="custom-control-input" id="day5" @if( !empty($ads->club[5] )) checked @endif disabled="disabled">
                                                <label class="custom-control-label" for="day5">기타</label>
                                            </div>
                                            @if( !empty($ads->club[5] ))
                                            <div class="custom-control custom-checkbox mx-3 mr-4" style="padding-left: 0px">
                                               {{$ads->club[6] ?? ''}}
                                            </div>
                                            @endif

                                        </div>
                                    </div>
                                    <!-- -->
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $ads->link,'file_upload'=>$ads->file_upload]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

