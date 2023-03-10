<style>
        .input-group-image {
        margin-right: 17px;
    }
   .input-group-image .form-control--upload {
       height: 150px;
       width: 150px;
       max-width: 150px;
       background-size : 150px
   }
</style>
<main id="main-content" data-view="flea-market" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <!-- flare menu -->
            {!! Theme::partial('campus.menu',['active'=>"studyRoom"]) !!}
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
                        {{__('campus.study_room')}}
                    </div>
                    <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                        {!!$description->description ?? ""!!}
                    </div>
                </div>
                <form action="  @if(isset($studyRoom)) {{route('studyRoomFE.edit',['id'=>$studyRoom->id])}} @else {{route('studyRoomFE.create')}} @endif" id="my_form" method="post" enctype="multipart/form-data">
                    @csrf

                <div class="form form--border">
                    <h3 class="form__title">@if(isset($studyRoom)){{__('campus.study_room.edit_study_room')}} @else {{__('campus.study_room.create_study_room')}} @endif</h3>
                    <p class="text-right"><span class="required">* </span>{{__('campus.study_room.required')}}</p>

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
                    {!! Theme::partial('campus.elements.categories',['categories'=>$categories,'selectedCategories'=>$selectedCategories ?? null]) !!}
                    <div class="form-group form-group--1">
                        <label for="item" class="form-control">
                            <input type=" text" id="item" placeholder="&nbsp;" name="title" value="{{$studyRoom->title ?? null}}" required>
                            <span class="form-control__label">{{__('campus.study_room.title')}}<span class="required">*</span></span>
                        </label>
                    </div>
                    <div class="form-group form-group--1">
                        <label for="contactUs" class="form-control">
                            <input type=" text" id="contactUs" placeholder="&nbsp;" name="contact" value="{{$studyRoom->contact ?? null}}" required>
                            <span class="form-control__label">{{__('campus.study_room.contact')}}<span class="required">*</span></span>
                        </label>
                    </div>
                    <div class="form-group form-group--1" >
                        <div class="text-bold mr-4 pr-2" style="margin-bottom: 10px;">{{__('campus.study_room.detail')}}<span class="required"> *</span></label></div>
                        <div class="flex-grow-1">
                            <textarea class="ckeditor" name="detail" id="content">{{$studyRoom->detail ?? null}}</textarea>
                            <script>
                              CKEDITOR.replace( 'detail', {
                                  filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                  filebrowserUploadMethod: 'form'
                              });
                            </script>
                        </div>
                    </div>
                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                        <p class="text-bold mr-4">{{__('campus.study_room.images')}}</p>
                        <div class="d-flex flex-wrap flex-grow-1">
                            <div class="input-group-image">
                                <input type="hidden" class="imagesValue" name="imagesValue[1]" value="{{$studyRoom->images[1] ??  null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[1]" value="{{$studyRoom && $studyRoom->images[1] ? '/'.$studyRoom->images[1]  :  null }}" >
                                <a class="btn_remove_image" title="{{__('campus.study_room.remove_image')}}" @if( !isset($studyRoom->images[1] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                <label for="uploadFile0" class="form-control form-control--upload"  style="{{ isset($studyRoom->images[1] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($studyRoom->images[1] ) ? get_image_url($studyRoom->images[1],'thumb') : ''  }}') ">
                                    <input type="file" name="images[1]" class="" id="uploadFile0"
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
                                <input type="hidden" class="imagesValue" name="imagesValue[2]" value="{{$studyRoom->images[2] ??  null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[2]" value="{{$studyRoom && $studyRoom->images[2] ? '/'.$studyRoom->images[2]  :  null }}" >
                                <a class="btn_remove_image" title="{{__('campus.study_room.remove_image')}}" @if( !isset($studyRoom->images[2] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                <label for="uploadFile1" class="form-control form-control--upload"  style="{{ isset($studyRoom->images[2] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($studyRoom->images[2] ) ? get_image_url($studyRoom->images[2],'thumb') : ''  }}') ">
                                    <input type="file" name="images[2]" class="" id="uploadFile1"
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
                                <input type="hidden" class="imagesValue" name="imagesValue[3]" value="{{$studyRoom->images[3] ??  null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[3]" value="{{$studyRoom && $studyRoom->images[3] ? '/'.$studyRoom->images[3]  :  null }}" >
                                <a class="btn_remove_image" title="{{__('campus.study_room.remove_image')}}" @if( !isset($studyRoom->images[3] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                <label for="uploadFile2" class="form-control form-control--upload"  style="{{ isset($studyRoom->images[3] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($studyRoom->images[3] ) ? get_image_url($studyRoom->images[3],'thumb') : ''  }}') ">
                                    <input type="file" name="images[3]" class="" id="uploadFile2"
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
                                <input type="hidden" class="imagesValue" name="imagesValue[4]" value="{{$studyRoom->images[4] ??  null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[4]" value="{{$studyRoom && $studyRoom->images[4]? '/'.$studyRoom->images[4]  :  null }}" >
                                <a class="btn_remove_image" title="{{__('campus.study_room.remove_image')}}" @if( !isset($studyRoom->images[4] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                <label for="uploadFile3" class="form-control form-control--upload"  style="{{ isset($studyRoom->images[4] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($studyRoom->images[4] ) ? get_image_url($studyRoom->images[4],'thumb') : ''  }}') ">
                                    <input type="file" name="images[4]" class="" id="uploadFile3"
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
                                <input type="hidden" class="imagesValue" name="imagesValue[5]" value="{{$studyRoom->images[5] ??  null}}" >
                                <input type="hidden" class="imagesBase64" name="imagesBase64[5]" value="{{$studyRoom && $studyRoom->images[5] ? '/'.$studyRoom->images[5]  :  null }}" >
                                <a class="btn_remove_image" title="{{__('campus.study_room.remove_image')}}" @if( !isset($studyRoom->images[5] ) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                <label for="uploadFile4" class="form-control form-control--upload" style="{{ isset($studyRoom->images[5] ) ? 'background-color : white; background-position : center;' :''  }} background-image: url('{{ isset($studyRoom->images[5] ) ? get_image_url($studyRoom->images[5],'thumb') : ''  }}') ">
                                    <input type="file" name="images[5]" class="" id="uploadFile4"
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
                        {!! Theme::partial('upload_link_file',['link'=> $studyRoom ? $studyRoom->link : '',
                         'file_upload' => $studyRoom ? $studyRoom->file_upload : '' ]) !!}
                    </div>
                    {{-- <div class="d-sm-flex">
                        <span class="text-bold mr-4 pr-2" >{{__('campus.study_room.status')}}<span class="required">*</span></span>
                        <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset($studyRoom) && $studyRoom->status == "publish" ) selected @endif>{{__('campus.study_room.in_recruitment')}}</option>
                                    <option value="pending" @if(isset($studyRoom) && $studyRoom->status == "pending" ) selected @endif>{{__('campus.study_room.closed')}}</option>
                                    <option value="draft" @if(isset($studyRoom) && $studyRoom->status == "draft" ) selected @endif>{{__('campus.study_room.draft')}}</option>

                                </select>
                        </div>
                    </div> --}}

                </div>


                <div class=" d-flex flex-wrap custom-checkbox mb-4">
                    {{-- <a href="{{getPolicyPage()}}" class="text-bold mr-4" target="_blank">{{__('campus.study_room.open_bulletin_board_policy')}}</a> --}}
                    <span class="text-bold mr-4">{{__('campus.study_room.open_bulletin_board_policy')}}</span>
                    <div class="custom-control mr-4">
                        <input type="checkbox" name="policy_confirm" class="custom-control-input" id="agree" checked required>
                        <label class="custom-control-label" for="agree" >{{__('campus.study_room.agree')}}</label>
                    </div>
                    {{-- <span>
                        <span class="required">*</span>
                        {{__('campus.study_room.view_bulletin_board_policy')}}
                    </span> --}}
                    {!! Theme::partial('policy_pattern') !!}
                </div>
                {!! Theme::partial('submit_form',[
                    'is_validate_image' => 0,
                    'route_preview' => route('studyRoomFE.preview'),
                    'route_back' => route('campus.study_room_list'),
                    'idPreview' =>  $studyRoom ? $studyRoom->id : '',
                ]) !!}
                {{-- <div class="text-center">
                    <a href="javascript:{}" class="btn btn-outline temporary">{{__('campus.study_room.temporary_save')}}</a>
                    <a href="javascript:{}" class="btn btn-outline preview">{{__('campus.study_room.preview')}}</a>
                    <a href="javascript:{}" class="btn btn-primary save">{{__('campus.study_room.save')}}</a>
                </div> --}}
            </form>
            </div>
        </div>
    </div>
</main>

<script>
// $('.temporary').on('click',function(){
//     $('#my_form').attr('action',"@if(isset($studyRoom)) {{route('studyRoomFE.edit',['id'=>$studyRoom->id])}} @else {{route('studyRoomFE.create')}} @endif");
//     $('#my_form').attr('target','_self');

//     $('.form-control--select').val('pending');
//     $('#my_form').submit();
// })

// $('.preview').on('click',function(){
//     $('#my_form').attr('action','{{route('studyRoomFE.preview')}}');

//     $('#my_form').attr('target','_blank');
//     $('#my_form').submit();
// })

// $('.save').on('click',function(){
//     $('#my_form').attr('action',"@if(isset($studyRoom)) {{route('studyRoomFE.edit',['id'=>$studyRoom->id])}} @else {{route('studyRoomFE.create')}} @endif");
//     $('#my_form').removeAttr('target');
//     $('#my_form').submit();
// })
</script>
