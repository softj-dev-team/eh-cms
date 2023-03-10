<style>
.input-group-image .form-control--upload {
    height: 28em;
    width: 28em;
    max-width: 28em;
    background-size: 28em;
}
@media  screen and (max-width : 480px){
    .input-group-image .form-control--upload{
        height: 20em;
        width: 20em ;
        max-width: 20em;
        background-size: 20em ;
    }
}
@media  screen and (max-width : 360px){
    .input-group-image .form-control--upload{
        height: 15em;
        width: 15em ;
        max-width: 15em;
        background-size: 15em ;
    }
}
</style>
<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
          <div class="category-menu">
            <h4 class="category-menu__title">{{__('new_contents')}}</h4>
            <ul class="category-menu__links">
              @foreach ($categories as $item)
              <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
                <a href="{{route('newContentsFE.list', ['idCategory'=>$item->id]) }}"
                  title="{{$item->name}}">{{$item->name}}</a>
              </li>
              @endforeach
            </ul>
          </div>
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
        @if(\Request::route()->getName() == "newContentsFE.create")
         <form action="{{route('newContentsFE.create',['idCategory'=>$idCategory])}}" method="post" id="my_form" enctype="multipart/form-data">
        @else
        <form action="{{route('newContentsFE.edit',['id'=>$newContents->id])}}" method="post" id="my_form" enctype="multipart/form-data">
        @endif
             @csrf
            <div class="form form--border">
                    <h3 class="form__title"> @if(\Request::route()->getName() == "newContentsFE.create") {{__('new_contents.create_new_contents')}} @else {{__('new_contents.edit_new_contents')}} #{{$newContents->id}} @endif </h3>
                    <p class="text-right"><span class="required">*</span>{{__('new_contents.required')}}</p>
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
                    <div class="alert alert-danger" style="display: block">
                      <p>{{ session('err') }}</p>
                    </div>
                    @endif
                    @if (session('success'))
                    <div class="alert alert-success" style="display: block">
                      <p>{{ session('success') }}</p>
                    </div>
                    @endif
                    <div class="form-group form-group--1">
                      <label for="durationActivity" class="form-control">
                      <input type=" text" id="durationActivity" placeholder="&nbsp;" name="title" value="{{ $newContents ? $newContents->title : '' }}">
                        <span class="form-control__label">{{__('new_contents.title')}} <span class="required">*</span></span>
                      </label>
                    </div>
{{--                    <div class="d-sm-flex flex-wrap align-items-center">--}}
{{--                      <div class=" d-flex align-items-center flex-grow-1">--}}
{{--                        <div class="form-group form-group--1 flex-grow-1">--}}
{{--                          <label for="recruiment" class="form-control">--}}
{{--                          <input type="number" id="recruiment" placeholder="&nbsp;" name="enrollment_limit" value="{{ $newContents ? $newContents->enrollment_limit : ''}}">--}}
{{--                            <span class="form-control__label">{{__('new_contents.enrollment_limit')}} <span class="required">*</span></span>--}}
{{--                          </label>--}}
{{--                        </div>--}}
{{--                        <div class="px-2">{{__('new_contents.person')}}</div>--}}
{{--                      </div>--}}
{{--                    </div>--}}
                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                      <p class="text-bold mr-4 pr-2">{{__('new_contents.banner')}}<span class="required">*</span></p>
                      <div class="d-flex flex-wrap flex-grow-1">

                        <div class="input-group-image" style="margin: auto;">
                        <label for="uploadFile0" class="form-control form-control--upload" style="height: 30em; width: 28em;max-width: 28em;{{$newContents ? 'background-color : white; background-position : center;' :''  }} ; background-image: url('{{ $newContents ? get_image_url($newContents->banner) : ''  }}') ">
                            <input type="file" class="" id="uploadFile0" accept="image/gif, image/jpeg, image/png" data-add-image name="image" value='{{$newContents ? $newContents->banner: '' }}' >
                            <span class="form-control__label">
                              <svg width="30" height="30" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big"></use>
                              </svg>
                            </span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group form-group--1">
                        <div class="text-bold mr-4 pr-2" style="margin-bottom: 10px;">{{__('new_contents.description')}} <span class="required"> *</span></div>
                        <div class="flex-grow-1" style="/*max-width: 724.19px;*/">
                          <textarea  class="ckeditor" name="content">{{ $newContents ? $newContents->content : ''}}</textarea >
                          <script>
                            CKEDITOR.replace( 'content', {
                                filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                filebrowserUploadMethod: 'form'
                            });
                          </script>
                        </div>
                    </div>
                    {{-- <div class="d-sm-flex" style="margin-top: 20px;">
                            <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('new_contents.categories')}} <span class="required">*</span></span>
                        <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="categories_new_contents_id">
                                    @foreach ($categories as $item)
                                        @if(is_null($newContents))
                                            <option value="{{$item->id}}" @if($item->id == $id) selected @endif >{{$item->name}}</option>
                                        @else
                                            <option value="{{$item->id}}" @if( $newContents->categories_new_contents_id == $item->id ) selected @endif >{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                        </div>
                    </div> --}}
                    <div style="margin: 20px 0">
                        {!! Theme::partial('upload_link_file',['link'=> $newContents ? $newContents->link : '',
                         'file_upload' => $newContents ? $newContents->file_upload : '' ]) !!}
                    </div>
                    <input type="hidden" name="categories_new_contents_id" value="{{$idCategory}}">
                    <div class="d-sm-flex" style="margin-top: 20px;">
                        <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('new_contents.status')}} <span class="required">*</span></span>
                        <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset($newContents) && $newContents->status == "publish" ) selected @endif>{{__('new_contents.publish')}}</option>
                                    <option value="draft" @if(isset($newContents) && $newContents->status == "draft" ) selected @endif>{{__('new_contents.draft')}}</option>
                                </select>
                        </div>
                    </div>
                    <div class="text-center" style="margin-top: 20px;">
                        @if(\Request::route()->getName() == "newContentsFE.create")
                            <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('new_contents.save')}}</a>
                        @else
                            <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('new_contents.update')}}</a>
                        @endif


                    </div>
            </div>
        </form>
        </div>

      </div>
    </div>
  </main>
