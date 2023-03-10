<style>
    .input-group-image {
        margin-right: 17px;
    }

    .input-group-image .form-control--upload {
        height: 150px;
        width: 150px;
        max-width: 150px;
    }
    .col-md-6 .single__limit {
        display: none;
    }
    .preview {
        display: none;
    }
</style>

<main id="main-content" data-view="flea-market" data-page="life" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <!-- flare menu -->
            {!! Theme::partial('life.menu',['active'=>"part_time_jobs_list"]) !!}
            <!-- end of flare menu -->
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
                <div class="heading">
                    <div class="heading__title">
                        {{__('life.part-time_job')}}
                    </div>
                    <div class="heading__description">
                        {!!$description->description ?? ""!!}
                    </div>
                </div>
                <form
                    action="{{route('jobsPartTimeFE.edit',['id'=>$jobs->id])}}" id="my_form" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form form--border">
                        <div class="header_title_post">
                            <h3 class="form__title">{{$parent->name }}</h3>
                        </div>
                        <p class="text-right"><span class="required">* </span>{{__('life.part-time_job.required')}}</p>

                        @if ($errors->any())
                        <div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li class="alert alert-danger">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if (session('err'))
                        <div>
                            <ul>
                                <li class="alert alert-danger">{{ session('err') }}</li>
                            </ul>
                        </div>
                        @endif
                        @if (session('success'))
                        <div>
                            <ul>
                                <li class="alert alert-success">{{ session('success') }}</li>
                            </ul>
                        </div>
                        @endif
                        <input type="hidden" name="categories[1]" id="categories1" value="{{$parent->id}}">
                        <div class="form-group form-group--1">
                            <div class="single__limit" style="display: flex;align-items: center;justify-content: flex-start;">
                                <label>{{__('life.part-time_job.classification')}} : </label>
                                {!! Theme::partial('classification',['categories'=>$jobs->categories, 'type'=> 2]) !!}
                            </div>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="item" class="form-control">
                                <input type="text" id="item" placeholder="&nbsp;"
                                    value="{{$jobs->title ?? null}}" required readonly>
                                <span class="form-control__label"> {{__('life.part-time_job.title')}} <span
                                        class="required">*</span></span>
                            </label>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="location" class="form-control">
                                <input type=" text" id="location" placeholder="&nbsp;"
                                    value="{{$jobs->location ?? null}}" required readonly>
                                <span class="form-control__label">근무지역<span class="required">*</span></span>
                            </label>
                        </div>

                        <div class="filter align-items-center"
                            style="padding:unset;background-color: transparent; margin-top: 10px; margin-bottom: 10px">
                            <div class="filter__item filter__title mr-3">급여<span class="required">*</span></div>
                            <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
                                <select class="form-control form-control--select mx-3" value=""
                                    style="width: 10%;background-color: white;" required disabled="true" >
                                    <option value="0"
                                        {{ $jobs && $jobs->pay  && $jobs->pay['option'] && $jobs->pay['option'] == 0 ? " selected='true' " : '' }}>
                                        시급</option>
                                    <option value="1"
                                        {{ $jobs && $jobs->pay  && $jobs->pay['option'] && $jobs->pay['option'] == 1 ? " selected='true' " : '' }}>
                                        일급</option>
                                    <option value="2"
                                        {{ $jobs && $jobs->pay  && $jobs->pay['option'] && $jobs->pay['option'] == 2 ? " selected='true' " : '' }}>
                                        주급</option>
                                    <option value="3"
                                        {{ $jobs && $jobs->pay  && $jobs->pay['option'] && $jobs->pay['option'] == 3 ? " selected='true' " : '' }}>
                                        월급</option>
                                    <option value="4"
                                        {{ $jobs && $jobs->pay  && $jobs->pay['option'] && $jobs->pay['option'] == 4 ? " selected='true' " : '' }}>
                                        기타</option>
                                </select>
                                <div class="form-group form-group--search  flex-grow-1  mx-3">
                                    <input type="text" class="form-control"
                                        value="{{ $jobs && $jobs->pay ? $jobs->pay['price'] : null}}" required readonly style="background-color: white">
                                </div>
                            </div>
                        </div>
                        @if($firstParent->id == $parent->id)
                        <div class="form-group form-group--1">
                            <label for="period" class="form-control">
                                <input type=" text" id="period" placeholder="&nbsp;"
                                    value="{{$jobs->period ?? null}}" required readonly>
                                <span class="form-control__label">근무기간<span class="required">*</span></span>
                            </label>
                        </div>
                        @else
                        <div class="form-group form-group--1">
                            <label for="working_period" class="form-control">
                                <input type=" text" id="working_period" placeholder="&nbsp;"
                                    value="{{$jobs->working_period ?? null}}" required readonly>
                                <span class="form-control__label">근무기간<span class="required">*</span></span>
                            </label>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="applying_period" class="form-control">
                                <input type=" text" id="applying_period" placeholder="&nbsp;"
                                    value="{{$jobs->applying_period ?? null}}" required readonly>
                                <span class="form-control__label">모집기간<span class="required">*</span></span>
                            </label>
                        </div>

                        @endif

                        <!-- day-->
                        <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2 align-items-center required-group">
                            <span class="text-bold mr-4" style="margin-bottom: 10px">근무요일<span class="required">*</span></span>
                            <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
                                <div class="custom-control mr-4">
                                    <input type="checkbox" value="1" class="custom-control-input"
                                        id="day1" @if( !empty($jobs->day[1] )) checked @endif required onclick="return false;">
                                    <label class="custom-control-label" for="day1">월</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" value="2" class="custom-control-input"
                                        id="day2" @if( !empty($jobs->day[2] )) checked @endif required onclick="return false;">
                                    <label class="custom-control-label" for="day2">화</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" value="3" class="custom-control-input"
                                        id="day3" @if( !empty($jobs->day[3] )) checked @endif required onclick="return false;">
                                    <label class="custom-control-label" for="day3">수</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" value="4" class="custom-control-input"
                                        id="day4" @if( !empty($jobs->day[4] )) checked @endif required onclick="return false;">
                                    <label class="custom-control-label" for="day4">목</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" value="5" class="custom-control-input"
                                        id="day5" @if( !empty($jobs->day[5] )) checked @endif required onclick="return false;">
                                    <label class="custom-control-label" for="day5">금</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" value="6" class="custom-control-input"
                                        id="day6" @if( !empty($jobs->day[6] )) checked @endif required onclick="return false;">
                                    <label class="custom-control-label" for="day6">토</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox"  value="7" class="custom-control-input"
                                        id="day7" @if( !empty($jobs->day[7] )) checked @endif required onclick="return false;">
                                    <label class="custom-control-label" for="day7">일</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox"  value="8" class="custom-control-input"
                                        id="day8" @if( !empty($jobs->day[8] )) checked @endif required onclick="return false;">
                                    <label class="custom-control-label" for="day8">무관</label>
                                </div>
                                <div class="custom-control custom-checkbox mx-3 mr-4">
                                    <input type="checkbox" value="9" class="custom-control-input"
                                        id="day9" @if( !empty($jobs->day[8] )) checked @endif required onclick="return false;">
                                    <label class="custom-control-label" for="day9">협의 가능</label>
                                </div>

                            </div>
                        </div>
                        <!-- -->
                        <div class="form-group form-group--1">
                            <label for="time" class="form-control">
                                <input type=" text" id="time" placeholder="&nbsp;"
                                    value="{{$jobs->time ?? null}}" required readonly>
                                <span class="form-control__label">근무시간<span class="required">*</span></span>
                            </label>
                        </div>

                        @if($firstParent->id == $parent->id)
                        <div class="form-group form-group--1">
                            <label for="contactUs" class="form-control">
                                <input type="text" id="contactUs" placeholder="&nbsp;"
                                    value="{{$jobs->contact ?? null}}" required readonly>
                                <span class="form-control__label">연락방법 <span class="required">*</span></span>
                            </label>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="resume" class="form-control">
                                <input type=" text" id="resume" placeholder="&nbsp;"
                                    value="{{$jobs->resume ?? null}}" required readonly>
                                <span class="form-control__label">경력사항 <span class="required">*</span></span>
                            </label>
                        </div>

                        @else
                        <div class="form-group form-group--1">
                            <label for="open_position" class="form-control">
                                <input type=" text" id="open_position" placeholder="&nbsp;"
                                    value="{{$jobs->open_position ?? null}}" required readonly>
                                <span class="form-control__label">모집인원 <span class="required">*</span></span>
                            </label>
                        </div>
                        <div class="filter align-items-center"
                            style="padding:unset;background-color: transparent; margin-top: 10px; margin-bottom: 10px">
                            <div class="filter__item filter__title mr-3">상세위치</div>
                            <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
                                {{-- <div class="form-group form-group--search  flex-grow-1  mx-3">
                                    <input type="text"
                                        id="country" disabled="true"
                                        class="form-control"
                                        value="{{ $jobs && $jobs->exact_location && isset($jobs->exact_location['post_code'])
                                && $jobs->exact_location['post_code'][1]  ? $jobs->exact_location['post_code'][1] : null}}">
                                </div>
                                - --}}
                                <div class="form-group form-group--search  flex-grow-1  mx-3">
                                    <input type="text"
                                        id="postal_code" disabled="true"
                                        class="form-control"
                                        style="background-color: white;"
                                        value="{{ $jobs && $jobs->exact_location && isset($jobs->exact_location['post_code'])
                                && $jobs->exact_location['post_code'][2]  ? $jobs->exact_location['post_code'][2] : null}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group--1" style="display: none">
                                <input type="hidden" id="map_location_lat" placeholder="&nbsp;"
                                    value="{{$jobs && $jobs->exact_location && isset($jobs->exact_location['map_location']) ? $jobs->exact_location['map_location']['lat'] : null }}"
                                    required readonly>
                                <input type="hidden" id="map_location_lng" placeholder="&nbsp;"
                                    value="{{$jobs && $jobs->exact_location && isset($jobs->exact_location['map_location']) ? $jobs->exact_location['map_location']['lng'] : null }}"
                                    required readonly>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="detail_address" class="form-control">
                                <input type=" text" id="detail_address" placeholder="&nbsp;"
                                    onfocus="geolocate()"
                                    value="{{$jobs && $jobs->exact_location && isset($jobs->exact_location['detail_address']) ? $jobs->exact_location['detail_address'] : null }}"
                                    required readonly>
                            </label>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="contactUs" class="form-control">
                                <input type=" text" id="contactUs" placeholder="&nbsp;"
                                    value="{{$jobs->contact ?? null}}" required readonly>
                                <span class="form-control__label">연락방법 <span class="required">*</span></span>
                            </label>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="prefer_requirements" class="form-control">
                                <input type=" text" id="prefer_requirements" placeholder="&nbsp;"
                                    value="{{$jobs->prefer_requirements ?? null}}" required readonly>
                                <span class="form-control__label">우대사항 <span class="required">*</span></span>
                            </label>
                        </div>
                        @endif

                        <div class="form-group form-group--1">
                            <div class="text-bold mr-4 pr-2" style="margin-bottom: 10px;">기타사항<span class="required">
                                    *</span></label></div>
                            <div class="flex-grow-1">
                                <textarea class="ckeditor"
                                    id="content" readonly>{{$jobs->detail ?? null}}</textarea>
                                <script>
                                  CKEDITOR.replace( 'detail', {
                                      filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                      filebrowserUploadMethod: 'form'
                                  });
                                </script>
                            </div>
                        </div>
                        @if(!is_null($jobs->images))
                        <div class="d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                            <p class="text-bold mr-4 pr-2">{{__('life.part-time_job.images')}}</p>
                            <div class="d-flex flex-wrap flex-grow-1">
                                <div class="input-group-image" style="margin: auto;">
                                    <label for="uploadImage" class="form-control form-control--upload"
                                        style="height: 30em; width: 28em;max-width: 28em;{{$jobs ? 'background-color : white; background-position : center;' :''  }} background-size: 40em; background-image: url('{{ $jobs ? get_image_url($jobs->images) : ''  }}') ">
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div style="margin: 20px 0">
                            {!! Theme::partial('attachments',['link'=> $jobs->link,'file_upload'=>$jobs->file_upload]) !!}
                        </div>
                        <div class="d-sm-flex">
                            <span class="text-bold mr-4 pr-2">{{__('life.part-time_job.status')}} <span
                                    class="required">*</span></span>
                            <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" id="special_status" name="status">
                                    <option value="publish" @if(isset($jobs) && $jobs->status == "publish" ) selected
                                        @endif>{{__('life.part-time_job.in_recruitment')}}</option>
                                    <option value="pending" @if(isset($jobs) && $jobs->status == "pending" ) selected
                                        @endif>{{__('life.part-time_job.closed')}}</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class=" d-flex flex-wrap custom-checkbox mb-4">
                        <a href="{{getPolicyPage()}}" class="text-bold mr-4"
                            target="_blank">{{__('life.part-time_job.open_bulletin_board_policy')}}</a>
                        <div class="custom-control mr-4">
                            <input type="checkbox" name="policy_confirm" class="custom-control-input" id="agree"
                                checked>
                            <label class="custom-control-label" for="agree">{{__('life.part-time_job.agree')}}</label>
                        </div>
                        {{-- <span>
                        <span class="required">*</span>
                        {{__('life.part-time_job.view_bulletin_board_policy')}}
                        </span> --}}
                    </div>
                    <input type="hidden" name="base64Image" id="base64Image"
                        value="{{ $jobs ? get_image_url($jobs->images) : '' }}">
                    {!! Theme::partial('submit_form',[
                    'is_validate_image' => $jobs ? '0' : 1,
                    'route_preview' => route('jobsPartTimeFE.preview'),
                    'route_back' => route('life.part_time_jobs_list'),
                    'idPreview' => $jobs ? $jobs->id : '',
                    ]) !!}

                </form>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript">
  $('input[type="submit"]').on('click', function() {
    $cbx_group = $("input:checkbox[id^='day']");
    $cbx_group.prop('required', true);
    if($cbx_group.is(":checked")){
      $cbx_group.prop('required', false);
    }
  });
</script>
