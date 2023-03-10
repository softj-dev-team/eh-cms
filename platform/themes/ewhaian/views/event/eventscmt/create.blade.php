<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->
                <div class="category-menu">
                    <h4 class="category-menu__title">{{__('event.event_comments')}}</h4>
                    <ul class="category-menu__links">
                        @foreach ($categories as $item)
                        <li class="category-menu__item">
                            <a href="{{route('event.event_list', ['idCategory'=>$item->id]) }}"
                                title="{{$item->name}}">{{$item->name}}</a>
                        </li>
                        @endforeach
                        <li class="category-menu__item active">
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
                @if(\Request::route()->getName() == "eventsFE.cmt.create")
                    action="{{route('eventsFE.cmt.create')}}"
                @else
                    action="{{route('eventsFE.cmt.edit',['id'=>$event->id] )}}"
                @endif
                method="post" id="my_form"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form form--border">
                        <h3 class="form__title"> @if(\Request::route()->getName() == "eventsFE.cmt.create") {{__('event.event_comments.create_event_comments')}} @else {{__('event.event_comments.edit_event_comments')}} #{{$event->id}}@endif</h3>
                        <p class="text-right"><span class="required">*</span>{{__('event.event_comments.required')}}</p>
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
                        <div class="alert alert-danger">
                            <p>{{ session('err') }}</p>
                        </div>
                        @endif

                        @if (session('success'))
                        <div class="alert alert-success">
                            <p>{{ session('success') }}</p>
                        </div>
                        @endif
                        <div class="form-group form-group--1">
                            <label for="durationActivity" class="form-control">
                                <input type=" text" id="durationActivity" placeholder="&nbsp;" name="title"
                                    value="{{ $event ? $event->title : '' }}" required >
                                <span class="form-control__label">{{__('event.event_comments.title')}} <span class="required">*</span></span>
                            </label>
                        </div>
                        <div class="d-sm-flex">
                            <span class="text-bold mr-4 pr-2">{{__('event.event_comments.detail')}} <span class="required">*</span></span>
                            <div class="flex-grow-1" style="max-width: 749.53px;">
                                <textarea class="ckeditor" name="detail" id="content" >{{ $event ? $event->detail : ''}}</textarea>
                                <script>
                                  CKEDITOR.replace( 'detail', {
                                      filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                      filebrowserUploadMethod: 'form'
                                  });
                                </script>
                            </div>
                        </div>
                        <div class="d-sm-flex" style="margin-top: 20px;">
                            <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('event.event_comments.categories')}} <span
                                    class="required">*</span></span>
                            <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="category_events_id">
                                    @foreach ($categories as $item)
                                    @if( $item->id != 5)
                                        <option value="{{$item->id}}" @if(isset($event) && $event->category_events_id ==
                                            $item->id ) selected @endif >{{$item->name}}</option>
                                    @endif

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div style="margin: 20px 0">
                            {!! Theme::partial('upload_link_file',['link'=> $event ? $event->link : '',
                             'file_upload' => $event ? $event->file_upload : '' ]) !!}
                        </div>
                        {!! Theme::partial('submit_form',[
                            'is_validate_image' => $event ? 0 : 1,
                            'route_preview' => route('eventsFE.cmt.preview'),
                            'route_back' => route('event.cmt.list'),
                            'idPreview' =>  $event ? $event->id : '',
                        ]) !!}
                        {{-- <div class="d-sm-flex" style="margin-top: 20px;">
                            <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('event.event_comments.status')}} <span
                                    class="required">*</span></span>
                            <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset($event) && $event->status == "publish" ) selected
                                        @endif>{{__('event.event_comments.publish')}}</option>
                                    <option value="draft" @if(isset($event) && $event->status == "draft" ) selected
                                        @endif>{{__('event.event_comments.draft')}}</option>
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="text-center" style="margin-top: 20px;">
                            @if(\Request::route()->getName() == "eventsFE.cmt.create")
                            <a href="javascript:{}" class="btn btn-primary"
                                onclick="document.getElementById('my_form').submit();">{{__('event.event_comments.save')}}</a>
                            @else
                            <a href="javascript:{}" class="btn btn-primary"
                                onclick="document.getElementById('my_form').submit();">{{__('event.event_comments.update')}}</a>
                            @endif


                        </div> --}}
                    </div>
                </form>
            </div>

        </div>
    </div>
</main>
