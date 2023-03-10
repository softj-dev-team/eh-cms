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
{!! Theme::partial('confirm_leave') !!}
<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->
                {!! Theme::partial('life.menu',['active'=>"open_space"]) !!}
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
                <form
                @if(\Request::route()->getName() == "openSpaceFE.create")
                    action="{{route('openSpaceFE.create')}}"
                @else
                    action="{{route('openSpaceFE.edit',['id'=> $openSpace->id] )}}"
                @endif
                method="post" id="my_form"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form form--border">
                        <h3 class="form__title"> @if(\Request::route()->getName() == "openSpaceFE.create"){{__('life.open_space.create_open_space')}}@else {{__('life.open_space.edit_open_space')}}@endif</h3>
                        <p class="text-right"><span class="required">*</span>{{__('life.open_space.required')}}</p>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if (session('err'))
                        <div class="alert alert-danger">
                            <p>{{ session('err') }}</p>
                        </div>
                        @endif

                        @if (session('success'))
                        <div class="alert alert-success">
                            <p>{{ session('success') }}</p>
                        </div>
                        @endif
                        {{-- <div class="form-group form-group--1">
                            <label for="durationActivity" class="form-control">
                                <input type=" text" id="durationActivity" placeholder="&nbsp;" name="title"
                                    value="{{  $openSpace ?  $openSpace->title : '' }}" required>
                                <span class="form-control__label">{{__('life.open_space.title')}} <span class="required">*</span></span>
                            </label>
                        </div> --}}



                        <div class="form-group form-group--1 d-sm-flex flex-wrap ">
                          <span class="text-bold pt-4">{{__('life.open_space.title')}}</span><span class="required pt-4 mr-4 pr-2">* </span>

                          <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">

                            <label for="durationActivity" class="form-control" style="line-height:2.5em">
                              <input type=" text" id="durationActivity" placeholder="&nbsp;" name="title"
                              value="{{  $openSpace ?  $openSpace->title : '' }}" {{--required--}}>
                              {{-- <span class="form-control__label">{{__('life.open_space.title')}} <span class="required">*</span></span>   --}}
                            </label>
                          </div>
                        </div>

                        <div class="d-sm-flex">
                            <span class="text-bold mr-4 pr-2">{{__('life.open_space.details')}}<span class="required">*</span></span>
                            <div class="flex-grow-1" style="max-width: 749.53px;">
                                <textarea class="ckeditor" name="detail" id="content">{{  $openSpace ?  $openSpace->detail : ''}}</textarea>
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
                            <div id="box-group-image" class="d-flex flex-wrap flex-grow-1">
                                <div class="input-group-image">
                                    <input type="hidden" class="imagesValue" name="imagesValue[]" value="" >
                                    <input type="hidden" class="imagesBase64" name="imagesBase64[]" value="" >
                                    <a class="btn_remove_image" title="{{__('campus.study_room.remove_image')}}" @if( !isset($openSpace->images) )  style="display : none" @endif><i class="fa fa-times"></i></a>
                                    <label class="form-control form-control--upload">
                                        <input type="file" name="images[]"
                                            accept="image/gif, image/jpeg, image/png" data-add-image>
                                        <span class="form-control__label">
                                            <svg width="30" height="30" aria-hidden="true" class="icon">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                                </use>
                                            </svg>
                                        </span>
                                    </label>
                                </div>
                                @if ($openSpace && $openSpace->images)
                                    @foreach ($openSpace->images as $key => $image)
                                        @if(!is_null($image))
                                            <div class="input-group-image">
                                                <input type="hidden" class="imagesValue" name="imagesValue[]" value="{{ $image ??  null}}" >
                                                <input type="hidden" class="imagesBase64" name="imagesBase64[]" value="{{ '/' . $image }}" >
                                                <a class="btn_remove_image" title="{{__('campus.study_room.remove_image')}}"><i class="fa fa-times"></i></a>
                                                <label for="uploadFile{{ $key }}" class="form-control form-control--upload"  style="background-color : white; background-position : center; background-image: url('{{ get_image_url($image,'thumb') }}')">
                                                    <input type="file" name="images[]" class="" id="uploadFile{{ $key }}"
                                                           accept="image/gif, image/jpeg, image/png" data-add-image>
                                                    <span class="form-control__label">
                                                    <svg width="30" height="30" aria-hidden="true" class="icon">
                                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big">
                                                        </use>
                                                    </svg>
                                                </span>
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="d-sm-flex" style="margin-top: 20px;">
                            <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">분류<span
                                    class="required">*</span></span>
                            <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="categories_id" required>
                                    <option value="0" @if(isset( $openSpace) &&  $openSpace->categories_id == "0" ) selected
                                        @endif>공동구매</option>
                                    <option value="1" @if(isset( $openSpace) &&  $openSpace->categories_id == "1" ) selected
                                        @endif>분실</option>
                                    <option value="2" @if(isset( $openSpace) &&  $openSpace->categories_id == "2" ) selected
                                        @endif>기타</option>
                                </select>
                            </div>
                        </div>
                        <div style="margin: 20px 0">
                            {!! Theme::partial('upload_link_file',['link'=> $openSpace ? $openSpace->link : '',
                             'file_upload' => $openSpace ? $openSpace->file_upload : '' ]) !!}
                        </div>




                        {{-- <div class="d-sm-flex" style="margin-top: 20px;">
                            <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('life.open_space.status')}} <span
                                    class="required">*</span></span>
                            <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset( $openSpace) &&  $openSpace->status == "publish" ) selected
                                        @endif>{{__('life.open_space.publish')}}</option>
                                    <option value="draft" @if(isset( $openSpace) &&  $openSpace->status == "draft" ) selected
                                        @endif>{{__('life.open_space.draft')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-center" style="margin-top: 20px;">
                            @if(\Request::route()->getName() == "openSpaceFE.create")
                            <a href="javascript:{}" class="btn btn-primary"
                                onclick="document.getElementById('my_form').submit();">{{__('life.open_space.save')}}</a>
                            @else
                            <a href="javascript:{}" class="btn btn-primary"
                                onclick="document.getElementById('my_form').submit();">{{__('life.open_space.update')}}</a>
                            @endif


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
{{--                    {!! Theme::partial('right_mouse_function',['item' => $openSpace ?? null]) !!}--}}
                    {!! Theme::partial('submit_form',[
                        'is_validate_image' => 0,
                        'route_preview' => route('openSpaceFE.preview'),
                        'route_back' => route('life.open_space_list'),
                        'idPreview' =>  $openSpace ? $openSpace->id : '',
                    ]) !!}
                </form>
            </div>
        </div>
    </div>
</main>
