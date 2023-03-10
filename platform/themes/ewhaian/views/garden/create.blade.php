<main id="main-content" data-view="advertisement-enrollment" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>$selectCategories->id]) !!}
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
                        {{$selectCategories->name}}
                    </div>
                    <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                        {!!$description->description ?? ""!!}
                    </div>
                </div>
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
                @if ($errors->any())
                <div class="alert alert-danger" style="display: block">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @isset($garden)
                    <script>
                    $(document).ready(function(){
                        if (!"{{request()->without_popup}}"){
                            if (confirm('작성 중인 게시글 이 있습니다. 불러올까요?')){
                                window.location.href = '/garden/create/' + {{ $selectCategories->id }} + '?nondraft=1';
                            }
                        }
                    });
                    </script>
                @endif

                <div class="form form--border pb-1 pt-2 align-items-center mb-4">
                    <form id="my_form" method="POST" autocomplete="off"
                          action="@if( is_null($garden)) {{route('gardenFE.create',['id'=>$selectCategories->id])}} @else {{route('gardenFE.edit',['id'=>$garden->id])}} @endif "
                        enctype="multipart/form-data">
                        @csrf

                        <div class="text-bold mr-4 pr-2" style="margin-top: 10px; margin-bottom:10px">
                            {{__('garden.title')}} <span class="required">*</span></div>

                        <div class="form-group form-group--search flex-grow-1">
                            <input type="text" class="form-control" placeholder="{{__('garden.enter_title_for_create')}}"
                                name="title" value="{{$garden->title ?? ''}}" required />
                        </div>


                        <div>
                            <div class="text-bold mr-4 pr-2" style="margin-top: 10px; margin-bottom:10px">
                                {{__('garden.detail')}} <span class="required">*</span></div>
                            <textarea class="ckeditor" name="detail" id="content">{{$garden->detail ?? ''}}</textarea>
                            <script>
                              CKEDITOR.replace( 'detail', {
                                  filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                                  filebrowserUploadMethod: 'form'
                              });
                            </script>
                        </div>
                        <div style="margin: 20px 0">
                            {!! Theme::partial('upload_link_file',['link'=> $garden ? $garden->link : '',
                            'file_upload' => $garden ? $garden->file_upload : '' ]) !!}
                        </div>
                        <div class="" style="position: absolute; z-index: -1">
                          <input type="text" name="off_autofill"  placeholder="">
                        </div>
                        {{-- <div class="d-sm-flex" style="margin-top: 20px;">
                            <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('garden.status')}} <span
                                    class="required">*</span></span>
                            <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset($garden) && $garden->status == "publish" )
                                        selected @endif>{{__('garden.publish')}}</option>
                                    <option value="draft" @if(isset($garden) && $garden->status == "draft" ) selected
                                        @endif>{{__('garden.draft')}}</option>
                                </select>
                            </div>
                        </div> --}}

                        {{-- @if(Route::current()->getName() == 'gardenFE.edit')
            <div class="d-sm-flex" style="margin-top: 20px;">
                    <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('garden.belongs_to')}}<span
                            class="required">*</span></span>
                        <input type="hidden" name="categories_gardens_name" id="categories_gardens_name"
                            value="{{$selectCategories->name}}">
                        <div class="flex-grow-1">
                            <select class="form-control form-control--select mx-3" name="categories_gardens_id"
                                id="categories_gardens_id">
                                @foreach ($categories as $item)
                                <option value="{{$item->id}}" @if(isset($garden) && $garden->categories_gardens_id ==
                                    $item->id ) selected @endif>{{$item->name}}</option>
                                @endforeach

                            </select>
                        </div>
                </div>
                @endif --}}
            </div>

            <div class="form--border border-0">
              <input type="hidden" name="categories_gardens_name" id="categories_gardens_name"
                      value="{{$selectCategories->name}}">
              <input type="hidden" name="categories_gardens_id" value="{{$selectCategories->id}}">
              <div class="d-flex flex-wrap custom-checkbox mb-2 mt-5">
                  <p class="text-bold mr-4">{{__('garden.comment_empathy_function')}}</p>
                  <div class="custom-control mr-4">
                      <input type="checkbox" class="custom-control-input" id="activation" name='active_empathy'
                          @if(is_null($garden) || $garden->active_empathy >0 ) checked @endif value="1">
                      <label class="custom-control-label" for="activation">{{__('garden.activation')}}</label>
                  </div>
{{--                  <div class="custom-control mr-4">--}}
{{--                      <input type="radio" class="custom-control-input" id="disabled" name='active_empathy' value="0"--}}
{{--                          @if( !is_null($garden) && $garden->active_empathy ==0 ) checked @endif>--}}
{{--                      <label class="custom-control-label" for="disabled">{{__('garden.disabled')}}</label>--}}
{{--                  </div>--}}
              </div>

              <!-- remove -->
              <!-- <div class=" d-flex flex-wrap custom-checkbox mb-2">
                  <p class="text-bold mr-4">{{__('garden.do_not_right_click')}}</p>
                  <div class="custom-control mr-4">
                      <input type="radio" class="custom-control-input" id="use" name="right_click"
                          @if(is_null($garden) || $garden->right_click >0 ) checked @endif value="1" required>
                      <label class="custom-control-label" for="use">{{__('garden.use')}}</label>
                  </div>
                  <div class="custom-control mr-4">
                      <input type="radio" class="custom-control-input" id="notUsed" name="right_click" @if(
                          !is_null($garden) && $garden->right_click == 0 ) checked @endif value=0>
                      <label class="custom-control-label" for="notUsed">{{__('garden.not_used')}}</label>
                  </div>
              </div> -->
{{--              <div class=" d-flex flex-wrap custom-checkbox mb-2">--}}
{{--                  <p class="text-bold mr-4">반응 가능</p>--}}
{{--                  <div class="custom-control mr-4">--}}
{{--                      <input type="radio" class="custom-control-input" id="useReaction" name="can_reaction"--}}
{{--                          @if(is_null($garden) || $garden->can_reaction >0 ) checked @endif value="1" required>--}}
{{--                      <label class="custom-control-label" for="useReaction">{{__('garden.use')}}</label>--}}
{{--                  </div>--}}
{{--                  <div class="custom-control mr-4">--}}
{{--                      <input type="radio" class="custom-control-input" id="notUsedReaction" name="can_reaction" @if(--}}
{{--                          !is_null($garden) && $garden->can_reaction == 0 ) checked @endif value=0>--}}
{{--                      <label class="custom-control-label" for="notUsedReaction">{{__('garden.not_used')}}</label>--}}
{{--                  </div>--}}
{{--              </div>--}}

              @if(!isset($garden) || (isset($garden) && !is_null($garden->pwd_post) || isset($garden) && $garden->status == \Botble\Base\Enums\BaseStatusEnum::DRAFT ))
              <div class=" d-flex flex-wrap custom-checkbox mb-2">
                  <p class="text-bold mr-4">{{__('garden.is_pwd_post')}}</p>
                  <div class="custom-control mr-4">
                      <input type="checkbox" class="custom-control-input" id="is_pwd_post" name="is_pwd_post"
                          @if(isset($garden) && !is_null($garden->pwd_post) ) checked @endif>
                      <label class="custom-control-label" for="is_pwd_post">{{__('garden.use')}}</label>
                  </div>
              </div>
              @endif
              @if(Route::current()->getName() == 'gardenFE.create')
              <input type="hidden" name="categories_gardens_id" value="{{$selectCategories->id}}">
              <input type="hidden" name="categories_gardens_name" value="{{$selectCategories->name}}">
              @endif
            </div>
            {{-- <div class="text-center">
                <a href="javascript:{}" class="btn btn-outline temporary" data-toggle="modal"
                    data-target="#confirmPopup2">{{__('garden.temporary_save')}}</a>
                <a href="javascript:{}" class="btn btn-outline preview">{{__('garden.preview')}}</a>
                <a href="javascript:{}" class="btn btn-primary save" data-toggle="modal"
                    data-target="#confirmPopup2">{{__('garden.enrollment')}}</a>
            </div> --}}
            {!! Theme::partial('submit_form',[
                'is_validate_image' => $garden ? 0 : 1,
                'route_preview' => route('gardenFE.preview'),
                'route_back' => route('gardenFE.list'),
                'idPreview' =>  $garden ? $garden->id : '',
                // 'show_popup' => !isset($garden) ? 1 : 0,
                 'show_popup' =>  0,
                'hint' =>  $garden->hint ?? '',
            ]) !!}

            </form>
        </div>
    </div>
    </div>
<script>

    $(function(){
        $('#categories_gardens_id').on('change',function(){
            $('#categories_gardens_name').val( $("#categories_gardens_id option:selected" ).text()   );
        })
    })

</script>
