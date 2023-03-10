<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
          <div class="category-menu">
            <h4 class="category-menu__title">{{__('event.menu__title')}}</h4>
            <ul class="category-menu__links">
              @foreach ($categories as $item)
              <li class="category-menu__item @if($item->id == $idCategory )  active @endif">
                <a href="{{route('event.event_list', ['idCategory'=>$item->id]) }}" title="{{$item->name }}"  >{{$item->name}}</a>
              </li>
              @endforeach
              <li class="category-menu__item">
                <a href="{{route('event.cmt.list')}}" title="{{__('event.event_comments')}}">{{__('event.event_comments')}}</a>
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
                              <a href="{{ $crumb['url'] }}" title="{!! $crumb['label'] !!}">{!! $crumb['label'] !!}</a>
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
                    <h3 class="form__title"> @if(\Request::route()->getName() == "eventsFE.create"){{__('event.create_events')}} @else {{__('event.edit_event',['id'=>$event->id])}}@endif</h3>
                    <p class="text-right"><span class="required">*</span>{{__('event.required')}}</p>
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
                    <div class="d-sm-flex align-items-center">
                      <span class="text-bold mr-3">{{__('event.event_date')}} <span class="required">*</span></span>
                      <div class="flex-grow-1 mx-3">
                        <div class="d-flex align-items-center">
                          <div class="form-group form-group--search  flex-grow-1 mr-1">
                            <span class="form-control__icon form-control__icon--gray">
                              <svg width="20" height="17" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_calender"></use>
                              </svg>
                            </span>
                            <input data-datepicker-start type="text" class="form-control startDate" id="startDate" name="start" value="{{ $event ? date("Y.m.d", strtotime($event->start))  : getToDate(1) }} " autocomplete="off" required>
                          </div>
                          <span class="filter__connect">~</span>
                          <div class="form-group form-group--search  flex-grow-1 ml-1">
                            <span class="form-control__icon form-control__icon--gray">
                              <svg width="20" height="17" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_calender"></use>
                              </svg>
                            </span>
                            <input data-datepicker-end type="text" class="form-control endDate" id="endDate" name="end" value="{{ $event ? date("Y.m.d", strtotime($event->end))  : getToDate(1) }} " autocomplete="off" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group form-group--1">
                      <label for="durationActivity" class="form-control">
                      <input type=" text" id="durationActivity" placeholder="&nbsp;" name="title" value="{{ $event ? $event->title : '' }}" required>
                        <span class="form-control__label">{{__('event.title')}} <span class="required">*</span></span>
                      </label>
                    </div>
                    <div class="d-sm-flex flex-wrap align-items-center">
                      <div class=" d-flex align-items-center flex-grow-1">
                        <div class="form-group form-group--1 flex-grow-1">
                          <label for="recruiment" class="form-control">
                          <input type="number" id="recruiment" placeholder="&nbsp;" name="enrollment_limit" value="{{ $event ? $event->enrollment_limit : ''}}" required>
                            <span class="form-control__label">{{__('event.enrollment_limit_create')}} <span class="required">*</span></span>
                          </label>
                        </div>
                        <div class="px-2">{{__('event.person')}}</div>
                      </div>
                    </div>
                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                      <p class="text-bold mr-4 pr-2">{{__('event.banner')}} <span class="required">*</span></p>
                      <div class="d-flex flex-wrap flex-grow-1">

                        <div class="input-group-image" style="margin: auto;">
                        {{-- <input type="hidden" class="imagesBase64" name="imagesBase64[1]" value="{{null}}" > --}}
                        <label for="uploadImage" class="form-control form-control--upload" style="height: 30em; width: 28em;max-width: 28em;{{$event ? 'background-color : white; background-position : center;' :''  }} background-size: 40em; background-image: url('{{ $event ? get_image_url($event->banner) : ''  }}') ">
                            <input type="file" class="" id="uploadImage" accept="image/gif, image/jpeg, image/png" data-add-image name="image" value='{{$event ? $event->banner: '' }}' >
                            <span class="form-control__label">
                              <svg width="30" height="30" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big"></use>
                              </svg>
                            </span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="d-sm-flex">
                        <span class="text-bold mr-4 pr-2">{{__('event.content')}} <span class="required">*</span></span>
                        <div class="flex-grow-1" style="max-width: 749.53px;">
                          <textarea  class="ckeditor" name="content" required id="ckeditor">{{ $event ? $event->content : ''}}</textarea >
                            <script>
                              CKEDITOR.replace( 'content', {
                                  filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                  filebrowserUploadMethod: 'form'
                              });
                            </script>
                        </div>
                    </div>
                    <div style="margin: 20px 0">
                        {!! Theme::partial('upload_link_file',['link'=> $event ? $event->link : '',
                         'file_upload' => $event ? $event->file_upload : '' ]) !!}
                    </div>
                    <input type="hidden" name="category_events_id" value="{{$idCategory}}">
                    {{-- <div class="d-sm-flex" style="margin-top: 20px;">
                        <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('event.status')}} <span class="required">*</span></span>
                        <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset($event) && $event->status == "publish" ) selected @endif>{{__('event.publish')}}</option>
                                    <option value="draft" @if(isset($event) && $event->status == "draft" ) selected @endif>{{__('event.draft')}}</option>
                                </select>
                        </div>
                    </div> --}}
                    <input type="hidden" name="base64Image" id="base64Image" value="{{ $event ? get_image_url($event->banner) : '' }}">
                    {!! Theme::partial('submit_form',[
                        'is_validate_image' => $event ? 0 : 1,
                        'route_preview' => route('eventsFE.preview'),
                        'route_back' => route('event.event_list', ['idCategory' => $idCategory]),
                        'idPreview' =>  $event ? $event->id : '',
                    ]) !!}
                    {{-- <div class="text-center" style="margin-top: 20px;">
                        @if(\Request::route()->getName() == "eventsFE.create")
                            <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('event.save')}}</a>
                        @else
                            <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('event.update')}}</a>
                        @endif
                    </div> --}}
            </div>
        </form>
        </div>

      </div>
    </div>
  </main>
