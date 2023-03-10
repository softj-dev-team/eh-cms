
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
                {!! Theme::partial('life.menu',['active'=>"part_time_jobs_list"]) !!}
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
                        <h3 class="single__title title-main">{{$jobs->title}}</h3>
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
                                {{ $jobs->lookup ?? 0}}
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters justify-content-between">

                        <div class="col-md-12">
                                <div class="single__limit" style="display: flex;align-items: center;justify-content: flex-end;">
                                    <label>Classification:</label>
                                    {!! Theme::partial('classification',['categories'=>$jobs->categories, 'type'=> 2]) !!}
                                </div>
                        </div>
                    </div>
                    {!! Theme::partial('member_post',['item'=> auth()->guard('member')->user()->id]) !!}
                    <br>
                    <div class="editor row justify-content-md-center ">
                        <div class="col-md-7 slick_banner">
                                @if ($jobs->images !== null)
                                <div class="block-img " style="width: 530px;height: 340px;">
                                    <div class="img-bg"
                                        style="width: 100%;height: 100%; background-image:url('{{  $jobs->images }}"
                                        alt="{{$jobs->title}}')">
                                    </div>
                                </div>
                                @endif
                        </div>
                    </div>
                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <div class="row">
                        <table class="table">
                            @if(!is_null($jobs->location))
                            <tr >
                                <th style="width: 1px;white-space: nowrap;">근무지역</th>
                                <td>{{$jobs->contact}}</td>
                            </tr>
                            @endif
                            @if(!is_null($jobs->pay))
                            <tr >
                                <th style="width: 1px;white-space: nowrap;">급여</th>
                                <td>
                                   @switch($jobs->pay['option'] )
                                       @case(0)
                                            시급
                                           @break
                                       @case(1)
                                            일급
                                           @break
                                        @case(2)
                                            주급
                                           @break
                                        @case(3)
                                            월급
                                           @break
                                        @case(4)
                                            기타
                                           @break
                                       @default
                                            시급
                                   @endswitch
                                    / {{ $jobs->pay['price']}}
                                </td>
                            </tr>
                            @endif
                            @if(!is_null($jobs->period))
                            <tr >
                                <th style="width: 1px;white-space: nowrap;">근무기간</th>
                                <td>{{$jobs->period}}</td>
                            </tr>
                            @endif
                            @if(!is_null($jobs->working_period))
                            <tr >
                                <th style="width: 1px;white-space: nowrap;">근무기간</th>
                                <td>{{$jobs->working_period}}</td>
                            </tr>
                            @endif
                            @if(!is_null($jobs->applying_period))
                            <tr >
                                <th style="width: 1px;white-space: nowrap;">모집기간</th>
                                <td>{{$jobs->applying_period}}</td>
                            </tr>
                            @endif
                            <tr >
                                <th style="width: 1px;white-space: nowrap;">근무요일</th>
                                <td>
                                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group align-items-center">
                                     <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
                                         <div class="custom-control mr-4">
                                             <input type="checkbox" class="custom-control-input" id="day1" @if( !empty($jobs->day[1] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day1">월</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                             <input type="checkbox" class="custom-control-input"  id="day2" @if( !empty($jobs->day[2] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day2">화</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                             <input type="checkbox"  class="custom-control-input" id="day3" @if( !empty($jobs->day[3] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day3">수</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                             <input type="checkbox" class="custom-control-input" id="day4" @if( !empty($jobs->day[4] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day4">목</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                             <input type="checkbox"  class="custom-control-input" id="day5" @if( !empty($jobs->day[5] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day5">금</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                             <input type="checkbox" class="custom-control-input" id="day6" @if( !empty($jobs->day[6] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day6">토</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                             <input type="checkbox" class="custom-control-input" id="day7" @if( !empty($jobs->day[7] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day7">일</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                             <input type="checkbox"  class="custom-control-input" id="day8" @if( !empty($jobs->day[8] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day8">무관</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                            <input type="checkbox" class="custom-control-input" id="day9" @if( !empty($jobs->day[9] )) checked @endif disabled="disabled">
                                            <label class="custom-control-label" for="day9">협의 가능</label>
                                        </div>

                                     </div>
                                 </div>
                                </td>
                            </tr>
                            @if(!is_null($jobs->time))
                            <tr >
                                <th style="width: 1px;white-space: nowrap;">근무시간</th>
                                <td>{{$jobs->time}}</td>
                            </tr>
                            @endif
                            @if(!is_null($jobs->contact))
                            <tr >
                                <th style="width: 1px;white-space: nowrap;">{{__('life.part-time_job.contact')}}</th>
                                <td>{{$jobs->contact}}</td>
                            </tr>
                            @endif
                            @if(!is_null($jobs->resume))
                            <tr >
                                <th style="width: 1px;white-space: nowrap;">경력사항</th>
                                <td>{{$jobs->resume}}</td>
                            </tr>
                            @endif
                            @if(!is_null($jobs->resume))
                            <tr>
                                <th >기타사항</th>
                                <td>{!! $jobs->detail !!}</td>
                            </tr>
                            @endif




                        </table>
                    </div>
                    <div>
                        {!! Theme::partial('attachments',['link'=> $jobs->link,'file_upload'=>$jobs->file_upload]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
