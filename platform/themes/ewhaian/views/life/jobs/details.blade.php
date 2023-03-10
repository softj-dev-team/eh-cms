
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

.table-content .table td, .table th {
  vertical-align: middle;
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
                          {{'작성자 : '.getNickName($jobs->member_id)}}
                            <div class="single__date">
                                <svg width="15" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                                </svg>
                                {{ date("Y-m-d",strtotime( $jobs->published) ) }}
                            </div>
                            <div class="single__eye">
                                <svg width="16" height="10" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                                </svg>
                                {{ $jobs->lookup}}
                            </div>
                            <div class="single__eye">

                                {!! Theme::partial('report' , [
                                    'type_report'=> '1',
                                    'type_post'=> '6',
                                    'id_post'=> $jobs->id,
                                    'object' => $jobs
                                ]) !!}

                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters justify-content-between">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                                <div class="single__limit" style="display: flex;align-items: center;justify-content: flex-end;">
                                    <label>{{__('life.part-time_job.classification')}} : </label>
                                    {!! Theme::partial('classification',['categories'=>$jobs->categories, 'type'=> 2]) !!}
                                </div>
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
                                @if ($jobs->images !== null)
                                <div class="block-img " style="width: 530px;height: 340px;">
                                    <div class="img-bg"
                                        style="width: 100%;height: 100%; background-image:url('{{  get_image_url($jobs->images, 'featured') }}"
                                        alt="{{$jobs->title}}')">
                                        <img src="{{  get_image_url($jobs->images, 'featured') }}" alt="{{$jobs->title}}">
                                    </div>
                                </div>
                                @endif
                        </div>
                    </div>
                    <div class="clearfix" style="padding-bottom: 40px;"></div>
                    <div class="row row-padding">
                        <table class="table table-content">
                            @if(!is_null($jobs->location))
                            <tr >
                                <th style="width: 1px;white-space: nowrap;">근무지역</th>
                                <td>{{$jobs->location}}</td>
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
                            @if(!is_null($jobs->day))
                            <tr >
                                <th style="width: 1px;white-space: nowrap;">근무요일</th>
                                <td>
                                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2 align-items-center">
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
                                             <input type="checkbox" class="custom-control-input" id="day5" @if( !empty($jobs->day[5] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day5">금</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                             <input type="checkbox" " class="custom-control-input" id="day6" @if( !empty($jobs->day[6] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day6">토</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                             <input type="checkbox"  class="custom-control-input" id="day7" @if( !empty($jobs->day[7] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day7">일</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                             <input type="checkbox" class="custom-control-input" id="day8" @if( !empty($jobs->day[8] )) checked @endif disabled="disabled">
                                             <label class="custom-control-label" for="day8">무관</label>
                                         </div>
                                         <div class="custom-control custom-checkbox mx-3 mr-4">
                                            <input type="checkbox"  class="custom-control-input" id="day9" @if( !empty($jobs->day[9] )) checked @endif disabled="disabled">
                                            <label class="custom-control-label" for="day9">협의 가능</label>
                                        </div>

                                     </div>
                                 </div>
                                </td>
                            </tr>
                            @endif
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
                            @if(!is_null($jobs->detail))
                            <style>
                              .table-content p {
                                margin-bottom: 0px;
                              }
                            </style>
                            <tr>
                                <th style="width: 1px;white-space: nowrap;">기타사항</th>
                                <td>{!!html_entity_decode( $jobs->detail ) !!}</td>
                            </tr>
                            @endif

                        </table>
                    </div>
                    {{-- {!! Theme::partial('life.dislike', [
                        'item' => $jobs,
                        'route' => route('life.part-time-jobs-details.dislike'),
                        'route_like' => route('life.part-time-jobs-details.like'),
                        'route_sympathy_permission_on_post' => route('jobsPartTimeFE.checkSympathyPermissionOnPost',['id'=>$jobs->id])
                    ]) !!} --}}
                    <div>
                        {!! Theme::partial('attachments',['link'=> $jobs->link,'file_upload'=>$jobs->file_upload]) !!}
                    </div>


                    <hr>
                        @if($canEdit ?? false || $canDelete ?? false)
                        <div class="post_action d-flex">
                          {!! Theme::partial('life.post_action', [
                            'idDetail'=>$jobs->id,
                            'editItem'=> 'jobsPartTimeFE.edit',
                            'deleteItem'=> 'jobsPartTimeFE.delete',
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                        ]) !!}
                         </div>
                        @endif


                        <div class="like_group mt-5">
                          {!! Theme::partial('life.dislike', [
                            'item' => $jobs,
                            'route' => route('life.part-time-jobs-details.dislike'),
                            'route_like' => route('life.part-time-jobs-details.like'),
                            'route_sympathy_permission_on_post' => route('jobsPartTimeFE.checkSympathyPermissionOnPost',['id'=>$jobs->id])
                        ]) !!}
                        </div>

                    <!-- comments -->
                        {!! Theme::partial('comments',['comments'=>$comments,
                            'countCmt'=>$jobs->comments->count(),
                            'nameDetail'=>'jobs_part_time_id',
                            'createBy'=>$jobs->member_id,
                            'idDetail'=>$jobs->id,
                            'route'=>'life.part_time_jobs_details_comments.create',
                            'routeDelete'=>'life.part_time_jobs_details_comments.delete',
                            'showEdit'=>  auth()->guard('member')->check() ?  true : false ,
                            'editItem'=> 'jobsPartTimeFE.edit',
                            'deleteItem'=> 'jobsPartTimeFE.delete',
                            'type_post'=> '6',
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                            'canCreateComment' => $canCreateComment,
                            'canDeleteComment' => $canDeleteComment,
                            'canViewComment' => $canViewComment,
                            'route_like' => route('jobsPartTimeFE.likeComments'),
                            'route_dislike' => route('jobsPartTimeFE.dislikeComments'),
                            'route_sympathy_permission_on_comment' => route('jobsPartTimeFE.checkSympathyPermissionOnComment'),
                            'top_comments' => $top_comments
                         ]) !!}
                    <!-- end of comments -->

                    {!! Theme::partial('life.part_time_job_index_sub',[
                        'jobs' => $subList['jobs'],
                        'categories' => $subList['categories'],
                        'style' => $subList['style'],
                        'idFirstParent' => $subList['idFirstParent'],
                        'canCreate' => $subList['canCreate']
                      ]) !!}
                </div>
            </div>
        </div>
    </div>
</main>
{!! Theme::partial('life.modal-dislike', [
    'item' => $jobs,
]) !!}
{!! Theme::partial('modal-report' , [
  'type_report'=> '1',
  'type_post'=> '6',
  'id_post'=> $jobs->id,
  'object' => $jobs
]) !!}
