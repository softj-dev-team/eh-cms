<style>
        .input-group-image {
        margin-right: 17px;
    }
   .input-group-image .form-control--upload {
       height: 150px;
       width: 150px;
       background-size : 150px
   }
   .custom-control {
  padding-left: 2.14286em; }
  .custom-radio .custom-control-input:checked ~ .custom-control-label:before {
    border-color: #EC1469; }
  .custom-radio .custom-control-input:checked ~ .custom-control-label:after {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11.46' height='8.246' viewBox='0 0 11.46 8.246'%3E%3Cpath id='checked' d='M4.124,68.077a.906.906,0,0,1-.643-.266L.267,64.6a.909.909,0,0,1,1.286-1.286l2.571,2.571L9.909,60.1a.909.909,0,1,1,1.285,1.286L4.766,67.811A.907.907,0,0,1,4.124,68.077Z' transform='translate(-0.001 -59.831)' fill='%23ec1469'/%3E%3C/svg%3E%0A"); }
  .custom-radio .custom-control-input:focus ~ .custom-control-label:before {
    box-shadow: none; }
  .custom-radio .custom-control-input:checked ~ .custom-control-label:before, .custom-radio .custom-control-input:active ~ .custom-control-label:before {
    background-color: transparent; }
  .custom-radio .custom-control-input:not(:disabled):active ~ .custom-control-label:before {
    border-color: #e8e8e8; }
  .custom-control-label:after, .custom-control-label:before {
    width: 1.42857em;
    height: 1.42857em;
    top: 50%;
    left: -2.14286em;
    -webkit-transform: translateY(-50%);
            transform: translateY(-50%); }
  .custom-control-label:after {
    top: 50%;
    -webkit-transform: translateY(-50%);
            transform: translateY(-50%); }
    .data-origin-template .align-items-center {
        display: none !important;
    }

</style>

<main id="main-content" data-view="flea-market" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <!-- flare menu -->
            {!! Theme::partial('campus.menu',['active'=>"genealogy"]) !!}
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
                <div class="heading" style="display: flex;">
                    <div class="heading__title" style="white-space: nowrap;">
                        {{__('campus.genealogy')}}
                    </div>
                    <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                        {!!$description->description ?? ""!!}
                    </div>
                </div>
                <form action="  @if(isset($genealogy)) {{route('genealogyFE.edit',['id'=>$genealogy->id])}} @else {{route('genealogyFE.create')}} @endif" id="my_form" method="post" enctype="multipart/form-data">
                    @csrf

                <div class="form form--border">
                    <h3 class="form__title">@if(isset($genealogy)){{__('campus.genealogy.edit_genealogy')}} @else {{__('campus.genealogy.create_genealogy')}} @endif</h3>
                    <p class="text-right"><span class="required">* </span>{{__('campus.genealogy.required')}}</p>

                    @if ($errors->any())
                    <div >
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
                    <div class=" d-sm-flex flex-wrap custom-radio form-group pt-2 align-items-center">
                        <span class="text-bold mr-4">{{__('campus.genealogy.semester')}}<span class="required">*</span></span>
                        <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
                            <div class="form-group form-group--search  flex-grow-1 mr-4">
                                <span class="form-control__icon form-control__icon--gray">
                                  <svg width="20" height="17" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_calender"></use>
                                  </svg>
                                </span>
                                <input data-datepicker-year type="text" class="form-control startDate" name="semester_year" value="{{ $genealogy ? date("Y", strtotime($genealogy->semester_year))  : date("Y") }} " autocomplete="off">
                              </div>
                              <div class="custom-control mr-4">
                                <input type="radio" name="semester_session" value="1" class="custom-control-input semester_session" id="session_1" @if(empty($genealogy) || $genealogy->semester_session == "1" ) checked="true" @endif>
                                <label class="custom-control-label"  for="session_1">{{__('campus.genealogy.session',['session'=> 1])}}</label>
                            </div>
                            <div class="custom-control custom-radio mr-4">
                                <input type="radio" name="semester_session" value="2" class="custom-control-input semester_session"  id="session_2" @if( !empty($genealogy) && $genealogy->semester_session == "2" )  checked="true" @endif >
                                <label class="custom-control-label"  for="session_2">{{__('campus.genealogy.session',['session'=> 2])}}</label>
                            </div>
                            <div class="custom-control custom-radio mr-4">
                                <input type="radio" name="semester_session" value="3" class="custom-control-input semester_session"  id="session_3" @if( !empty($genealogy) && $genealogy->semester_session == "3" )  checked="true" @endif >
                                <label class="custom-control-label"  for="session_3">여름계절</label>
                            </div>
                            <div class="custom-control custom-radio mr-4">
                                <input type="radio" name="semester_session" value="4" class="custom-control-input semester_session"  id="session_4" @if( !empty($genealogy) && $genealogy->semester_session == "4" )  checked="true" @endif >
                                <label class="custom-control-label"  for="session_4">겨울계절</label>
                            </div>

                        </div>
                    </div>

                    <div class="form-group form-group--1">
                        <label for="purchasingPrice" class="form-control">
                            <input type="text" id="className" placeholder="&nbsp;" name="class_name" value="{{$genealogy->class_name ?? ''}}" required>
                            <span class="form-control__label">{{__('campus.genealogy.class_name')}}<span class="required">*</span></span>
                        </label>
                    </div>
                    <div class="form-group form-group--1">
                        <label for="purchasingPrice" class="form-control">
                            <input type="text" id="professorName" placeholder="&nbsp;" name="professor_name" value="{{$genealogy->professor_name ?? ''}}" required>
                            <span class="form-control__label">{{__('campus.genealogy.professor_name')}}<span class="required">*</span></span>
                        </label>
                    </div>

                    <div class=" d-sm-flex flex-wrap custom-radio form-group pt-2 align-items-center">
                        <span class="text-bold mr-4">{{__('campus.genealogy.exam_name')}}<span class="required">*</span></span>
                        <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
                            <div class="custom-control mr-4">
                                <input type="radio" name="exam_name" value="midterm" class="custom-control-input exam_name" id="midterm" @if( empty($genealogy) || $genealogy->exam_name == "midterm" )  checked="true" @endif >
                                <label class="custom-control-label"  for="midterm">{{__('campus.genealogy.midterm')}}</label>
                            </div>
                            <div class="custom-control custom-radio mr-4">
                                <input type="radio" name="exam_name" value="final" class="custom-control-input exam_name"  id="final" @if( !empty($genealogy) && $genealogy->exam_name == "final" )  checked="true" @endif>
                                <label class="custom-control-label"  for="final">{{__('campus.genealogy.final')}}</label>
                            </div>
                            <div class="custom-control custom-radio mr-4">
                                <input type="radio" name="exam_name" value="quiz" class="custom-control-input exam_name" id="quiz" @if( !empty($genealogy) && $genealogy->exam_name == "quiz" )  checked="true" @endif>
                                <label class="custom-control-label"  for="quiz">{{__('campus.genealogy.quiz')}}</label>
                            </div>
                            <div class="custom-control custom-radio mr-4">
                                <input type="radio" name="exam_name" value="other" class="custom-control-input exam_name" id="other" @if( !empty($genealogy) && $genealogy->exam_name != "midterm" && $genealogy->exam_name != "final"&& $genealogy->exam_name != "quiz" )  checked="true" @endif>
                                <label class="custom-control-label"  for="other">{{__('campus.genealogy.other')}}</label>
                            </div>
                            <input type="hidden" name="exam_name_text" value="{{$genealogy->exam_name ?? ''}}" class="form-control form-control--auto flex-grow-1"
                                placeholder="{{__('campus.genealogy.enter_your_words')}}" id="exam_name_text" required>
                        </div>
                    </div>

                    {{-- <div class="form-group form-group--1">
                        <span class="text-bold mr-4" style="margin-bottom: 10px; display: block;">{{__('campus.genealogy.categories')}}<span class="required">*</span></span>
                        <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center" >
                            <select class="form-control form-control--select mx-3 " id="multiple" name="major[]" multiple="multiple" required>
                                @foreach ($major as $key => $item)
                                    @if($item->getChild()->count() > 0)
                                        @foreach ($item->getChild() as $key => $subitem)
                                        <option value="{{$subitem->id}}"
                                                @if (!is_null($genealogy))
                                                    @if (count( $subitem->getItemById($genealogy->id ?? null,1 )->get() ) > 0 )
                                                        selected
                                                    @endif
                                                @endif>
                                            {{$subitem->name}}
                                        </option>
                                        @endforeach
                                    @else
                                    <option value="{{$item->id}}"
                                            @if (!is_null($genealogy))
                                                @if (count( $item->getItemById($genealogy->id ?? null,1)->get() ) > 0 )
                                                    selected
                                                @endif
                                            @endif>
                                        {{$item->name}}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="form-group form-group--1" >
                        <div class="text-bold mr-4 pr-2" style="margin-bottom: 10px;">{{__('campus.genealogy.detail')}}<span class="required"> *</span></label></div>
                        <div class="flex-grow-1">
                            <textarea class="ckeditor" name="detail" id="content">{{$genealogy->detail ?? null}}</textarea>
                            <script>
                              CKEDITOR.replace( 'detail', {
                                  filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                  filebrowserUploadMethod: 'form'
                              });
                            </script>
                        </div>
                    </div>
                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                        <p class="text-bold mr-4">{{__('campus.genealogy.images')}}</p>
                        <div class="d-flex flex-wrap flex-grow-1">
                            <div class="input-group-image">
                                <input type="hidden" class="imagesValue" name="imagesValue[1]" value="{{$genealogy && $genealogy->images && $genealogy->images[1] ??  null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[1]" value="{{$genealogy && $genealogy->images && $genealogy->images[1] ? '/'.$genealogy->images[1]  :  null }}" >
                                <a class="btn_remove_image" title="{{__('campus.genealogy.remove_image')}}" @if( !isset($genealogy->images[1] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                <label for="uploadImage" class="form-control form-control--upload"  style="{{ isset($genealogy->images[1] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($genealogy->images[1] ) ? get_image_url($genealogy->images[1],'thumb') : ''  }}') ">
                                    <input type="file" name="images[1]" class="" id="uploadImage"
                                        accept="image/gif, image/jpeg, image/png" data-add-image>
                                    <span class="form-control__label">
                                        <svg width="30" height="30" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                            </use>
                                        </svg>
                                    </span>
                                </label>
                            </div>

                            <div class="input-group-image">
                                <input type="hidden" class="imagesValue" name="imagesValue[2]" value="{{$genealogy && $genealogy->images && $genealogy->images[2] ??  null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[2]" value="{{$genealogy && $genealogy->images && $genealogy->images[2] ? '/'.$genealogy->images[2]  :  null }}" >
                                <a class="btn_remove_image" title="{{__('campus.genealogy.remove_image')}}" @if( !isset($genealogy->images[2] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                <label for="uploadImage1" class="form-control form-control--upload"  style="{{ isset($genealogy->images[2] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($genealogy->images[2] ) ? get_image_url($genealogy->images[2],'thumb') : ''  }}') ">
                                    <input type="file" name="images[2]" class="" id="uploadImage1"
                                        accept="image/gif, image/jpeg, image/png" data-add-image>
                                    <span class="form-control__label">
                                        <svg width="30" height="30" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                            </use>
                                        </svg>
                                    </span>
                                </label>
                            </div>

                            <div class="input-group-image">
                                <input type="hidden" class="imagesValue" name="imagesValue[3]" value="{{$genealogy && $genealogy->images &&$genealogy->images[3] ??  null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[3]" value="{{$genealogy && $genealogy->images && $genealogy->images[3] ? '/'.$genealogy->images[3]  :  null }}" >
                                <a class="btn_remove_image" title="{{__('campus.genealogy.remove_image')}}" @if( !isset($genealogy->images[3] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                <label for="uploadImage2" class="form-control form-control--upload"  style="{{ isset($genealogy->images[3] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($genealogy->images[3] ) ? get_image_url($genealogy->images[3],'thumb') : ''  }}') ">
                                    <input type="file" name="images[3]" class="" id="uploadImage2"
                                        accept="image/gif, image/jpeg, image/png" data-add-image>
                                    <span class="form-control__label">
                                        <svg width="30" height="30" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                            </use>
                                        </svg>
                                    </span>
                                </label>

                            </div>
                            <div class="input-group-image">
                                <input type="hidden" class="imagesValue" name="imagesValue[4]" value="{{$genealogy && $genealogy->images &&$genealogy->images[4] ??  null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[4]" value="{{$genealogy && $genealogy->images && $genealogy->images[4]? '/'.$genealogy->images[4]  :  null }}" >
                                <a class="btn_remove_image" title="{{__('campus.genealogy.remove_image')}}" @if( !isset($genealogy->images[4] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                <label for="uploadImage3" class="form-control form-control--upload"  style="{{ isset($genealogy->images[4] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($genealogy->images[4] ) ? get_image_url($genealogy->images[4],'thumb') : ''  }}') ">
                                    <input type="file" name="images[4]" class="" id="uploadImage3"
                                        accept="image/gif, image/jpeg, image/png" data-add-image>
                                    <span class="form-control__label">
                                        <svg width="30" height="30" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                            </use>
                                        </svg>
                                    </span>
                                </label>
                            </div>
                            <div class="input-group-image">
                                <input type="hidden" class="imagesValue" name="imagesValue[5]" value="{{$genealogy && $genealogy->images && $genealogy->images[5] ??  null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[5]" value="{{$genealogy && $genealogy->images && $genealogy->images[5] ? '/'.$genealogy->images[5]  :  null }}" >
                                <a class="btn_remove_image" title="{{__('campus.genealogy.remove_image')}}" @if( !isset($genealogy->images[5] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                <label for="uploadImage4" class="form-control form-control--upload" style="{{ isset($genealogy->images[5] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($genealogy->images[5] ) ? get_image_url($genealogy->images[5],'thumb') : ''  }}') ">
                                    <input type="file" name="images[5]" class="" id="uploadImage4"
                                        accept="image/gif, image/jpeg, image/png" data-add-image>
                                    <span class="form-control__label">
                                        <svg width="30" height="30" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                            </use>
                                        </svg>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div style="margin: 20px 0">
                        {!! Theme::partial('upload_link_file',['link'=> $genealogy ? $genealogy->link : '',
                         'file_upload' => $genealogy ? $genealogy->file_upload : '' ]) !!}
                    </div>
                    {{-- <div class="d-sm-flex">
                        <span class="text-bold mr-4 pr-2" >{{__('campus.genealogy.status')}} <span class="required">*</span></span>
                        <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset($genealogy) && $genealogy->status == "publish" ) selected @endif>{{__('campus.genealogy.publish')}}</option>
                                    <option value="draft" @if(isset($genealogy) && $genealogy->status == "draft" ) selected @endif>{{__('campus.genealogy.draft')}}</option>
                                </select>
                        </div>
                    </div> --}}
                </div>

                <div class=" d-flex flex-wrap custom-checkbox mb-4">
                    {{-- <a href="{{getPolicyPage()}}" class="text-bold mr-4" target="_blank">{{__('campus.genealogy.open_bulletin_board_policy')}}</a> --}}
                    <span class="text-bold mr-4">{{__('campus.genealogy.open_bulletin_board_policy')}}</span>
                    <div class="custom-control mr-4">
                        <input type="checkbox" name="policy_confirm" class="custom-control-input" id="agree" checked required>
                        <label class="custom-control-label" for="agree" >{{__('campus.genealogy.agree')}}</label>
                    </div>
                    {!! Theme::partial('policy_pattern') !!}
                </div>
                {{-- <input type="hidden" name="idGenealogy" id="idGenealogy" value="{{ $genealogy ? $genealogy->id : '' }}"> --}}
                {!! Theme::partial('submit_form',[
                    'is_validate_image' => 0,
                    'route_preview' => route('genealogyFE.preview'),
                    'route_back' => route('campus.genealogy_list'),
                    'idPreview' =>  $genealogy ? $genealogy->id : '',
                ]) !!}
            </form>
            </div>
        </div>
    </div>
</main>

<script>
    if($('#other').prop('checked') == true){
        $('#exam_name_text').prop('type','text');
    }
    if($('#other').attr('checked') == false){
        $('#exam_name_text').prop('type','hidden');
    }

    //show it when the checkbox is clicked
    $('.exam_name').on('click', function () {
        if ($('#other').prop('checked') == true) {
            // $('#exam_name_text').val('');
            $('#exam_name_text').prop('type','text')
            $('#exam_name_text').fadeIn();
            $('#exam_name_text').focus();
        } else {
            $('#exam_name_text').fadeOut();
            $('#exam_name_text').prop('type','hidden');
            // $('#exam_name_text').val('');
        }
    });

    if($('#session_other').attr('checked')){
        $('#semester_session_text').prop('type','text');
    }

    //show it when the checkbox is clicked
    $('.semester_session').on('click', function () {
        if ($('#session_other').prop('checked')) {
            $('#semester_session_text').val('');
            $('#semester_session_text').prop('type','text')
            $('#semester_session_text').fadeIn();
            $('#semester_session_text').focus();
        } else {
            $('#semester_session_text').fadeOut();
            $('#semester_session_text').prop('type','hidden')
            $('#semester_session_text').val('');
        }
    });




// $('.preview').on('click',function(){
//     $('#my_form').attr('action','{{route('genealogyFE.preview')}}');

//     $('#my_form').attr('target','_blank');
//     $('#my_form').submit();
// })

// $('.save').on('click',function(){
//     $('#my_form').attr('action',"@if(isset($genealogy)) {{route('genealogyFE.edit',['id'=>$genealogy->id])}} @else {{route('genealogyFE.create')}} @endif");
//     $('#my_form').removeAttr('target');
//     $('#my_form').submit();
// })

$("#multiple").select2({
    theme: "bootstrap"
});

</script>
