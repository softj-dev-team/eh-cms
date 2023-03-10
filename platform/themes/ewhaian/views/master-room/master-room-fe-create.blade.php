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
            <h4 class="category-menu__title">{{__('master_room')}}</h4>
            <ul class="category-menu__links">
              @foreach ($categories as $item)
              <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
                <a href="{{route('masterRoomFE.list', ['idCategory'=>$item->id]) }}"
                  title="{{$item->name}}">{{$item->name}}</a>
              </li>
              @endforeach
              <li class="category-menu__item">
                <a href="{{route('masterRoomFE.address.list')}}"
                  title="{{__('master_room.address')}}">{{__('master_room.address')}}</a>
            </li>
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
        @if(\Request::route()->getName() == "masterRoomFE.create" || \Request::route()->getName() == "masterRoomFE.reply.create")
         <form action="{{route('masterRoomFE.create',['idCategory'=>$idCategory])}}" method="post" id="my_form" enctype="multipart/form-data">
        @else
          <form action="{{route('masterRoomFE.edit',['id'=>$masterRoom->id])}}" method="post" id="my_form" enctype="multipart/form-data">
        @endif
          @if(\Request::route()->getName() == "masterRoomFE.reply.create" || \Request::route()->getName() == 'masterRoomFE.reply.edit')
            <input type="hidden" name="master_room_id" value="{{ $masterRoomId }}">
          @endif
             @csrf
            <div class="form form--border">
                    <h3 class="form__title">
                      @if(\Request::route()->getName() == "masterRoomFE.create")
                        {{__('master_room.create_master_room')}}
                      @elseif(\Request::route()->getName() == "masterRoomFE.reply.create")
                        {{__('master_room.create_master_room_reply')}}
                      @else {{__('master_room.edit_master_room')}} #{{$masterRoom->id}} @endif </h3>
                    <p class="text-right"><span class="required">*</span>{{__('master_room.required')}}</p>
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
                      <input type=" text" id="durationActivity" placeholder="&nbsp;" name="title" value="{{ $masterRoom ? $masterRoom->title : '' }}" required>
                        <span class="form-control__label">
                          {{__('master_room.title')}}
                          <span class="required">*</span></span>
                      </label>
                    </div>
                    {{-- <div class="d-sm-flex flex-wrap align-items-center">
                      <div class=" d-flex align-items-center flex-grow-1">
                        <div class="form-group form-group--1 flex-grow-1">
                          <label for="recruiment" class="form-control">
                          <input type="number" id="recruiment" placeholder="&nbsp;" name="enrollment_limit" value="{{ $masterRoom ? $masterRoom->enrollment_limit : 0 }}">
                            <span class="form-control__label">{{__('master_room.enrollment_limit')}} </span>
                          </label>
                        </div>
                        <div class="px-2">{{__('master_room.person')}}</div>
                      </div>
                    </div> --}}
                    {{-- <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                      <p class="text-bold mr-4 pr-2">{{__('master_room.banner')}}</p>
                      <div class="d-flex flex-wrap flex-grow-1">

                        <div class="input-group-image" style="margin: auto;">
                        <label for="uploadFile0" class="form-control form-control--upload" style="height: 30em; width: 28em;max-width: 28em;{{$masterRoom ? 'background-color : white; background-position : center;' :''  }} ; background-image: url('{{ $masterRoom ? get_image_url($masterRoom->banner) : ''  }}') ">
                            <input type="file" class="" id="uploadFile0" accept="image/gif, image/jpeg, image/png" data-add-image name="image" value='{{$masterRoom ? $masterRoom->banner: '' }}' >
                            <span class="form-control__label">
                              <svg width="30" height="30" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big"></use>
                              </svg>
                            </span>
                          </label>
                        </div>
                      </div>
                    </div> --}}
                    {{-- <div class="form-group form-group--1">
                      <label for="description" class="form-control">
                      <input type=" text" id="description" placeholder="&nbsp;" name="description" value="{{ $masterRoom ? $masterRoom->description : '' }}">
                        <span class="form-control__label">{{__('master_room.description')}} </span>
                      </label>
                    </div> --}}
                    {{-- <div class="form-group form-group--1">
                      <label for="notice" class="form-control">
                      <input type=" text" id="notice" placeholder="&nbsp;" name="notice" value="{{ $masterRoom ? $masterRoom->notice : '' }}">
                        <span class="form-control__label">{{__('master_room.notice')}} </span>
                      </label>
                    </div> --}}
                    <div class="d-sm-flex">
                        <div class="text-bold mr-4 pr-2" style="display: flex;">{{__('master_room.contents')}} <span class="required"> *</span></div>
                        <div class="flex-grow-1" style="max-width: 724.19px;">
                          <textarea  class="ckeditor" name="content" required>
                            @if($masterRoomParent && \Request::route()->getName() == "masterRoomFE.reply.create")
                              <br>
                              --------------------------------------------------
                              {{ $masterRoomParent->content }}
                            @else
                            {{ $masterRoom ? $masterRoom->content : ''}}
                            @endif
                          </textarea >
                          <script>
                            CKEDITOR.replace( 'content', {
                                filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                filebrowserUploadMethod: 'form'
                            });
                          </script>
                        </div>
                    </div>
                    {{-- <div class="d-sm-flex" style="margin-top: 20px;">
                            <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('master_room.categories')}} <span class="required">*</span></span>
                        <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="categories_master_rooms_id">
                                    @foreach ($categories as $item)
                                        @if(is_null($masterRoom))
                                            <option value="{{$item->id}}" @if($item->id == $id) selected @endif >{{$item->name}}</option>
                                        @else
                                            <option value="{{$item->id}}" @if( $masterRoom->categories_master_rooms_id == $item->id ) selected @endif >{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                        </div>
                    </div> --}}
                    <div style="margin: 20px 0">
                        {!! Theme::partial('upload_link_file',['link'=> $masterRoom ? $masterRoom->link : '',
                         'file_upload' => $masterRoom ? $masterRoom->file_upload : '' ]) !!}
                    </div>
                    <input type="hidden" name="categories_master_rooms_id" value="{{$idCategory}}">
{{--                    <div class="d-sm-flex" style="margin-top: 20px;">--}}
{{--                        <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('master_room.status')}} <span class="required">*</span></span>--}}
{{--                        <div class="flex-grow-1">--}}
{{--                                <select class="form-control form-control--select mx-3" name="status">--}}
{{--                                    <option value="publish" @if(isset($masterRoom) && $masterRoom->status == "publish" ) selected @endif>{{__('master_room.publish')}}</option>--}}
{{--                                    <option value="draft" @if(isset($masterRoom) && $masterRoom->status == "draft" ) selected @endif>{{__('master_room.draft')}}</option>--}}
{{--                                </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="text-center" style="margin-top: 20px;">
                      @if(\Request::route()->getName() == "masterRoomFE.create" || \Request::route()->getName() == "masterRoomFE.reply.create")
                            <input type="submit" class="btn btn-primary submit_form" value="{{__('master_room.save')}}" >
                        @else
                        <input type="submit" class="btn btn-primary submit_form" value="{{__('master_room.update')}}" >
                        @endif

                    </div>
            </div>
        </form>
        </div>

      </div>
    </div>
  </main>
<script>
    $(document).ready(function(){
        $('#my_form').on('submit', function (e) {
            e.preventDefault();

            let content = CKEDITOR.instances['content'];
            if (content && content.getData() == '') {
                alert('내용을 입력하세요.');
                content.focus();
                return;
            }
            e.currentTarget.submit();
        });
    });

</script>
