<main id="main-content" data-view="advertisement-enrollment" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- create menu -->
                {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>0,'egarden'=>1]) !!}
                <!-- end of create menu -->
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
                <div class="heading">
                    <div class="heading__title">
                        @if( is_null($egarden)) {{__('egarden.create_new_egarden')}} @else
                        {{__('egarden.edit_egarden')}} @endif
                    </div>
                </div>
                @if (session('err'))
                <div class="alert alert-danger" style="display: block">
                    <p style="margin-bottom: rem;">{{ session('err') }}</p>
                </div>
                @endif

                @if (session('success'))
                <div class="alert alert-success" style="display: block">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                @if ($errors->any())
                <div class="alert alert-danger" style="display: block">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="form form--border pb-1 pt-2 align-items-center mb-4">
                    <form id="my_form" method="POST"
                        action="@if( is_null($egarden)) {{route('egardenFE.create',['id'=>$id])}} @else {{route('egardenFE.edit',['id'=>$id,'idEgarden'=>$egarden->id])}} @endif "
                        enctype="multipart/form-data">
                        @csrf

                        <div>
                            <div class="text-bold mr-4 pr-2" style="margin-top: 10px; margin-bottom:10px">
                                {{__('egarden.title')}} <span class="required">*</span></div>

                            <div class="form-group form-group--search flex-grow-1">
                                <input type="text" class="form-control" placeholder="{{__('egarden.enter_title_for_create')}}"
                                    name="title" value="{{$egarden->title ?? ''}}">
                            </div>
                        </div>
                        @if (count($categoreisRoom) > 0 )
                        <div style="display: flex">
                            <div class="text-bold mr-4 pr-2" style="margin-top: 10px; margin-bottom:10px">
                                카테고리
                                <span class="required">*</span>
                            </div>

                            <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="categories_room_id">
                                    @foreach ($categoreisRoom as $item)
                                    <option value="{{$item->id}}"
                                        @if (isset($egarden) && $egarden->categories_room_id == $item->id ) selected @endif
                                    >{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                            <p class="text-bold mr-4 pr-2">{{__('contents.banner')}}</p>
                            <div class="d-flex flex-wrap flex-grow-1">
                              <div class="input-group-image" style="margin: auto;">
                              <label for="uploadImage" class="form-control form-control--upload" style="height: 30em; width: 28em;max-width: 28em;{{$egarden ? 'background-color : white; background-position : center;' :''  }} background-size: 40em; background-image: url('{{ $egarden ? get_image_url($egarden->banner) : ''  }}') ">
                                  <input type="file" class="" id="uploadImage" accept="image/gif, image/jpeg, image/png" data-add-image name="banner" value='{{$egarden ? $egarden->egarden: '' }}' >
                                  <span class="form-control__label">
                                    <svg width="30" height="30" aria-hidden="true" class="icon">
                                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big"></use>
                                    </svg>
                                  </span>
                                </label>
                              </div>
                            </div>
                        </div>
                        <div>
                            <div class="text-bold mr-4 pr-2" style="margin-top: 10px; margin-bottom:10px">
                                {{__('egarden.detail')}} <span class="required">*</span></div>
                            <textarea class="ckeditor" name="detail" id="content">{{$egarden->detail ?? ''}}</textarea>
                            <script>
                              CKEDITOR.replace( 'detail', {
                                  filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                  filebrowserUploadMethod: 'form'
                              });
                            </script>
                        </div>
                        <div style="margin: 20px 0">
                            {!! Theme::partial('upload_link_file',['link'=> $egarden ? $egarden->link : '',
                            'file_upload' => $egarden ? $egarden->file_upload : '' ]) !!}
                        </div>
                        {{-- <div class="d-sm-flex" style="margin-top: 20px;">
                            <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('egarden.status')}} <span
                                    class="required">*</span></span>
                            <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset($egarden) && $egarden->status == "publish" )
                                        selected @endif>{{__('egarden.publish')}}</option>
                                    <option value="draft" @if(isset($egarden) && $egarden->status == "draft" ) selected
                                        @endif>{{__('egarden.draft')}}</option>
                                </select>
                            </div>
                        </div> --}}
                </div>
                <div class="d-flex flex-wrap custom-checkbox mb-2 mt-5">
                    <p class="text-bold mr-4">{{__('egarden.comment_empathy_function')}}</p>
                    <div class="custom-control mr-4">
                        <input type="radio" class="custom-control-input" id="activation" name='active_empathy'
                            @if(is_null($egarden) || $egarden->active_empathy >0 ) checked @endif value="1">
                        <label class="custom-control-label"
                            for="activation">{{__('egarden.activation')}}</label>
                    </div>
                    <div class="custom-control mr-4">
                        <input type="radio" class="custom-control-input" id="disabled" name='active_empathy'
                            value="0" @if( !is_null($egarden) && $egarden->active_empathy ==0 ) checked @endif>
                        <label class="custom-control-label" for="disabled">{{__('egarden.disabled')}}</label>
                    </div>
                </div>
                <div class=" d-flex flex-wrap custom-checkbox mb-2">
                    <p class="text-bold mr-4">{{__('egarden.do_not_right_click')}}</p>
                    <div class="custom-control mr-4">
                        <input type="radio" class="custom-control-input" id="use" name="right_click"
                            @if(is_null($egarden) || $egarden->right_click >0 ) checked @endif value="1">
                        <label class="custom-control-label" for="use">{{__('egarden.use')}}</label>
                    </div>
                    <div class="custom-control mr-4">
                        <input type="radio" class="custom-control-input" id="notUsed" name="right_click" @if(
                            !is_null($egarden) && $egarden->right_click == 0 ) checked @endif value=0>
                        <label class="custom-control-label" for="notUsed">{{__('egarden.not_used')}}</label>
                    </div>
                    {{-- <span>
                        <span class="required">*</span>
                        {{__('egarden.enabled_warming')}}
                    </span> --}}
                </div>
                <input type="hidden" name="room_id" value="{{$id}}">
                {{-- <div class="text-center">
                    <a href="javascript:{}" class="btn btn-outline temporary" data-toggle="modal"
                        data-target="#confirmPopup2">{{__('egarden.temporary_save')}}</a>
                    <a href="javascript:{}" class="btn btn-outline preview">{{__('egarden.preview')}}</a>
                    <a href="javascript:{}" class="btn btn-primary save" data-toggle="modal"
                        data-target="#confirmPopup2">{{__('egarden.enrollment')}}</a>
                </div> --}}
                <input type="hidden" name="base64Image" id="base64Image" value="{{ $egarden ? get_image_url($egarden->banner) : '' }}">
                {!! Theme::partial('submit_form',[
                    'is_validate_image' => $egarden ? 0 : 1,
                    'route_preview' => route('egardenFE.preview'),
                    'route_back' => route('egardenFE.room.detail',['id'=>$id]),
                    'idPreview' =>  $egarden ? $egarden->id : '',
                    'hint' =>  $egarden->hint ?? '',
                ]) !!}
                </form>
            </div>
        </div>
    </div>
    <script>
$('#categories_gardens_id').on('change',function(){
    $('#categories_gardens_name').val( $("#categories_gardens_id option:selected" ).text()   );
})
    </script>
