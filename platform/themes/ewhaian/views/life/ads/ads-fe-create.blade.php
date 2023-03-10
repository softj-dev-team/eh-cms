<style>
    .input-group-image {
        margin-right: 17px;
    }

    .input-group-image .form-control--upload {
        height: 150px;
        width: 150px;
        max-width: 150px;
    }

    .data-origin-template .align-items-center {
        display: none !important;
    }
</style>
<main id="main-content" data-view="flea-market" data-page="life" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <!-- flare menu -->
            {!! Theme::partial('life.menu',['active'=>"advertisements"]) !!}
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
                        {{__('life.advertisements')}}
                    </div>
                    <div class="heading__description">
                        {!!$description->description ?? ""!!}
                    </div>
                </div>
                <form
                    action="  @if(isset($ads)) {{route('adsFE.edit',['id'=>$ads->id])}} @else {{route('adsFE.create')}} @endif"
                    id="my_form" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form form--border">
                        <h3 class="form__title">@if(isset($ads)){{__('life.advertisements.edit_advertisements')}}@else {{__('life.advertisements.create_advertisements')}} @endif</h3>
                        <p class="text-right"><span class="required">* </span>{{__('life.advertisements.required')}}</p>

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

                        <div class="custom-control custom-checkbox mx-3" style="padding-bottom: 10px;">
                            <input type="checkbox" class="custom-control-input" id="is_deadline" name="is_deadline"
                                value="1" @if( $ads ? ($ads->is_deadline == 0) : false) ) @else checked @endif >
                            <label class="custom-control-label" for="is_deadline">{{__('life.advertisements.is_deadline')}}</label>
                        </div>
                        <div class="check_deadline" @if( $ads ? ($ads->is_deadline == 0) : false) ) style="display :
                            none" @endif>
                            <div class="d-sm-flex align-items-center ">
                                <span class="text-bold mr-3">{{__('life.advertisements.deadline')}}</span>
                                <div class="flex-grow-1 mx-3">
                                    <div class="d-flex align-items-center">
                                        <div class="form-group form-group--search  flex-grow-1 mr-1 has-datetime">
                                            <span class="form-control__icon form-control__icon--gray">
                                                <svg width="20" height="17" aria-hidden="true" class="icon">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        xlink:href="#icon_calender"></use>
                                                </svg>
                                            </span>
                                            <input data-datepicker-end type="text" class="form-control startDate"
                                                id="startDate" name="start"
                                                value="{{ $ads ? date("Y.m.d", strtotime($ads->start))  : getToDate(1) }} " autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                                <span class="filter__connect">~</span>
                                <div class="flex-grow-1 mx-3">
                                    <div class="d-flex align-items-center">
                                        <div class="form-group form-group--search  flex-grow-1 mr-1 has-datetime">
                                            <span class="form-control__icon form-control__icon--gray">
                                                <svg width="20" height="17" aria-hidden="true" class="icon">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        xlink:href="#icon_calender"></use>
                                                </svg>
                                            </span>
                                            <input data-datepicker-end type="text" class="form-control endDate"
                                                id="endDate" name="deadline"
                                                value="{{ $ads ? date("Y.m.d", strtotime($ads->deadline))  : getToDate(1) }} " autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group--1 d-sm-flex flex-wrap ">
                            <span class="text-bold mr-1 pt-4">{{__('life.advertisements.title')}}</span><span class="required pt-4">* </span>

                            <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center ml-3">

                              <label for="item" class="form-control" style="line-height:2.5em">
                                  <input type=" text" id="item" placeholder="&nbsp;" name="title"
                                      value="{{$ads->title ?? null}}" required>
                                  {{-- <span class="form-control__label"> {{__('life.advertisements.title')}} <span class="required">*</span></span> --}}
                              </label>
                            </div>
                        </div>
                        <div class="form-group form-group--1">
                            <label for="item" class="form-control">
                                <input type=" text" id="item" placeholder="&nbsp;" name="duration2"
                                    value="{{$ads->duration2 ?? null}}">
                                <span class="form-control__label"> 활동기간</span>
                            </label>
                        </div>
                        {{-- <div class="d-sm-flex align-items-center">
                            <span class="text-bold mr-3">{{__('life.advertisements.duration_activity')}}<span class="required">*</span></span>
                            <div class="flex-grow-1 mx-3">
                              <div class="d-flex align-items-center">
                                <div class="form-group form-group--search  flex-grow-1 mr-1">
                                  <span class="form-control__icon form-control__icon--gray">
                                    <svg width="20" height="17" aria-hidden="true" class="icon">
                                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_calender"></use>
                                    </svg>
                                  </span>
                                  <input data-datepicker-start type="text" class="form-control startDate" id="startDate" name="duration" value="{{ $ads ? date("Y.m.d", strtotime($ads->duration))  : getToDate(1) }} " autocomplete="off" required>
                                </div>
                              </div>
                            </div>
                          </div> --}}

                        <div class="d-sm-flex flex-wrap align-items-center">
                            <div class=" d-flex align-items-center flex-grow-1">
                                <div class="form-group form-group--1 flex-grow-1">
                                    <label for="recruiment" class="form-control">
                                        <input type="number" id="recruiment" placeholder="&nbsp;" name="recruitment"
                                            value="{{$ads->recruitment ?? null}}">
                                        <span class="form-control__label">{{__('life.advertisements.recruitment_no')}}
                                    </label>
                                </div>
                                <div class="px-2">{{__('life.advertisements.person')}}</div>
                            </div>

                            <div class="px-2">
                                <div class="custom-control custom-checkbox mx-3">
                                    <input type="checkbox" class="custom-control-input" id="other1">
                                    <label class="custom-control-label" for="other1">{{__('life.advertisements.other')}}</label>
                                </div>
                            </div>

                        </div>
                        <div class="form-group form-group--1">
                            <label for="contact" class="form-control">
                                <input type="text" id="contact" placeholder="&nbsp;" name="contact"
                                    value="{{$ads->contact ?? null}}">
                                <span class="form-control__label"> {{__('life.advertisements.contact')}}</span>
                            </label>
                        </div>
{{--                        <div class="form-group form-group--1">--}}
{{--                            <label for="bolong" class="form-control">--}}
{{--                                <input type="text" id="bolong" placeholder="&nbsp;"--}}
{{--                                    value="{{$ads ?  $ads->getNameMemberById($ads->member_id) :  auth()->guard('member')->user()->nickname   }}"--}}
{{--                                    readonly>--}}
{{--                                <span class="form-control__label"> {{__('life.advertisements.belong')}}</span>--}}

{{--                            </label>--}}
{{--                        </div>--}}
                        <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                            {!!
                            Theme::partial('life.elements.categories',['categories'=>$categories,'selectedCategories'=>$selectedCategories
                            ?? null]) !!}

                        </div>
                    <!-- club-->
                     <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2 align-items-center">
                        <span class="text-bold mr-4" style="margin-bottom: 10px">동아리 특성</span>
                         <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
                             <div class="custom-control mr-4">
                                 <input type="checkbox" name="club[1]" value="1" class="custom-control-input" id="club1" @if( !empty($ads->club[1] )) checked @endif >
                                 <label class="custom-control-label" for="club1">친목</label>
                             </div>
                             <div class="custom-control custom-checkbox mx-3 mr-4">
                                 <input type="checkbox" name="club[2]" value="2" class="custom-control-input"  id="club2" @if( !empty($ads->club[2] )) checked @endif >
                                 <label class="custom-control-label" for="club2">스터디</label>
                             </div>
                             <div class="custom-control custom-checkbox mx-3 mr-4">
                                 <input type="checkbox" name="club[3]" value="3" class="custom-control-input" id="club3" @if( !empty($ads->club[3] )) checked @endif >
                                 <label class="custom-control-label" for="club3">공모전</label>
                             </div>
                             <div class="custom-control custom-checkbox mx-3 mr-4">
                                 <input type="checkbox" name="club[5]" value="5" class="custom-control-input" id="club5" @if( !empty($ads->club[5] )) checked @endif >
                                 <label class="custom-control-label" for="club5">기타</label>
                             </div>
                             <input type="hidden" name="club[6]" value="@if( !empty($ads->club[6] )) {{$ads->club[6]}} @endif" class="form-control form-control--auto flex-grow-1"
                             placeholder="{{__('life.flea_market.enter_your_words')}}">

                         </div>
                     </div>
                    <!-- -->
                        <div class="form-group form-group--1">
                            <div class="text-bold mr-4 pr-2" style="margin-bottom: 10px;">{{__('life.advertisements.introduce')}}</label></div>
                            <div class="flex-grow-1">
                                <textarea class="ckeditor" name="details" id="content">{{$ads->details ?? null}}</textarea>
                                <script>
                                  CKEDITOR.replace( 'details', {
                                      filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                      filebrowserUploadMethod: 'form'
                                  });
                                </script>
                            </div>
                        </div>
                        <div class="d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                            <p class="text-bold mr-4 pr-2">{{__('life.advertisements.images')}}</p>
                            <div class="d-flex flex-wrap flex-grow-1">
                                <div class="input-group-image" style="margin: auto;">
                                    <label for="uploadImage" class="form-control form-control--upload "
                                        style="height: 30em; width: 28em;max-width: 28em;{{$ads ? 'background-color : white; background-position : center;' :''  }} background-size: 40em; background-image: url('{{ $ads ? get_image_url($ads->images) : ''  }}') ">
                                        <input type="file" class="" id="uploadImage"
                                            accept="image/gif, image/jpeg, image/png" data-add-image name="images"
                                            value='{{$ads ? $ads->images : '' }}'>
                                        <span class="form-control__label">
                                            <svg width="30" height="30" aria-hidden="true" class="icon">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    xlink:href="#icon_plus_big"></use>
                                            </svg>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div style="margin: 20px 0">
                            {!! Theme::partial('upload_link_file',['link'=> $ads ? $ads->link : '',
                             'file_upload' => $ads ? $ads->file_upload : '' ]) !!}
                        </div>
                        {{-- <div class="d-sm-flex">
                            <span class="text-bold mr-4 pr-2">{{__('life.advertisements.status')}} <span class="required">*</span></span>
                            <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset($ads) && $ads->status == "publish" ) selected
                                        @endif>{{__('life.advertisements.publish')}}</option>
                                    <option value="draft" @if(isset($ads) && $ads->status == "draft" ) selected
                                        @endif>{{__('life.advertisements.draft')}}</option>
                                </select>
                            </div>
                        </div> --}}
                    </div>
                    <div class=" d-flex flex-wrap custom-checkbox mb-4">
                        {{-- <a href="{{getPolicyPage()}}" class="text-bold mr-4" target="_blank">{{__('life.advertisements.open_bulletin_board_policy')}}</a> --}}
                        <span class="text-bold mr-4">{{__('life.advertisements.open_bulletin_board_policy')}}</span>
                        <div class="custom-control mr-4">
                            <input type="checkbox" name="policy_confirm" class="custom-control-input" id="agree"
                                checked required>
                            <label class="custom-control-label" for="agree">{{__('life.advertisements.agree')}}</label>
                        </div>
                        {{-- <span>
                            <span class="required">*</span>
                            {{__('life.advertisements.view_bulletin_board_policy')}}
                        </span> --}}
                        {!! Theme::partial('policy_pattern') !!}
                    </div>
                    <input type="hidden" name="base64Image" id="base64Image" value="{{ $ads ? get_image_url($ads->images) : '' }}">
                    <input type="hidden" name="idAds" id="idAds" value="{{ $ads ? $ads->id : '' }}">
                    {!! Theme::partial('submit_form',[
                        'is_validate_image' => 0,
                        'route_preview' => route('adsFE.preview'),
                        'idPreview' =>  $ads ? $ads->id : '',
                    ]) !!}
                    {{-- <div class="text-center">
                        <a href="javascript:{}" class="btn btn-outline temporary">{{__('life.advertisements.temporary_save')}}</a>
                        <a href="javascript:{}" class="btn btn-outline preview" data-value="">{{__('life.advertisements.preview')}}</a>
                        <a href="javascript:{}" class="btn btn-primary save">{{__('life.advertisements.enrollment')}}</a>
                    </div> --}}

                </form>
            </div>
        </div>
    </div>
</main>
<script>
    $(function(){
    $('#is_deadline').on('change',function(){
        if($(this).is(':checked')){
           $('.check_deadline').css('display','block');
        }else {
            $('.check_deadline').css('display','none');
        }
    })

    if($('input[name="club[5]"]').attr('checked')){
            $('input[name="club[6]"]').prop('type','text');
            $('input[name="club[6]"]').focus();
        }

    $('input[name="club[5]"]').on('click', function () {
        if ($(this).prop('checked')) {
            $('input[name="club[6]"]').prop('type','text')
            $('input[name="club[6]"]').fadeIn();
            $('input[name="club[6]"]').focus();
        } else {
            $('input[name="club[6]"]').fadeOut();
            $('input[name="club[6]"]').prop('type','hidden')
        }
    });

})
</script>
