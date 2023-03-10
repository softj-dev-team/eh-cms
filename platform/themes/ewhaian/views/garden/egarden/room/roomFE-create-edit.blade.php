<style>
.modal-backdrop {
    z-index: 900 !important;
}
.delete-categories {
    position: absolute;
    right: 0;
    top: 0;
    padding: 5px 10px 0 0;
}

</style>
<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
          <div class="category-menu">
            <h4 class="category-menu__title">{{__('egarden.room')}}</h4>
            <ul class="category-menu__links">
              <li class="category-menu__item">
                <a href="{{route('egardenFE.room.myroom.list') }}" title="{{__('egarden.room.my_room')}}">{{__('egarden.room.my_room')}}</a>
              </li>
              @if(\Request::route()->getName() == "egardenFE.room.create")
                <li class="category-menu__item active">
                    <a href="{{route('egardenFE.room.create')}}" title="Create contents">{{__('egarden.room.create_new_room')}}</a>
                </li>
              @else
                <li class="category-menu__item active">
                    <a title="Create contents">{{__('egarden.room.edit_room')}} #{{$room->id}}</a>
                </li>
              @endif
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
        <form @if(\Request::route()->getName() == "egardenFE.room.create") action="{{route('egardenFE.room.create')}}" @else  action="{{route('egardenFE.room.edit',['id' => $room->id])}}" @endif method="post" id="my_form" enctype="multipart/form-data">
             @csrf
            <div class="form form--border">
                    <h3 class="form__title"> @if(\Request::route()->getName() == "egardenFE.room.create") {{ __('egarden.room.create_new_room') }} @else {{__('egarden.room.edit_room')}} #{{$room->id}}@endif</h3>
                    <p class="text-right"><span class="required">*</span>{{__('egarden.room.required')}}</p>
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
                      <span>{{ session('err') }}</span>
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="alert alert-success" style="display: block" >
                      <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                      <p class="text-bold mr-4 pr-2">{{__('egarden.room.banner')}} <span class="required">*</span></p>
                      <div class="d-flex flex-wrap flex-grow-1">

                        <div class="input-group-image" style="margin: auto;">
                        <label for="uploadFile0" class="form-control form-control--upload" style="height: 30em; width: 28em;max-width: 28em;{{$room ? 'background-color : white; background-position : center;' :''  }} background-size: 40em; background-image: url('{{ $room ? get_image_url($room->images) : ''  }}') ">
                            <input type="file" class="" id="uploadFile0" accept="image/gif, image/jpeg, image/png" data-add-image name="images" value='{{$room ? $room->images: '' }}' >
                            <span class="form-control__label">
                              <svg width="30" height="30" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_big"></use>
                              </svg>
                            </span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class=" d-sm-flex flex-wrap custom-checkbox form-group pt-2">
                      <p class="text-bold mr-4 pr-2">덮개<span class="required">*</span></p>
                      <div class="d-flex flex-wrap flex-grow-1">

                        <div class="input-group-image" style="margin: auto;">
                        <label for="uploadFile1" class="form-control form-control--upload" style="height: 30em; width: 28em;max-width: 28em;{{$room ? 'background-color : white; background-position : center;' :''  }} background-size: 40em; background-image: url('{{ $room ? get_image_url($room->cover) : ''  }}') ">
                            <input type="file" class="" id="uploadFile1" accept="image/gif, image/jpeg, image/png" data-add-image name="cover" value='' >
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
                            <label for="description" class="form-control">
                            <input type="text" id="name" placeholder="&nbsp;" name="name" value="{{ $room ? $room->name : '' }}">
                              <span class="form-control__label">{{__('egarden.room.name')}}<span class="required">*</span></span>
                            </label>
                    </div>
                    <div class="form-group form-group--1">
                            <label for="description" class="form-control">
                            <input type="text" id="description" placeholder="&nbsp;" name="description" value="{{ $room ? $room->description : '' }}">
                              <span class="form-control__label">{{__('egarden.room.description')}} <span class="required">*</span></span>
                            </label>
                    </div>
                    <div class="form-group form-group--1">
                        @if (!is_null($room))
                        <a href="javascript:void(0)"  data-toggle="modal" data-target="#categoriesPopup">
                            <label for="categories" class="form-control">
                                    <span class="form-control__label">
                                        카테고리<span class="required">*</span>
                                </span>
                            </label>
                        </a>
                        @endif
                        @foreach ($categoreisRoom as $item)
                        <div style="position: relative" >
                            <div>
                                {!! Theme::partial('garden.elements.showCategories',['item' => $item]) !!}
                            </div>

                            <div class="delete-categories" style="position: absolute;right: ;">
                                <a  href="javascript:void(0)" class="deleteEgarden" title="{{__('egarden.remove_egarden')}}" data-toggle="modal" data-value="{{$item->id}}" data-target="#confirmDelete" >
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div style="margin: 20px 0">
                        {!! Theme::partial('upload_link_file',['link'=> $room ? $room->link : '',
                         'file_upload' => $room ? $room->file_upload : '' ]) !!}
                    </div>
                    <div class="d-sm-flex" style="margin-top: 20px;">
                        <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('egarden.room.status')}} <span class="required">*</span></span>
                        <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset($room) && $room->status == "publish" ) selected @endif>{{__('egarden.room.publish')}}</option>
                                    <option value="draft" @if(isset($room) && $room->status == "draft" ) selected @endif>{{__('egarden.room.draft')}}</option>
                                    <option value="pending" @if(isset($room) && $room->status == "pending" ) selected @endif>{{__('egarden.room.pending')}}</option>
                                </select>
                        </div>
                    </div>
                    <div class="text-center" style="margin-top: 20px;">
                        @if(\Request::route()->getName() == "egardenFE.room.create")
                            <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('egarden.room.save')}}</a>
                        @else
                            <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('egarden.room.update')}}</a>
                        @endif
                    </div>
            </div>
        </form>
        </div>

      </div>
    </div>
  </main>
@if (!is_null($room))
<div class="modal fade" id="categoriesPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 999">
    <div class="modal-dialog modal-dialog-centered  modal-md" role="document" style="text-align: center;">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{route('egardenFE.room.categories.store',['idRoom' => $room->id])}}" method="post">
                    @csrf
                    <h3 class="text-bold mt-2 py-3 text-center">New categories</h3>
                    <div class="form-group form-group--1 mb-4">
                        <label for="name" class="form-control">
                            <input type="text" required id="name" name="name" placeholder="&nbsp;">
                            <span class="form-control__label">{{__('egarden.room.name')}}<span
                                    class="required">*</span></span>
                        </label>
                    </div>
                    <div class="form-group form-group--1 mb-4">
                        <label for="background" class="form-control">
                            <input type="text" required id="background" name="background" placeholder="&nbsp;" class=" jscolor" >
                            <span class="form-control__label">Background<span
                                    class="required">*</span></span>
                        </label>
                    </div>
                    <div class="form-group form-group--1 mb-4">
                        <label for="color" class="form-control">
                            <input type="text" required id="color" name="color" placeholder="&nbsp;" class=" jscolor" >
                            <span class="form-control__label">Color<span
                                    class="required">*</span></span>
                        </label>
                    </div>
                    <div class="button-group my-4 text-center">
                        <button type="button" class="btn btn-outline mr-lg-10"
                            data-dismiss="modal">{{__('campus.timetable.cancel')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('campus.timetable.create')}}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="/themes/ewhaian/js/jscolor.js"></script>
<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmDelete" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header align-items-center justify-content-lg-center">
        <span class="modal__key">
          <svg width="40" height="18" aria-hidden="true">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
          </svg>
        </span>
      </div>
      <div class="modal-body">

        <div class="d-lg-flex align-items-center mx-3">
          <div class="d-lg-flex align-items-start flex-grow-1">
            <div class="form-group form-group--1 flex-grow-1 mr-lg-30 mb-3">
              <label for="hint" class="form-control">
                <input type=" text" id="hint" value="Do you really want to delete this category?" placeholder="&nbsp;" readonly>
              </label>
            </div>
          </div>
          <form action="{{route('egardenFE.room.categories.delete')}}" method="post">
            @csrf
            <div class="button-group mb-2">
                <input type="hidden" value="" id="categories_id" name="categories_id">
                <input type="hidden" value="{{$room->id}}" name="idRoom">
                <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">cancel</button>
                <button type="submit" class="btn btn-primary">Delete</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$( document ).ready(function() {
    $(document.body).on("click",'.deleteEgarden', function(e){
            let $this = $(this);
            $('#categories_id').val($this.attr('data-value'))
    });
});

</script>
@endif
