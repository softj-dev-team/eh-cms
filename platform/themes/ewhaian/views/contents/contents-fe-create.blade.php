<main id="main-content" data-view="home" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="sidebar-template__control">
        <!-- category menu -->
        <div class="category-menu">
          <h4 class="category-menu__title">{{__('contents')}}</h4>
          <ul class="category-menu__links">
          @foreach ($categories as $item)
              <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
                <a href="{{route('contents.contents_list', ['idCategory'=>$item->id]) }}"
                   title="$item->name">{{$item->name}}</a>
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
        <form action="" method="post" id="my_form" enctype="multipart/form-data">
          @csrf
          <div class="form form--border">
            <h3
              class="form__title"> @if(\Request::route()->getName() == "contentsFE.create") {{__('contents.create_contents')}} @else {{__('contents.edit_contents')}}
              #{{$contents->id}}@endif</h3>
            <p class="text-right"><span class="required">*</span>{{__('contents.required')}}</p>
            @if ($errors->any())
              <div class="alert alert-danger" style="display: block">
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
                <input type=" text" id="durationActivity" placeholder="&nbsp;" name="title"
                       value="{{ $contents ? $contents->title : '' }}" required>
                <span class="form-control__label">{{__('contents.title')}} <span class="required">*</span></span>
              </label>
            </div>

            <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
              <p class="text-bold mr-4 pr-2">{{__('contents.banner')}} <span class="required">*</span></p>
              <div class="d-flex flex-wrap flex-grow-1">

                <div class="input-group-image" style="margin: auto;">
                  <label for="uploadImage" class="form-control form-control--upload"
                         style="height: 30em; width: 28em;max-width: 28em;{{$contents ? 'background-color : white; background-position : center;' :''  }} background-size: 40em; background-image: url('{{ $contents ? get_image_url($contents->banner) : ''  }}') ">
                    <input type="file" class="" id="uploadImage" accept="image/gif, image/jpeg, image/png"
                           data-add-image name="image" value='{{$contents ? $contents->banner: '' }}'>
                    <span class="form-control__label">
                              <svg width="30" height="30" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big"></use>
                              </svg>
                            </span>
                  </label>
                </div>
              </div>
            </div>

            {{-- <div class="form-group form-group--1">
              <label for="description" class="form-control">
                <input type=" text" id="description" placeholder="&nbsp;" name="description"
                       value="{{ $contents ? $contents->description : '' }}" required>
                <span class="form-control__label">{{__('contents.description')}} <span class="required">*</span></span>
              </label>
            </div> --}}


{{--            <div class="d-sm-flex mb-2">--}}
{{--              <span class="text-bold" style="margin-right: 51px">{{__('contents.description')}}</span>--}}
{{--              <div class="flex-grow-1" style="max-width: 749.53px;">--}}
{{--                <textarea class="ckeditor" name="description"--}}
{{--                          id="description">{{ $contents ? $contents->description : ''}}</textarea>--}}
{{--                <script>--}}
{{--                  CKEDITOR.replace( 'description', {--}}
{{--                      filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",--}}
{{--                      filebrowserUploadMethod: 'form'--}}
{{--                  });--}}
{{--                </script>--}}
{{--              </div>--}}
{{--            </div>--}}

            <div class="d-sm-flex">
              <span class="text-bold mr-4 pr-2">{{__('contents.description')}} <span class="required">*</span></span>
              <div class="flex-grow-1" style="max-width: 749.53px;">
                <textarea class="ckeditor" name="content"
                          id="content">{{ $contents ? $contents->content : ''}}</textarea>
                <script>
                  CKEDITOR.replace( 'content', {
                      filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                      filebrowserUploadMethod: 'form'
                  });
                </script>
              </div>
            </div>
            <div style="margin: 20px 0">
              {!! Theme::partial('upload_link_file',['link'=> $contents ? $contents->link : '',
               'file_upload' => $contents ? $contents->file_upload : '' ]) !!}
            </div>
            <input type="hidden" name="categories_contents_id" value="{{$idCategory}}">
            <input type="hidden" name="base64Image" id="base64Image"
                   value="{{ $contents ? get_image_url($contents->banner) : '' }}">
            {!! Theme::partial('submit_form',[
                'is_validate_image' => $contents ? 0 : 1,
                'route_preview' => route('contentsFE.preview'),
                'route_back' => route('contents.contents_list',['contents' => null, 'categories' => $categories,'idCategory'=>$idCategory]),
                'idPreview' =>  $contents ? $contents->id : '',
            ]) !!}
            {{-- <div class="d-sm-flex" style="margin-top: 20px;">
                <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('contents.status')}} <span class="required">*</span></span>
                <div class="flex-grow-1">
                        <select class="form-control form-control--select mx-3" name="status">
                            <option value="publish" @if(isset($contents) && $contents->status == "publish" ) selected @endif>{{__('contents.publish')}}</option>
                            <option value="draft" @if(isset($contents) && $contents->status == "draft" ) selected @endif>{{__('contents.draft')}}</option>
                        </select>
                </div>
            </div> --}}
            {{-- <div class="text-center" style="margin-top: 20px;">
                @if(\Request::route()->getName() == "contentsFE.create")
                    <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('contents.save')}}</a>
                @else
                    <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('contents.update')}}</a>
                @endif


            </div> --}}
          </div>
        </form>
      </div>

    </div>
  </div>
</main>
